<?php
session_start();
require_once '../db/conn.php';
require '../adminlte/dist/fpdf/fpdf.php'; // pastikan path sesuai dengan folder kamu

if (!isset($_SESSION['id_supplier'])) {
    header('location:../auth');
    exit;
}

$id_supplier = $_SESSION['id_supplier'];

// Query data penjualan
$query = mysqli_query($conn, "
    SELECT 
        sk.id_keluar,
        s.id_supplier,
        sk.tanggal,
        sk.id_barang,
        b.nama_barang,
        b.kode_barang,
        b.harga_konsinyasi,
        sk.jumlah,
        sk.jenis_keluar,
        sk.status_pembayaran,
        (sk.jumlah * b.harga_konsinyasi) AS total
    FROM tb_stok_keluar sk
    JOIN tb_barang b ON sk.id_barang = b.id_barang
    JOIN tb_supplier s ON b.id_supplier = s.id_supplier
    WHERE sk.jenis_keluar = 'Terjual' 
    AND s.id_supplier = '$id_supplier'
    ORDER BY sk.tanggal DESC
");

class PDF extends FPDF
{
    function Header()
    {
        // Logo (posisi x=10, y=6, ukuran lebar 25mm)
        $this->Image('../logo.png', 10, 8, 25); // ubah path logo sesuai lokasi kamu

        // Nama toko dan alamat
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, 'APOTEK RADINKA', 0, 1, 'C');

        $this->SetFont('Arial', '', 11);
        $this->Cell(190, 6, 'Jl. Raya Dukuhturi No.12, Bumiayu, Brebes, Jawa Tengah', 0, 1, 'C');
        $this->Cell(190, 6, 'Telp: (0289) 123456 | Email: apotekradinka@gmail.com', 0, 1, 'C');

        // Garis pembatas ganda
        $this->Ln(3);
        $this->SetLineWidth(0.8);
        $this->Line(10, 32, 200, 32);
        $this->SetLineWidth(0.2);
        $this->Line(10, 33, 200, 33);

        $this->Ln(10);

        // Judul laporan
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 8, 'LAPORAN PENJUALAN SUPPLIER', 0, 1, 'C');
        $this->Ln(3);
    }

    function Footer()
    {
        // Posisi 15 mm dari bawah
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i') . ' | Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Kode', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Harga/Pcs', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Total', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);

$no = 1;
$grand_total = 0;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(25, 7, date('d-m-Y', strtotime($row['tanggal'])), 1, 0, 'C');
        $pdf->Cell(25, 7, $row['kode_barang'], 1, 0, 'C');
        $pdf->Cell(45, 7, $row['nama_barang'], 1, 0, 'L');
        $pdf->Cell(20, 7, $row['jumlah'] . ' pcs', 1, 0, 'C');
        $pdf->Cell(25, 7, 'Rp ' . number_format($row['harga_konsinyasi'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(40, 7, 'Rp ' . number_format($row['total'], 0, ',', '.'), 1, 1, 'R');
        $grand_total += $row['total'];
    }

    // Total keseluruhan
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(150, 8, 'Total Penjualan', 1, 0, 'R', true);
    $pdf->Cell(40, 8, 'Rp ' . number_format($grand_total, 0, ',', '.'), 1, 1, 'R', true);
} else {
    $pdf->Cell(190, 8, 'Tidak ada data penjualan untuk supplier ini', 1, 1, 'C');
}

$pdf->Output('I', 'Laporan_Penjualan_Supplier.pdf');
?>
