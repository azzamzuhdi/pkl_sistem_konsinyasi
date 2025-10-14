<?php
require_once '../db/conn.php';

if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    echo "<script>alert('Data tidak lengkap!');window.location='index.php';</script>";
    exit;
}

$id_retur = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi == 'terima') {
    $status = 'Diterima';
} elseif ($aksi == 'tolak') {
    $status = 'Ditolak';
} else {
    echo "<script>alert('Aksi tidak valid!');window.location='index.php';</script>";
    exit;
}

// Ambil data retur dulu
$query_retur = mysqli_query($conn, "SELECT * FROM tb_retur_barang WHERE id_retur='$id_retur'");
$data_retur = mysqli_fetch_assoc($query_retur);

if (!$data_retur) {
    echo "<script>alert('Data retur tidak ditemukan!');window.location='index.php';</script>";
    exit;
}

// Update status retur
$update = mysqli_query($conn, "UPDATE tb_retur_barang SET status_retur='$status' WHERE id_retur='$id_retur'") or die(mysqli_error($conn));

if ($update && $status == 'Diterima') {
    // Tambahkan logika jika retur diterima:
    // contoh: stok dikembalikan ke supplier
    $id_barang = $data_retur['id_barang'];
    $jumlah_retur = $data_retur['jumlah_retur'];

    // Update stok barang di supplier
    mysqli_query($conn, "UPDATE tb_barang SET sisa_stok = sisa_stok - $jumlah_retur WHERE id_barang = '$id_barang'") or die(mysqli_error($conn));
}

echo "<script>alert('Proses retur berhasil diperbarui');window.location='index.php';</script>";
exit;
?>
