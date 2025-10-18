<?php
require '../db/conn.php';

$id_keluar = $_POST['id_keluar'];
$id_supplier = $_POST['id_supplier'];
$total_pembayaran = $_POST['total_pembayaran'];
$tanggal_pembayaran = $_POST['tanggal_pembayaran'];
$keterangan = $_POST['keterangan'];

// Simpan ke tabel pembayaran supplier
// pastikan kolom notifikasi_sent ada
$col_check = mysqli_query($conn, "SHOW COLUMNS FROM tb_pembayaran_supplier LIKE 'notifikasi_sent'");
if (mysqli_num_rows($col_check) == 0) {
    mysqli_query($conn, "ALTER TABLE tb_pembayaran_supplier ADD COLUMN notifikasi_sent TINYINT(1) DEFAULT 0") or die(mysqli_error($conn));
}

// periksa apakah ada kolom invoice_number
$col_inv = mysqli_query($conn, "SHOW COLUMNS FROM tb_pembayaran_supplier LIKE 'invoice_number'");
$has_invoice_col = (mysqli_num_rows($col_inv) > 0);

// jika ada, generate invoice_number INV-YYYYMMDD-XXXX (berdasarkan tanggal_pembayaran)
$invoice_number_sql_part = '';
if ($has_invoice_col) {
    $date_sql = date('Y-m-d', strtotime($tanggal_pembayaran));
    $cnt_q = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM tb_pembayaran_supplier WHERE DATE(tanggal_pembayaran) = '$date_sql'") or die(mysqli_error($conn));
    $cnt_row = mysqli_fetch_assoc($cnt_q);
    $seq = intval($cnt_row['jumlah']) + 1;
    $seq_str = str_pad($seq, 4, '0', STR_PAD_LEFT);
    $inv = 'INV-' . date('Ymd', strtotime($tanggal_pembayaran)) . '-' . $seq_str;
    $invoice_number_sql_part = ", invoice_number = '" . mysqli_real_escape_string($conn, $inv) . "'";
}

$query = "INSERT INTO tb_pembayaran_supplier 
(id_supplier, id_keluar, total_pembayaran, tanggal_pembayaran, keterangan, notifikasi_sent" . ($has_invoice_col ? ', invoice_number' : '') . ")
VALUES ('" . mysqli_real_escape_string($conn, $id_supplier) . "', '" . mysqli_real_escape_string($conn, $id_keluar) . "', '" . mysqli_real_escape_string($conn, $total_pembayaran) . "', '" . mysqli_real_escape_string($conn, $tanggal_pembayaran) . "', '" . mysqli_real_escape_string($conn, $keterangan) . "', 1" . ($has_invoice_col ? (", '" . mysqli_real_escape_string($conn, $inv) . "'") : '') . ")";

if (mysqli_query($conn, $query)) {
    mysqli_query($conn, "UPDATE tb_stok_keluar 
                         SET status_pembayaran = 'Sudah Dibayar' 
                         WHERE id_keluar = '$id_keluar'");

    echo "<script>alert('Pembayaran berhasil disimpan');window.location='index.php?id_supplier=$id_supplier';</script>";
} else {
    echo "Gagal menyimpan data pembayaran: " . mysqli_error($conn);
}
?>