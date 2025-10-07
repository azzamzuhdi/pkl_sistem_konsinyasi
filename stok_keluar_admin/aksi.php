<?php
require_once '../db/conn.php';

if (isset($_POST['tambah_stok_keluar'])) {
    $id_supplier = $_POST['id_supplier'];
    $id_barang = $_POST['id_barang'];
    $jumlah_keluar = $_POST['jumlah_keluar'];
    $jenis_keluar = $_POST['jenis_keluar'];
    $tanggal = date("Y-m-d H:i:s");
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "INSERT INTO tb_stok_keluar (id_barang, id_supplier, jumlah, jenis_keluar, tanggal, keterangan) VALUES ('$id_barang','$id_supplier', '$jumlah_keluar', '$jenis_keluar', '$tanggal', '$keterangan')") or die(mysqli_error($conn));

    mysqli_query($conn, "UPDATE tb_barang SET sisa_stok = sisa_stok - $jumlah_keluar WHERE id_barang = '$id_barang'") or die(mysqli_error($conn));

    echo "<script>alert('Stok keluar berhasil dicatat');window.location='index.php?id_supplier=$id_supplier';</script>";

}