<?php
require_once '../db/conn.php';

if (isset($_POST['tambah_stok_keluar'])) {
    $id_supplier = $_POST['id_supplier'];
    $id_barang = $_POST['id_barang'];
    $jumlah_keluar = $_POST['jumlah_keluar'];
    $jenis_keluar = $_POST['jenis_keluar'];
    $tanggal = date("Y-m-d H:i:s");
    $keterangan = $_POST['keterangan'];

    // Default status pembayaran dari database adalah "Belum Dibayar"
    $status_pembayaran = 'Belum Dibayar';

    // Jika jenis keluar adalah Kadaluarsa atau Rusak, ubah menjadi Tidak Terjual
    if (in_array($jenis_keluar, ['Kadaluarsa', 'Rusak'])) {
        $status_pembayaran = 'Tidak Terjual';
    }

    // Simpan data stok keluar
    $insert = mysqli_query($conn, "
        INSERT INTO tb_stok_keluar 
        (id_barang, id_supplier, jumlah, jenis_keluar, tanggal, keterangan, status_pembayaran)
        VALUES 
        ('$id_barang', '$id_supplier', '$jumlah_keluar', '$jenis_keluar', '$tanggal', '$keterangan', '$status_pembayaran')
    ") or die(mysqli_error($conn));

    // Kurangi sisa stok di tabel barang
    mysqli_query($conn, "
        UPDATE tb_barang 
        SET sisa_stok = sisa_stok - $jumlah_keluar 
        WHERE id_barang = '$id_barang'
    ") or die(mysqli_error($conn));

    echo "<script>
        alert('Stok keluar berhasil dicatat');
        window.location='index.php?id_supplier=$id_supplier';
    </script>";
}
?>