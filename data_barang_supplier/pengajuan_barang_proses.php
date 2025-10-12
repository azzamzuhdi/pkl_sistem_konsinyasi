<?php
require_once '../db/conn.php';

if (isset($_POST['kirim_pengajuan'])) {
    $id_supplier = $_POST['id_supplier'];
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_konsinyasi = $_POST['harga_konsinyasi'];
    $stok_masuk = $_POST['stok_masuk'];
    $status_pengajuan = 'Menunggu';

    // Simpan ke tabel tb_pengajuan_barang
    $query = "INSERT INTO tb_pengajuan_barang 
              (id_supplier, kode_barang, nama_barang, harga_konsinyasi, stok_masuk, status_pengajuan)
              VALUES
              ('$id_supplier', '$kode_barang', '$nama_barang', '$harga_konsinyasi', '$stok_masuk', '$status_pengajuan')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Pengajuan barang berhasil dikirim! Menunggu konfirmasi admin.');
                window.location.href='index.php?id_supplier=$id_supplier';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengirim pengajuan: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: data_barang_supplier.php");
    exit;
}
?>