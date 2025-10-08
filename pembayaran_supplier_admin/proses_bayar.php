<?php
require '../db/conn.php';

$id_keluar = $_POST['id_keluar'];
$id_supplier = $_POST['id_supplier'];
$total_pembayaran = $_POST['total_pembayaran'];
$tanggal_pembayaran = $_POST['tanggal_pembayaran'];
$keterangan = $_POST['keterangan'];

// Simpan ke tabel pembayaran supplier
$query = "INSERT INTO tb_pembayaran_supplier 
(id_supplier, id_keluar, total_pembayaran, tanggal_pembayaran, keterangan)
VALUES ('$id_supplier', '$id_keluar', '$total_pembayaran', '$tanggal_pembayaran', '$keterangan')";

if (mysqli_query($conn, $query)) {
    mysqli_query($conn, "UPDATE tb_stok_keluar 
                         SET status_pembayaran = 'Sudah Dibayar' 
                         WHERE id_keluar = '$id_keluar'");

    echo "<script>alert('Pembayaran berhasil disimpan');window.location='index.php?id_supplier=$id_supplier';</script>";
} else {
    echo "Gagal menyimpan data pembayaran: " . mysqli_error($conn);
}
?>