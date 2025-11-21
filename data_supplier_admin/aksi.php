<?php
require_once '../db/conn.php';



if (isset($_POST['tambah'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $peran = '1';
    $nama_user = $nama_supplier;
    $password_supplier = sha1($nama_supplier);

    $query_cek = mysqli_query($conn, "SELECT nama_supplier, no_hp FROM tb_supplier WHERE nama_supplier = '$nama_supplier' AND no_hp = '$no_hp'") or die(mysqli_error($conn));

    $rv = mysqli_num_rows($query_cek);
    if ($rv > 0) {
        echo "<script>alert('Supplier sudah ada !!');window.location='index.php';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO tb_supplier (nama_supplier, no_hp, alamat) VALUES ('$nama_supplier', '$no_hp', '$alamat')") or die(mysqli_error($conn));

        $id_supplier = mysqli_insert_id($conn);

        mysqli_query($conn, "INSERT INTO tb_user (username, password, peran, id_supplier, nama_user) VALUES ('$nama_supplier', '$password_supplier', '$peran', '$id_supplier', '$nama_user')") or die(mysqli_error($conn));
        echo "<script>alert('Data supplier berhasil ditambahkan');window.location='index.php';</script>";
    }
}

if (isset($_GET['id_supplier'])) {
    $id_supplier = $_GET['id_supplier'];
    mysqli_query($conn, "DELETE FROM tb_supplier WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
    mysqli_query($conn, "DELETE FROM tb_user WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
    echo "<script>alert('Data supplier berhasil dihapus');window.location='index.php';</script>";
}

if (isset($_POST['edit'])) {
    $id_supplier = $_POST['id_supplier2'];
    $nama_supplier = $_POST['nama_supplier2'];
    $no_hp = $_POST['no_hp2'];
    $alamat = $_POST['alamat2'];
    mysqli_query($conn, "UPDATE tb_supplier SET nama_supplier = '$nama_supplier', no_hp = '$no_hp', alamat = '$alamat' WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
    echo "<script>alert('Data supplier berhasil edit');window.location='index.php';</script>";
}


// barang
if (isset($_POST['edit_barang'])) {
    $id_supplier = $_POST['id_supplier2'];
    $id_barang = $_POST['id_barang2'];
    $kode_barang = $_POST['kode_barang2'];
    $nama_barang = $_POST['nama_barang2'];
    $harga_konsinyasi = $_POST['harga_konsinyasi2'];
    $harga_jual = $_POST['harga_jual2'];
    mysqli_query($conn, "UPDATE tb_barang SET kode_barang = '$kode_barang', nama_barang = '$nama_barang', harga_konsinyasi = '$harga_konsinyasi', harga_jual = '$harga_jual' WHERE id_barang = '$id_barang'") or die(mysqli_error($conn));
    echo "<script>alert('Data barang berhasil edit');window.location='barang.php?id_supplier=$id_supplier';</script>";
}

if (isset($_POST['tambah_barang'])) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_konsinyasi = $_POST['harga_konsinyasi'];
    $harga_jual = $_POST['harga_jual'];
    $stok_masuk = $_POST['stok_masuk'];
    $id_supplier = $_POST['id_supplier'];
    $sisa_stok = $stok_masuk;
    // Cek apakah barang dengan kode yang sama sudah ada untuk supplier ini
    $query_cek = mysqli_query($conn, "SELECT * FROM tb_barang WHERE kode_barang='$kode_barang' AND id_supplier = '$id_supplier'") or die(mysqli_error($conn));
    $rv = mysqli_num_rows($query_cek);
    if ($rv > 0) {
        // Jika sudah ada, tambahkan stok dan update harga jika diperlukan
        $existing = mysqli_fetch_assoc($query_cek);
        $existing_stok_masuk = (int)$existing['stok_masuk'];
        $existing_sisa = (int)$existing['sisa_stok'];

        // Tambah stok masuk dan sisa stok
        $new_stok_masuk = $existing_stok_masuk + (int)$stok_masuk;
        $new_sisa = $existing_sisa + (int)$stok_masuk;
        // Update record: hanya tambahkan stok (jangan ubah nama/harga)
        $id_barang_existing = $existing['id_barang'];
        mysqli_query($conn, "UPDATE tb_barang SET stok_masuk='$new_stok_masuk', sisa_stok='$new_sisa' WHERE id_barang = '$id_barang_existing'") or die(mysqli_error($conn));

    // Catat di tabel tb_stok_masuk dengan tanggal sekarang (format DATE)
    $tanggal = date("Y-m-d");
        mysqli_query($conn, "INSERT INTO tb_stok_masuk (id_supplier, id_barang, jumlah_masuk, tanggal_masuk) VALUES ('$id_supplier', '$id_barang_existing', '$stok_masuk', '$tanggal')") or die(mysqli_error($conn));

        echo "<script>alert('Barang sudah ada. Stok berhasil ditambahkan (+$stok_masuk).');window.location='barang.php?id_supplier=$id_supplier';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO tb_barang (id_supplier, kode_barang, nama_barang, harga_konsinyasi, harga_jual, stok_masuk, sisa_stok) VALUES ('$id_supplier', '$kode_barang', '$nama_barang', '$harga_konsinyasi', '$harga_jual', '$stok_masuk', '$sisa_stok')") or die(mysqli_error($conn));
        // dapatkan id_barang yang baru dibuat dan catat stok masuk
        $new_id_barang = mysqli_insert_id($conn);
    $tanggal = date("Y-m-d");
        mysqli_query($conn, "INSERT INTO tb_stok_masuk (id_supplier, id_barang, jumlah_masuk, tanggal_masuk) VALUES ('$id_supplier', '$new_id_barang', '$stok_masuk', '$tanggal')") or die(mysqli_error($conn));

        echo "<script>alert('Data barang berhasil ditambahkan');window.location='barang.php?id_supplier=$id_supplier';</script>";
    }
}

if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];

    // cari id_supplier untuk redirect
    $result = mysqli_query($conn, "SELECT id_supplier FROM tb_barang WHERE id_barang = '$id_barang'");
    $row = mysqli_fetch_assoc($result);
    $id_supplier = $row['id_supplier'];

    // Soft delete (tidak menghapus histori stok)
    mysqli_query($conn, "UPDATE tb_barang SET status='nonaktif' WHERE id_barang='$id_barang'") or die(mysqli_error($conn));

    echo "<script>alert('Barang berhasil dihapus');window.location='barang.php?id_supplier=$id_supplier';</script>";
}


