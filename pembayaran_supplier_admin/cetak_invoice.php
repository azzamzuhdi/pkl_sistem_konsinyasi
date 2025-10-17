<?php
require_once '../db/conn.php';

// include FPDF from AdminLTE dist
$fpdf_path = __DIR__ . '/../adminlte/dist/fpdf/fpdf.php';
if (!file_exists($fpdf_path)) {
    die('FPDF library tidak ditemukan. Pastikan file ada di adminlte/dist/fpdf/fpdf.php');
}
require_once $fpdf_path; // sesuaikan lokasi FPDF

// Ambil id_keluar dari query string
$id_keluar = isset($_GET['id_keluar']) ? mysqli_real_escape_string($conn, $_GET['id_keluar']) : null;
if (!$id_keluar) {
    die('Parameter id_keluar diperlukan');
}

// Ambil data pembayaran dan detail barang & supplier
$q = mysqli_query($conn, "
    SELECT p.*, sk.tanggal AS tanggal_keluar, sk.id_keluar, sk.id_barang, sk.jumlah, b.nama_barang, b.kode_barang, b.harga_konsinyasi, s.nama_supplier, s.alamat, s.no_hp
    FROM tb_pembayaran_supplier p
    JOIN tb_stok_keluar sk ON p.id_keluar = sk.id_keluar
    JOIN tb_barang b ON sk.id_barang = b.id_barang
    JOIN tb_supplier s ON p.id_supplier = s.id_supplier
    WHERE p.id_keluar = '$id_keluar'
    LIMIT 1
") or die(mysqli_error($conn));

if (mysqli_num_rows($q) == 0) {
    die('Data pembayaran tidak ditemukan');
}

$row = mysqli_fetch_assoc($q);

// --- Generate invoice number per day: INV-YYYYMMDD-0001 ---
// Check if table has column `invoice_number`
$col_check = mysqli_query($conn, "SHOW COLUMNS FROM tb_pembayaran_supplier LIKE 'invoice_number'");
$has_invoice_col = (mysqli_num_rows($col_check) > 0);

// If invoice already stored in DB (and column exists), use it
$invoice_number = null;
if ($has_invoice_col && isset($row['invoice_number']) && !empty($row['invoice_number'])) {
    $invoice_number = $row['invoice_number'];
} else {
    // Build date part from tanggal_pembayaran (only date portion)
    $date_part = date('Ymd', strtotime($row['tanggal_pembayaran']));

    // Count how many payments already exist on the same date
    $date_sql = date('Y-m-d', strtotime($row['tanggal_pembayaran']));
    $cnt_q = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM tb_pembayaran_supplier WHERE DATE(tanggal_pembayaran) = '$date_sql'") or die(mysqli_error($conn));
    $cnt_row = mysqli_fetch_assoc($cnt_q);
    $seq = intval($cnt_row['jumlah']) + 1; // next sequence for that date
    $seq_str = str_pad($seq, 4, '0', STR_PAD_LEFT);
    $invoice_number = "INV-{$date_part}-{$seq_str}";

    // If there is an invoice_number column, store generated invoice number for this record
    if ($has_invoice_col) {
        mysqli_query($conn, "UPDATE tb_pembayaran_supplier SET invoice_number = '$invoice_number' WHERE id_keluar = '$id_keluar'") or die(mysqli_error($conn));
    }
}

// Define PDF class with Header/Footer similar to laporan_retur_supplier
class PDF extends FPDF
{
    public $invoice_number = '';
    public $payment_date = '';

    function Header()
    {
        // Logo toko
        if (file_exists(__DIR__ . '/../logo.png')) {
            $this->Image('../logo.png', 10, 8, 25);
        }

        // Nama toko dan alamat (centered)
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 8, 'TOKO ALIN', 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, 'Jl. Raya Pruwatan No.12, Bumiayu, Brebes, Jawa Tengah', 0, 1, 'C');
        $this->Cell(0, 6, 'Telp: (0289) 123456 | Email: tokoalin@gmail.com', 0, 1, 'C');

        // Garis pembatas ganda
        $this->Ln(3);
        $this->SetLineWidth(0.8);
        $this->Line(10, 36, 200, 36);
        $this->SetLineWidth(0.2);
        $this->Line(10, 37, 200, 37);

        // Judul dan nomor invoice
        $this->Ln(8);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 7, 'INVOICE PEMBAYARAN', 0, 1, 'C');
        $this->Ln(2);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, 'No. Invoice: ' . $this->invoice_number, 0, 1, 'C');
        $this->Ln(3);
    }

    function Footer()
    {
        // Posisi footer
        $this->SetY(-18);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 6, 'Dicetak pada: ' . date('d-m-Y H:i') . ' | Halaman ' . $this->PageNo(), 0, 1, 'C');
        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 6, 'Terima kasih atas kerjasama Anda.', 0, 0, 'C');
    }
}

// Generate PDF invoice
$pdf = new PDF('P', 'mm', 'A4');
$pdf->invoice_number = $invoice_number;
$pdf->payment_date = $row['tanggal_pembayaran'];
$pdf->AddPage();

// Supplier / Penerima info (left) and Invoice meta (right)
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(45, 7, 'Supplier', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(80, 7, ': ' . $row['nama_supplier'], 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(30, 7, 'Tanggal', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, ': ' . date('d-m-Y', strtotime($row['tanggal_pembayaran'])), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(45, 7, 'Alamat', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(80, 7, ': ' . $row['alamat'], 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(30, 7, 'No. Invoice', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, ': ' . $invoice_number, 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(45, 7, 'No. HP', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(80, 7, ': ' . $row['no_hp'], 0, 1);

$pdf->Ln(6);

// Table header
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Kode', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Harga/Pcs', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Total', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 11);

// Data row (this payment refers to one id_keluar / one item)
$pdf->Cell(10, 7, '1', 1, 0, 'C');
$pdf->Cell(30, 7, $row['kode_barang'] ?? '-', 1, 0, 'C');
$pdf->Cell(60, 7, $row['nama_barang'], 1, 0, 'L');
$pdf->Cell(25, 7, $row['jumlah'] . ' pcs', 1, 0, 'C');
$pdf->Cell(30, 7, 'Rp ' . number_format($row['harga_konsinyasi'], 0, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 7, 'Rp ' . number_format($row['total_pembayaran'], 0, ',', '.'), 1, 1, 'R');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(155, 8, 'Total Pembayaran', 1, 0, 'R', true);
$pdf->Cell(35, 8, 'Rp ' . number_format($row['total_pembayaran'], 0, ',', '.'), 1, 1, 'R', true);

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, 'Pembayaran: ' . ($row['keterangan'] ?? '-'), 0, 1);

$pdf->Ln(12);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Bumiayu, ' . date('d F Y'), 0, 1, 'C');
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Mengetahui,', 0, 1, 'C');
$pdf->Ln(20);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, '(____________________)', 0, 1, 'C');

$pdf->Output('I', 'invoice_' . $invoice_number . '.pdf');
