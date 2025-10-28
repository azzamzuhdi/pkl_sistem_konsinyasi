<?php
require_once '../db/conn.php';

if (isset($_POST['edit_barang'])) {
    $id_supplier = $_POST['id_supplier2'];
    $id_barang = $_POST['id_barang2'];
    $kode_barang = $_POST['kode_barang2'];
    $nama_barang = $_POST['nama_barang2'];
    $harga_konsinyasi = $_POST['harga_konsinyasi2'];
    $harga_jual = $_POST['harga_jual2'];
    mysqli_query($conn, "UPDATE tb_barang SET kode_barang = '$kode_barang', nama_barang = '$nama_barang', harga_konsinyasi = '$harga_konsinyasi', harga_jual = '$harga_jual' WHERE id_barang = '$id_barang'") or die(mysqli_error($conn));
    echo "<script>alert('Data barang berhasil edit');window.location='index.php?id_supplier=$id_supplier';</script>";
}

if (isset($_POST['tambah_barang'])) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_konsinyasi = $_POST['harga_konsinyasi'];
    $harga_jual = $_POST['harga_jual'];
    $stok_masuk = $_POST['stok_masuk'];
    $id_supplier = $_POST['id_supplier'];
    $sisa_stok = $stok_masuk;

    $query_cek = mysqli_query($conn, "SELECT kode_barang, id_supplier FROM tb_barang WHERE kode_barang='$kode_barang' AND id_supplier = '$id_supplier'") or die(mysqli_error($conn));
    $rv = mysqli_num_rows($query_cek);
    if ($rv > 0) {
        echo "<script>alert('Barang sudah ada !!');window.location='index.php?id_supplier=$id_supplier';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO tb_barang (id_supplier, kode_barang, nama_barang, harga_konsinyasi, harga_jual, stok_masuk, sisa_stok) VALUES ('$id_supplier', '$kode_barang', '$nama_barang', '$harga_konsinyasi', '$harga_jual', '$stok_masuk', '$sisa_stok')") or die(mysqli_error($conn));
        echo "<script>alert('Data barang berhasil ditambahkan');window.location='index.php?id_supplier=$id_supplier';</script>";
    }
}

if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];

    // cari id_supplier dari barang yang akan dihapus UNTUKK REDIRECT
    $result = mysqli_query($conn, "SELECT id_supplier FROM tb_barang WHERE id_barang = '$id_barang'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);
    $id_supplier = $row['id_supplier'];

    // hapus data
    mysqli_query($conn, "DELETE FROM tb_barang WHERE id_barang = '$id_barang'") or die(mysqli_error($conn));
    echo "<script>alert('Data barang berhasil dihapus');window.location='index.php?id_supplier=$id_supplier';</script>";
}

