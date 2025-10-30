<?php
require_once '../db/conn.php';
require '../adminlte/dist/fpdf/fpdf.php';

if (!isset($_GET['id_supplier'])) {
    die('Supplier tidak ditemukan!');
}

$id_supplier = $_GET['id_supplier'];

// Ambil data supplier
$query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
$row_supplier = mysqli_fetch_assoc($query_supplier);

// Ambil data stok masuk
$filter_tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($conn, $_GET['tanggal']) : '';
$where_date = '';
if (!empty($filter_tanggal)) {
    $where_date = " AND sm.tanggal_masuk = '$filter_tanggal'";
}
$query = mysqli_query($conn, "SELECT sm.tanggal_masuk, sm.jumlah_masuk, b.kode_barang, b.nama_barang
    FROM tb_stok_masuk sm
    JOIN tb_barang b ON sm.id_barang = b.id_barang
    WHERE sm.id_supplier = '$id_supplier' $where_date
    ORDER BY sm.tanggal_masuk DESC") or die(mysqli_error($conn));

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../logo.png', 10, 8, 25);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, 'TOKO ALIN', 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(190, 6, 'Jl. Raya Pruwatan No.12, Bumiayu, Brebes, Jawa Tengah', 0, 1, 'C');
        $this->Cell(190, 6, 'Telp: (0289) 123456 | Email: tokoalin@gmail.com', 0, 1, 'C');

        $this->Ln(3);
        $this->SetLineWidth(0.8);
        $this->Line(10, 32, 200, 32);
        $this->SetLineWidth(0.2);
        $this->Line(10, 33, 200, 33);

        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 8, 'LAPORAN STOK MASUK', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i') . ' | Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();

// Info supplier
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

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Tanggal Masuk', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Kode Barang', 1, 0, 'C', true);
$pdf->Cell(75, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Jumlah Masuk', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$no = 1;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(45, 7, date('d-m-Y H:i:s', strtotime($row['tanggal_masuk'])), 1, 0, 'C');
        $pdf->Cell(30, 7, $row['kode_barang'], 1, 0, 'C');
        $pdf->Cell(75, 7, $row['nama_barang'], 1, 0, 'L');
        $pdf->Cell(30, 7, $row['jumlah_masuk'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 8, 'Tidak ada data stok masuk untuk supplier ini', 1, 1, 'C');
}

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Bumiayu, ' . date('d F Y'), 0, 1, 'C');
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Mengetahui,', 0, 1, 'C');
$pdf->Ln(20);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, '(____________________)', 0, 1, 'C');

$pdf->Output('I', 'Laporan_Stok_Masuk_Supplier.pdf');

?>
