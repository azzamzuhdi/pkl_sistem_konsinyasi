<?php
require_once '../db/conn.php';

if (isset($_POST['tambah_stok_masuk'])) {
    $id_supplier = $_POST['id_supplier'];
    $id_barang = $_POST['id_barang'];
    $jumlah_stok = $_POST['jumlah_stok'];
    $tanggal = date("Y-m-d H:i:s");

    mysqli_query($conn, "INSERT INTO tb_stok_masuk (id_supplier, id_barang, jumlah_masuk, tanggal_masuk) VALUES ('$id_supplier', '$id_barang', '$jumlah_stok', '$tanggal')") or die(mysqli_error($conn));
    mysqli_query($conn, "UPDATE tb_barang SET stok_masuk = stok_masuk + '$jumlah_stok' WHERE id_barang = '$id_barang' ") or die(mysqli_error($conn));
    mysqli_query($conn, "UPDATE tb_barang SET stok_masuk = stok_masuk + '$jumlah_stok', sisa_stok = sisa_stok + '$jumlah_stok' WHERE id_barang = '$id_barang' ") or die(mysqli_error($conn));

    echo "<script>alert('Stok berhasil ditambahkan');window.location='index.php?id_supplier=$id_supplier';</script>";
}