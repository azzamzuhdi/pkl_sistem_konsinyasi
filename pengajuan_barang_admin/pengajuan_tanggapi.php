<?php
require_once '../db/conn.php';
session_start();

if ($_SESSION['peran'] != '0') {
    session_destroy();
    header('location:../auth');
    exit();
}

if ((isset($_POST['id']) && isset($_POST['aksi'])) || (isset($_GET['id']) && isset($_GET['aksi']))) {

    // Ambil data dari POST jika ada, kalau tidak ambil dari GET
    $id = $_POST['id'] ?? $_GET['id'];
    $aksi = $_POST['aksi'] ?? $_GET['aksi'];

    // Field tambahan (POST only)
    $kode_barang = $_POST['kode_barang'] ?? null;
    $harga_jual  = $_POST['harga_jual'] ?? null;

    // Tentukan status
    if ($aksi == 'terima') {
        $status = 'Disetujui';
    } elseif ($aksi == 'tolak') {
        $status = 'Ditolak';
    } else {
        $status = 'Menunggu';
    }

    // Ambil data pengajuan
    $query = mysqli_query($conn, "SELECT * FROM tb_pengajuan_barang WHERE id_pengajuan = '$id'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        echo "<script>alert('Data pengajuan tidak ditemukan!');window.location.href='index.php';</script>";
        exit();
    }

    // Update status pengajuan
    mysqli_query($conn, "UPDATE tb_pengajuan_barang SET status_pengajuan='$status' WHERE id_pengajuan='$id'");

    // ================================
    // JIKA DISSETUJUI
    // ================================
    if ($status == 'Disetujui') {

        $id_supplier      = $data['id_supplier'];
        $nama_barang      = $data['nama_barang'];
        $harga_konsinyasi = $data['harga_konsinyasi'];
        $stok_masuk       = $data['stok_masuk'];
        $sisa_stok        = $stok_masuk;
        $tanggal          = date("Y-m-d");

        // Validasi harus isi kode barang & harga jual
        if (!$kode_barang || !$harga_jual) {
            echo "<script>alert('Kode barang dan harga jual wajib diisi!'); window.history.back();</script>";
            exit();
        }

        // Cek apakah kode barang sudah ada untuk supplier ini
        $cek_kode = mysqli_query($conn, 
            "SELECT * FROM tb_barang WHERE kode_barang='$kode_barang' AND id_supplier='$id_supplier'"
        );

        if (mysqli_num_rows($cek_kode) > 0) {
            echo "<script>alert('Gagal! Kode barang sudah dipakai oleh supplier ini.'); window.history.back();</script>";
            exit();
        }

        // Insert barang baru
        $insert_barang = mysqli_query($conn, "
            INSERT INTO tb_barang 
            (id_supplier, kode_barang, nama_barang, harga_konsinyasi, harga_jual, stok_masuk, sisa_stok)
            VALUES 
            ('$id_supplier', '$kode_barang', '$nama_barang', '$harga_konsinyasi', '$harga_jual', '$stok_masuk', '$sisa_stok')
        ");

        if (!$insert_barang) {
            echo "<script>alert('Status disetujui tetapi gagal memasukkan barang: " . mysqli_error($conn) . "');window.location.href='index.php';</script>";
            exit();
        }

        // Ambil id_barang baru
        $id_barang_baru = mysqli_insert_id($conn);

        // CATAT STOK MASUK (WAJIB)
        mysqli_query($conn, "
            INSERT INTO tb_stok_masuk (id_supplier, id_barang, jumlah_masuk, tanggal_masuk)
            VALUES ('$id_supplier', '$id_barang_baru', '$stok_masuk', '$tanggal')
        ");

        echo "<script>
                alert('Pengajuan diterima dan barang berhasil ditambahkan! Stok masuk juga dicatat.');
                window.location.href='index.php';
              </script>";
        exit();
    }

    // ================================
    // JIKA DITOLAK
    // ================================
    echo "<script>
            alert('Status pengajuan diperbarui menjadi $status.');
            window.location.href='index.php';
          </script>";
    exit();

} else {
    echo "<script>alert('Permintaan tidak valid!');window.location.href='index.php';</script>";
    exit();
}
?>
