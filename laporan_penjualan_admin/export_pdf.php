<?php
require_once '../db/conn.php';
require '../adminlte/dist/fpdf/fpdf.php';

if (!isset($_GET['id_supplier'])) {
    die('Supplier tidak ditemukan!');
}

$id_supplier = $_GET['id_supplier'];
$date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($conn, $_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($conn, $_GET['date_to']) : '';

// Ambil data supplier
$query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
$row_supplier = mysqli_fetch_assoc($query_supplier);

// build where_date
$where_date = '';
if (!empty($date_from) && !empty($date_to)) {
    $where_date = " AND sk.tanggal BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'";
} elseif (!empty($date_from)) {
    $where_date = " AND sk.tanggal >= '$date_from 00:00:00'";
} elseif (!empty($date_to)) {
    $where_date = " AND sk.tanggal <= '$date_to 23:59:59'";
}

// Ambil data penjualan
$query = mysqli_query($conn, "
    SELECT 
        sk.id_keluar,
        s.id_supplier,
        sk.tanggal,
        sk.id_barang,
        b.nama_barang,
        b.kode_barang,
        b.harga_jual,
        sk.jumlah,
        (sk.jumlah * b.harga_jual) AS total
    FROM tb_stok_keluar sk
    JOIN tb_barang b ON sk.id_barang = b.id_barang
    JOIN tb_supplier s ON b.id_supplier = s.id_supplier
    WHERE sk.jenis_keluar = 'Terjual' AND s.id_supplier = '$id_supplier' $where_date
    ORDER BY sk.tanggal DESC
") or die(mysqli_error($conn));

class PDF_LAP_PENJUALAN extends FPDF
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
        $this->Cell(190, 8, 'LAPORAN PENJUALAN', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i') . ' | Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}
$pdf = new PDF_LAP_PENJUALAN('P', 'mm', 'A4');
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

// Header table
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Kode Barang', 1, 0, 'C', true);
$pdf->Cell(75, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Jumlah / Total', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$no = 1; $total = 0;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(35, 7, date('d-m-Y', strtotime($row['tanggal'])), 1, 0, 'C');
        $pdf->Cell(30, 7, $row['kode_barang'], 1, 0, 'C');
        $pdf->Cell(75, 7, $row['nama_barang'], 1, 0, 'L');
        $pdf->Cell(40, 7, $row['jumlah'] . ' pcs / Rp ' . number_format($row['total'],0,',','.'), 1, 1, 'R');
        $total += $row['total'];
    }
    $pdf->Ln(6);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(190, 8, 'Total Penjualan: Rp ' . number_format($total,0,',','.'), 0, 1, 'R');
} else {
    $pdf->Cell(190, 8, 'Tidak ada data penjualan untuk supplier ini', 1, 1, 'C');
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

$pdf->Output('I', 'Laporan_Penjualan_Admin.pdf');