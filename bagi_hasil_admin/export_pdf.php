<?php
require_once '../db/conn.php';
require '../adminlte/dist/fpdf/fpdf.php';

if (!isset($_GET['id_supplier'])) {
    die("Supplier tidak ditemukan!");
}

$id_supplier = $_GET['id_supplier'];

// Ambil data supplier
$query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
$row_supplier = mysqli_fetch_assoc($query_supplier);

// Ambil data bagi hasil
$query = mysqli_query($conn, "
    SELECT 
        sk.tanggal,
        b.nama_barang,
        sk.jumlah,
        b.harga_konsinyasi,
        b.harga_jual,
        (sk.jumlah * b.harga_jual) AS total_penjualan,
        (sk.jumlah * b.harga_konsinyasi) AS hak_supplier,
        ((sk.jumlah * b.harga_jual) - (sk.jumlah * b.harga_konsinyasi)) AS keuntungan_toko
    FROM tb_stok_keluar sk
    JOIN tb_barang b ON sk.id_barang = b.id_barang
    JOIN tb_supplier s ON b.id_supplier = s.id_supplier
    WHERE sk.jenis_keluar = 'Terjual' AND s.id_supplier = '$id_supplier'
    ORDER BY sk.tanggal DESC
") or die(mysqli_error($conn));

class PDF extends FPDF
{
    function Header()
    {
        // Logo dan identitas toko
        $this->Image('../logo.png', 10, 8, 25);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, 'TOKO ALIN', 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(190, 6, 'Jl. Raya Pruwatan No.12, Bumiayu, Brebes, Jawa Tengah', 0, 1, 'C');
        $this->Cell(190, 6, 'Telp: (0289) 123456 | Email: tokoalin@gmail.com', 0, 1, 'C');

        // Garis pembatas
        $this->Ln(3);
        $this->SetLineWidth(0.8);
        $this->Line(10, 32, 200, 32);
        $this->SetLineWidth(0.2);
        $this->Line(10, 33, 200, 33);

        // Judul laporan
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 8, 'LAPORAN BAGI HASIL PENJUALAN', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i') . ' | Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Membuat PDF
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();

// ====================
// Data Supplier
// ====================
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 8, 'Nama Supplier', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(100, 8, ': ' . $row_supplier['nama_supplier'], 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 8, 'Alamat', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(100, 8, ': ' . $row_supplier['alamat'], 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 8, 'No. HP', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(100, 8, ': ' . $row_supplier['no_hp'], 0, 1);

$pdf->Ln(5);

// ====================
// Header tabel
// ====================
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(28, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(18, 8, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(26, 8, 'Harga Kons.', 1, 0, 'C', true);
$pdf->Cell(26, 8, 'Harga Jual', 1, 0, 'C', true);
$pdf->Cell(32, 8, 'Total', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$no = 1;
$total_penjualan = 0;
$hak_supplier = 0;
$keuntungan_toko = 0;

// ====================
// Isi tabel
// ====================
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(28, 7, date('d-m-Y', strtotime($row['tanggal'])), 1, 0, 'C');
        $pdf->Cell(50, 7, $row['nama_barang'], 1, 0, 'L');
        $pdf->Cell(18, 7, $row['jumlah'] . ' pcs', 1, 0, 'C');
        $pdf->Cell(26, 7, 'Rp ' . number_format($row['harga_konsinyasi'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(26, 7, 'Rp ' . number_format($row['harga_jual'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(32, 7, 'Rp ' . number_format($row['total_penjualan'], 0, ',', '.'), 1, 1, 'R');

        $total_penjualan += $row['total_penjualan'];
        $hak_supplier += $row['hak_supplier'];
        $keuntungan_toko += $row['keuntungan_toko'];
    }
} else {
    $pdf->Cell(190, 8, 'Tidak ada data penjualan untuk supplier ini', 1, 1, 'C');
}

// ====================
// Ringkasan total
// ====================
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(190, 8, 'Ringkasan Bagi Hasil', 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(190, 6, 'Total Penjualan : Rp ' . number_format($total_penjualan, 0, ',', '.'), 0, 1);
$pdf->Cell(190, 6, 'Hak Supplier    : Rp ' . number_format($hak_supplier, 0, ',', '.'), 0, 1);
$pdf->Cell(190, 6, 'Keuntungan Toko : Rp ' . number_format($keuntungan_toko, 0, ',', '.'), 0, 1);

$pdf->Ln(10);

// ====================
// Tanda tangan
// ====================
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Bumiayu, ' . date('d F Y'), 0, 1, 'C');
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Mengetahui,', 0, 1, 'C');
$pdf->Ln(20);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, '(____________________)', 0, 1, 'C');

// Output PDF
$pdf->Output('I', 'Laporan_Bagi_Hasil_Supplier.pdf');
?>
