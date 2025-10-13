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
        <title>Dashboard | Sistem Konsinyasi</title>

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

                            <!-- Total Barang Masuk -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
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
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Barang Terjual -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <?php
                                        $query_terjual = mysqli_query($conn, "SELECT SUM(jumlah) AS total_terjual FROM tb_stok_keluar WHERE id_supplier = '$id_supplier' AND jenis_keluar = 'Terjual'") or die(mysqli_error($conn));
                                        $total_terjual = mysqli_fetch_assoc($query_terjual)['total_terjual'] ?? 0;
                                        ?>
                                        <h3><?= $total_terjual; ?></h3>
                                        <p>Barang Terjual</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Barang Rusak -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <?php
                                        $query_rusak = mysqli_query($conn, "SELECT SUM(jumlah) AS total_rusak FROM tb_stok_keluar WHERE id_supplier = '$id_supplier' AND jenis_keluar = 'Rusak'") or die(mysqli_error($conn));
                                        $total_rusak = mysqli_fetch_assoc($query_rusak)['total_rusak'] ?? 0;
                                        ?>
                                        <h3><?= $total_rusak; ?></h3>
                                        <p>Barang Rusak</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Barang Kadaluarsa -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <?php
                                        $query_kadaluarsa = mysqli_query($conn, "SELECT SUM(jumlah) AS total_kadaluarsa FROM tb_stok_keluar WHERE id_supplier = '$id_supplier' AND jenis_keluar = 'Kadaluarsa'") or die(mysqli_error($conn));
                                        $total_kadaluarsa = mysqli_fetch_assoc($query_kadaluarsa)['total_kadaluarsa'] ?? 0;
                                        ?>
                                        <h3><?= $total_kadaluarsa; ?></h3>
                                        <p>Barang Kadaluarsa</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Sisa Stok -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <?php
                                        $query_sisa = mysqli_query($conn, "SELECT SUM(sisa_stok) AS jml_sisa_stok FROM tb_barang WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
                                        $jml_sisa_stok = mysqli_fetch_assoc($query_sisa)['jml_sisa_stok'] ?? 0;
                                        ?>
                                        <h3><?= $jml_sisa_stok; ?></h3>
                                        <p>Stok Sisa</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Pendapatan -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <?php
                                        $query_pendapatan = mysqli_query($conn, "
                    SELECT SUM(sk.jumlah * b.harga_konsinyasi) AS total_pendapatan
                    FROM tb_stok_keluar sk
                    JOIN tb_barang b ON sk.id_barang = b.id_barang
                    WHERE sk.jenis_keluar = 'Terjual' AND b.id_supplier = '$id_supplier'
                ") or die(mysqli_error($conn));

                                        $pendapatan = mysqli_fetch_assoc($query_pendapatan)['total_pendapatan'] ?? 0;
                                        ?>
                                        <h3>Rp. <?= number_format($pendapatan, 0, ',', '.') ?></h3>
                                        <p>Total Pendapatan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Sudah Dibayar -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <?php
                                        $query_lunas = mysqli_query($conn, "
                    SELECT SUM(sk.jumlah * b.harga_konsinyasi) AS total_lunas
                    FROM tb_stok_keluar sk
                    JOIN tb_barang b ON sk.id_barang = b.id_barang
                    WHERE sk.status_pembayaran = 'Sudah Dibayar' AND b.id_supplier = '$id_supplier'
                ") or die(mysqli_error($conn));

                                        $lunas = mysqli_fetch_assoc($query_lunas)['total_lunas'] ?? 0;
                                        ?>
                                        <h3>Rp <?= number_format($lunas, 0, ',', '.'); ?></h3>
                                        <p>Total Lunas</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Belum  Dibayar -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <?php
                                        $query_blm_lunas = mysqli_query($conn, "
                    SELECT SUM(sk.jumlah * b.harga_konsinyasi) AS total_belum_lunas
                    FROM tb_stok_keluar sk
                    JOIN tb_barang b ON sk.id_barang = b.id_barang
                    WHERE sk.status_pembayaran = 'Belum Dibayar' AND b.id_supplier = '$id_supplier'
                ") or die(mysqli_error($conn));

                                        $blm_lunas = mysqli_fetch_assoc($query_blm_lunas)['total_belum_lunas'] ?? 0;
                                        ?>
                                        <h3>Rp <?= number_format($blm_lunas, 0, ',', '.'); ?></h3>
                                        <p>Belum Dibayar</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check-circle"></i>
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