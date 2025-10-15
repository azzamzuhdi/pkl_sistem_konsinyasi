<?php
require_once '../db/conn.php';

if (isset($_POST['ajukan_retur'])) {
    $id_supplier = $_POST['id_supplier'];
    $id_barang = $_POST['id_barang'];
    $id_keluar = $_POST['id_keluar'];
    $jumlah_retur = $_POST['jumlah_retur'];
    $alasan = $_POST['alasan'];
    $keterangan = $_POST['keterangan'];
    $tanggal_retur = date("Y-m-d H:i:s");
    $status_retur = 'Sudah';

    $insert = mysqli_query($conn, "
        INSERT INTO tb_retur_barang (id_supplier, id_barang, jumlah_retur, alasan, tanggal_retur, status_retur, keterangan)
        VALUES ('$id_supplier', '$id_barang', '$jumlah_retur', '$alasan', '$tanggal_retur', 'Menunggu', '$keterangan')
    ");
    mysqli_query($conn, "UPDATE tb_stok_keluar SET status_retur = 'Sudah' WHERE id_keluar = '$id_keluar' AND id_supplier = '$id_supplier' AND status_retur = 'Belum'") or die(mysqli_error($conn));
    
    mysqli_query($conn, "INSERT INTO tb_riwayat_retur (id_barang, id_supplier, jumlah_retur, alasan, tanggal_retur, status_retur)
                                       VALUES ('$id_barang', '$id_supplier', '$jumlah', '$alasan', '$tanggal', 'Diterima')");

    if ($insert) {
        echo "<script>alert('Pengajuan retur berhasil dikirim!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengirim retur.'); window.history.back();</script>";
    }
}
?>