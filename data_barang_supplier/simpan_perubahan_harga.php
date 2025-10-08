<?php
include_once '../db/conn.php';
session_start();

if (isset($_POST['ajukan'])) {
    $id_barang = $_POST['id_barang'];
    $id_supplier = $_POST['id_supplier'];
    $harga_baru = $_POST['harga_baru'];
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);

    // ambil harga lama
    $query_harga_lama = mysqli_query($conn, "SELECT harga_konsinyasi FROM tb_barang WHERE id_barang = '$id_barang'");
    $harga_lama = mysqli_fetch_assoc($query_harga_lama)['harga_konsinyasi'];

    // simpan pengajuan ke tabel perubahan harga
    mysqli_query($conn, "INSERT INTO tb_perubahan_harga (id_barang, id_supplier, harga_lama, harga_baru, alasan, status)
        VALUES ('$id_barang', '$id_supplier', '$harga_lama', '$harga_baru', '$alasan', 'Menunggu')")
        or die(mysqli_error($conn));

    echo "<script>alert('Pengajuan perubahan harga berhasil dikirim ke admin.');window.location='data_barang_supplier.php';</script>";
}
?>
