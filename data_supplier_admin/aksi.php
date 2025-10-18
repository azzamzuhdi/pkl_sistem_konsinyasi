<?php
require_once '../db/conn.php';



if (isset($_POST['tambah'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $no_hp = $_POST['no_hp'];
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $alamat = $_POST['alamat'];
    $peran = '1';
    $nama_user = $nama_supplier;
    $password_supplier = sha1($nama_supplier);

    $query_cek = mysqli_query($conn, "SELECT nama_supplier, no_hp FROM tb_supplier WHERE nama_supplier = '$nama_supplier' AND no_hp = '$no_hp'") or die(mysqli_error($conn));

    $rv = mysqli_num_rows($query_cek);
    if ($rv > 0) {
        echo "<script>alert('Supplier sudah ada !!');window.location='index.php';</script>";
    } else {
        // pastikan kolom email ada
        $col_check = mysqli_query($conn, "SHOW COLUMNS FROM tb_supplier LIKE 'email'");
        if (mysqli_num_rows($col_check) == 0) {
            mysqli_query($conn, "ALTER TABLE tb_supplier ADD COLUMN email VARCHAR(255) NULL") or die(mysqli_error($conn));
        }

        mysqli_query($conn, "INSERT INTO tb_supplier (nama_supplier, no_hp, alamat, email) VALUES ('$nama_supplier', '$no_hp', '$alamat', '$email')") or die(mysqli_error($conn));

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
    $email = isset($_POST['email2']) ? $_POST['email2'] : '';
    $alamat = $_POST['alamat2'];
    // pastikan kolom email ada
    $col_check = mysqli_query($conn, "SHOW COLUMNS FROM tb_supplier LIKE 'email'");
    if (mysqli_num_rows($col_check) == 0) {
        mysqli_query($conn, "ALTER TABLE tb_supplier ADD COLUMN email VARCHAR(255) NULL") or die(mysqli_error($conn));
    }

    mysqli_query($conn, "UPDATE tb_supplier SET nama_supplier = '$nama_supplier', no_hp = '$no_hp', alamat = '$alamat', email = '$email' WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
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

    $query_cek = mysqli_query($conn, "SELECT kode_barang, id_supplier FROM tb_barang WHERE kode_barang='$kode_barang' AND id_supplier = '$id_supplier'") or die(mysqli_error($conn));
    $rv = mysqli_num_rows($query_cek);
    if ($rv > 0) {
        echo "<script>alert('Barang sudah ada !!');window.location='barang.php?id_supplier=$id_supplier';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO tb_barang (id_supplier, kode_barang, nama_barang, harga_konsinyasi, harga_jual, stok_masuk, sisa_stok) VALUES ('$id_supplier', '$kode_barang', '$nama_barang', '$harga_konsinyasi', '$harga_jual', '$stok_masuk', '$sisa_stok')") or die(mysqli_error($conn));
        echo "<script>alert('Data barang berhasil ditambahkan');window.location='barang.php?id_supplier=$id_supplier';</script>";
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
    echo "<script>alert('Data barang berhasil dihapus');window.location='barang.php?id_supplier=$id_supplier';</script>";
}

