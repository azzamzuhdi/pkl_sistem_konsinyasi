<?php
include_once '../db/conn.php';

if (isset($_POST['login'])) {
    $inputan_username = trim(mysqli_escape_string($conn, $_POST['username']));
    $inputan_password = sha1(trim(mysqli_escape_string($conn, $_POST['password'])));

    $query_cek = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$inputan_username' AND password = '$inputan_password'") or die(mysqli_error($conn));
    $rv = mysqli_num_rows($query_cek);
    if ($rv > 0) {
        $row = mysqli_fetch_assoc($query_cek);
        $id_pengguna = $row['id_user'];
        $peran = $row['peran'];
        $nama_user = $row['nama_user'];

        if ($peran == '0') {
            session_start();
            $_SESSION['username'] = $inputan_username;
            $_SESSION['password'] = $inputan_password;
            $_SESSION['peran'] = $peran;
            $_SESSION['nama_user'] = $nama_user;
            echo "<script>window.location='../dashboard_admin'</script>";
        } elseif ($peran == 1) {
            session_start();
            $_SESSION['username'] = $inputan_username;
            $_SESSION['password'] = $inputan_password;
            $_SESSION['peran'] = $peran;
            $_SESSION['nama_user'] = $nama_user;
            $_SESSION['id_supplier'] = $row['id_supplier'];
            echo "<script>window.location='../dashboard_supplier'</script>";
        } else {
            echo '<script>alert("User tidak dikenali !!)</script>';
            echo "<script>window.location='../auth'</script>";
        }
    } else {
        echo "<script>alert('Username atau password salah')</script>";
        echo "<script>window.location='../auth'</script>";
    }
}
?>