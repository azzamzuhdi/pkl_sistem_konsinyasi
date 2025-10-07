<?php
session_start();
require '../db/conn.php';

$halaman = 'dashboard_supplier';
if ($_SESSION['peran'] != '1') {
    session_destroy();
    header('location:../auth');
} else {


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AdminLTE 3 | Dashboard 2</title>

        <?php
        include '../layout/style.php';
        ?>
    </head>

    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">

            <!-- Navbar -->
            <?php
            include '../layout/navbar.php';
            ?>
            <!-- /.navbar -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0" style="font-size: 20px; font-weight: bold">Dashboard</h1>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>

                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <?php
                                        $id_supplier = $_SESSION['id_supplier'];
                                        $query_stok_masuk = mysqli_query($conn, "SELECT SUM(stok_masuk) AS total_stok_masuk FROM tb_barang WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
                                        $total_stok_masuk = mysqli_fetch_assoc($query_stok_masuk)['total_stok_masuk'] ?? 0;
                                        ?>
                                        <h3><?= $total_stok_masuk; ?></h3>
                                        <p>Total Barang Masuk</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <?php
                                        $id_supplier = $_SESSION['id_supplier'];
                                        $query_terjual = mysqli_query($conn, "SELECT SUM(jumlah) AS total_terjual, jenis_keluar FROM tb_stok_keluar WHERE id_supplier = '$id_supplier' AND jenis_keluar = 'Terjual'") or die(mysqli_error($conn));
                                        $total_terjual = mysqli_fetch_assoc($query_terjual)['total_terjual'] ?? 0;
                                        ?>
                                        <h3><?= $total_terjual; ?></h3>
                                        <p>Barang Terjual</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <?php
                                        $id_supplier = $_SESSION['id_supplier'];
                                        $query_rusak = mysqli_query($conn, "SELECT SUM(jumlah) AS total_rusak, jenis_keluar FROM tb_stok_keluar WHERE id_supplier = '$id_supplier' AND jenis_keluar = 'Rusak'") or die(mysqli_error($conn));
                                        $total_rusak = mysqli_fetch_assoc($query_rusak)['total_rusak'] ?? 0;
                                        ?>
                                        <h3><?= $total_rusak; ?></h3>
                                        <p>Barang Rusak</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <?php
                                        $id_supplier = $_SESSION['id_supplier'];
                                        $query_kadaluarsa = mysqli_query($conn, "SELECT SUM(jumlah) AS total_kadaluarsa, jenis_keluar FROM tb_stok_keluar WHERE id_supplier = '$id_supplier' AND jenis_keluar = 'Kadaluarsa'") or die(mysqli_error($conn));
                                        $total_kadaluarsa = mysqli_fetch_assoc($query_kadaluarsa)['total_kadaluarsa'] ?? 0;
                                        ?>
                                        <h3><?= $total_kadaluarsa; ?></h3>
                                        <p>Barang Kadaluarsa</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <?php
                                        $id_supplier = $_SESSION['id_supplier'];
                                        $query_sisa = mysqli_query($conn, "SELECT SUM(sisa_stok) AS jml_sisa_stok FROM tb_barang WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
                                        $jml_sisa_stok = mysqli_fetch_assoc($query_sisa)['jml_sisa_stok'] ?? 0;
                                        ?>
                                        <h3><?= $jml_sisa_stok; ?></h3>
                                        <p>Stok Sisa</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <?php
                                        $id_supplier = $_SESSION['id_supplier'];
                                        $query_pendapatan = mysqli_query($conn, "SELECT 
    s.id_supplier,
    b.harga_konsinyasi,
    b.harga_jual,
    sk.jumlah,
    sk.jenis_keluar,
    (sk.jumlah * b.harga_konsinyasi) AS total_pendapatan
FROM tb_stok_keluar sk
JOIN tb_barang b 
JOIN tb_supplier s ON b.id_supplier = s.id_supplier
WHERE sk.jenis_keluar = 'Terjual' AND s.id_supplier = '$id_supplier'
    ") or die(mysqli_error($conn));
                                        $pendapatan = mysqli_fetch_assoc($query_pendapatan)['total_pendapatan'] ?? 0;
                                        ?>
                                        <h3><?= $pendapatan; ?></h3>
                                        <p>Total Pendapatan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            <?php
            include '../layout/footer.php';
            ?>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->
        <?php
        include '../layout/script.php';
        ?>
    </body>

    </html>
    <?php
}
?>