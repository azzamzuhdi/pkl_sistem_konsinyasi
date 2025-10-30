<?php
require_once '../db/conn.php';
require '../adminlte/dist/fpdf/fpdf.php';

$date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($conn, $_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($conn, $_GET['date_to']) : '';

// Bangun kondisi tanggal
$where_date = '';
if (!empty($date_from) && !empty($date_to)) {
    $where_date = " AND t.tanggal BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'";
} elseif (!empty($date_from)) {
    $where_date = " AND t.tanggal >= '$date_from 00:00:00'";
} elseif (!empty($date_to)) {
    $where_date = " AND t.tanggal <= '$date_to 23:59:59'";
}

// Ambil data barang rusak/kadaluarsa dari tb_rusak (asumsi tabel bernama tb_rusak atau tb_retur_barang sebelumnya)
$query = mysqli_query($conn, "
    SELECT r.id_retur, r.tanggal, b.kode_barang, b.nama_barang, r.jumlah, r.keterangan
    FROM tb_retur_barang r
    JOIN tb_barang b ON r.id_barang = b.id_barang
    WHERE 1=1 $where_date
    ORDER BY r.tanggal DESC
") or die(mysqli_error($conn));

class PDF_LAP_RUSAK extends FPDF
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
        $this->Cell(190, 8, 'LAPORAN BARANG RUSAK / KADALUARSA', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i') . ' | Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF_LAP_RUSAK('P', 'mm', 'A4');
$pdf->AddPage();

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Kode', 1, 0, 'C', true);
$pdf->Cell(75, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Jumlah', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$no = 1;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(35, 7, date('d-m-Y', strtotime($row['tanggal'])), 1, 0, 'C');
        $pdf->Cell(30, 7, $row['kode_barang'], 1, 0, 'C');
        $pdf->Cell(75, 7, $row['nama_barang'], 1, 0, 'L');
        $pdf->Cell(40, 7, $row['jumlah'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 8, 'Tidak ada data barang rusak/kadaluarsa untuk rentang tanggal ini', 1, 1, 'C');
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

$pdf->Output('I', 'Laporan_Rusak_Admin.pdf');

?>
<?php
require_once '../db/conn.php';
require '../adminlte/dist/fpdf/fpdf.php';

$date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($conn, $_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($conn, $_GET['date_to']) : '';

$where_date = '';
if (!empty($date_from) && !empty($date_to)) {
    $where_date = " AND r.tanggal BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'";
} elseif (!empty($date_from)) {
    $where_date = " AND r.tanggal >= '$date_from 00:00:00'";
} elseif (!empty($date_to)) {
    $where_date = " AND r.tanggal <= '$date_to 23:59:59'";
}

$query = mysqli_query($conn, "SELECT r.tanggal, b.kode_barang, b.nama_barang, r.jumlah, r.keterangan
    FROM tb_retur r
    JOIN tb_barang b ON r.id_barang = b.id_barang
    WHERE r.jenis = 'Rusak' $where_date
    ORDER BY r.tanggal DESC") or die(mysqli_error($conn));

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
        $this->Cell(190, 8, 'LAPORAN BARANG RUSAK', 0, 1, 'C');
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

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Kode', 1, 0, 'C', true);
$pdf->Cell(85, 8, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Jumlah', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$no = 1;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(35, 7, date('d-m-Y H:i', strtotime($row['tanggal'])), 1, 0, 'C');
        $pdf->Cell(30, 7, $row['kode_barang'], 1, 0, 'C');
        $pdf->Cell(85, 7, $row['nama_barang'], 1, 0, 'L');
        $pdf->Cell(30, 7, $row['jumlah'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 8, 'Tidak ada data untuk rentang tanggal ini', 1, 1, 'C');
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

$pdf->Output('I', 'Laporan_Rusak.pdf');
?>