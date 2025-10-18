<?php
session_start();
require_once '../db/conn.php';
require '../adminlte/dist/fpdf/fpdf.php'; // sesuaikan lokasi FPDF kamu

if (!isset($_SESSION['id_supplier'])) {
    header('location:../auth');
    exit;
}

$id_supplier = $_SESSION['id_supplier'];

// Ambil data supplier
$query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
$row_supplier = mysqli_fetch_assoc($query_supplier);

// Query data retur
$query = mysqli_query($conn, "
    SELECT 
        r.id_retur,
        b.nama_barang,
        b.harga_konsinyasi,
        r.jumlah_retur,
        r.tanggal_retur,
        r.alasan,
        r.keterangan,
        r.status_retur
    FROM tb_retur_barang r
    JOIN tb_barang b ON r.id_barang = b.id_barang
    WHERE r.id_supplier = '$id_supplier'
    AND r.status_retur = 'Diterima'
    ORDER BY r.tanggal_retur DESC
") or die(mysqli_error($conn));

class PDF extends FPDF
{
    function Header()
    {
        // Logo toko
        $this->Image('../logo.png', 10, 8, 25); // ubah path sesuai lokasi logo kamu

        // Nama toko dan alamat
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, 'TOKO ALIN', 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(190, 6, 'Jl. Raya Pruwatan No.12, Bumiayu, Brebes, Jawa Tengah', 0, 1, 'C');
        $this->Cell(190, 6, 'Telp: (0289) 123456 | Email: tokoalin@gmail.com', 0, 1, 'C');

        // Garis pembatas ganda
        $this->Ln(3);
        $this->SetLineWidth(0.8);
        $this->Line(10, 32, 200, 32);
        $this->SetLineWidth(0.2);
        $this->Line(10, 33, 200, 33);

        // Judul laporan
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 8, 'LAPORAN RETUR BARANG SUPPLIER', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        // Posisi footer
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i') . ' | Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Inisialisasi PDF
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
$pdf->Cell(50, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Harga/Pcs', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Tanggal Retur', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Alasan Retur', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);

$no = 1;
$total_retur = 0;

// ====================
// Isi tabel
// ====================
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $subtotal = $row['jumlah_retur'] * $row['harga_konsinyasi'];
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(50, 7, $row['nama_barang'], 1, 0, 'L');
        $pdf->Cell(25, 7, $row['jumlah_retur'] . ' pcs', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Rp ' . number_format($row['harga_konsinyasi'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(30, 7, date('d-m-Y', strtotime($row['tanggal_retur'])), 1, 0, 'C');
        $pdf->Cell(45, 7, $row['alasan'], 1, 1, 'L');
        $total_retur += $subtotal;
    }

    // Total keseluruhan
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(145, 8, 'Total Nilai Retur Barang', 1, 0, 'R', true);
    $pdf->Cell(45, 8, 'Rp ' . number_format($total_retur, 0, ',', '.'), 1, 1, 'R', true);
} else {
    $pdf->Cell(190, 8, 'Tidak ada data retur untuk supplier ini', 1, 1, 'C');
}

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
$pdf->Output('I', 'Laporan_Retur_Supplier.pdf');
?>
