<?php
require_once '../db/conn.php';

if (isset($_POST['ajukan_retur'])) {
    $id_supplier = $_POST['id_supplier'];
    $id_barang = $_POST['id_barang'];
    $jumlah_retur = $_POST['jumlah_retur'];
    $alasan = $_POST['alasan'];
    $keterangan = $_POST['keterangan'];
    $tanggal_retur = date("Y-m-d H:i:s");

    $insert = mysqli_query($conn, "
        INSERT INTO tb_retur_barang (id_supplier, id_barang, jumlah_retur, alasan, tanggal_retur, status_retur, keterangan)
        VALUES ('$id_supplier', '$id_barang', '$jumlah_retur', '$alasan', '$tanggal_retur', 'Menunggu', '$keterangan')
    ");

    if ($insert) {
        echo "<script>alert('Pengajuan retur berhasil dikirim!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengirim retur.'); window.history.back();</script>";
    }
}
?>