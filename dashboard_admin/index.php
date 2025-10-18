<?php
session_start();

$halaman = 'dashboard_admin';
if ($_SESSION['peran'] != '0') {
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
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Dashboard Admin</h1>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <?php
                        require_once '../db/conn.php';

                        // Summary numbers
                        $q_suppliers = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM tb_supplier") or die(mysqli_error($conn));
                        $suppliers = mysqli_fetch_assoc($q_suppliers)['cnt'] ?? 0;

                        $q_products = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM tb_barang") or die(mysqli_error($conn));
                        $products = mysqli_fetch_assoc($q_products)['cnt'] ?? 0;

                        // Sales this month (sum of total_pembayaran where month)
                        $month_start = date('Y-m-01');
                        $q_sales_month = mysqli_query($conn, "SELECT IFNULL(SUM(total_pembayaran),0) AS total FROM tb_pembayaran_supplier WHERE tanggal_pembayaran >= '$month_start'") or die(mysqli_error($conn));
                        $sales_month = mysqli_fetch_assoc($q_sales_month)['total'] ?? 0;

                        // Pending payments count
                        $q_pending = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM tb_stok_keluar WHERE status_pembayaran = 'Belum Dibayar' AND jenis_keluar = 'Terjual'") or die(mysqli_error($conn));
                        $pending = mysqli_fetch_assoc($q_pending)['cnt'] ?? 0;
                        ?>

                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><?= $suppliers ?></h3>
                                        <p>Total Supplier</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-store"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $products ?></h3>
                                        <p>Produk</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>Rp. <?= number_format($sales_month, 0, ',', '.') ?></h3>
                                        <p>Penjualan Bulan Ini</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?= $pending ?></h3>
                                        <p>Tagihan Belum Dibayar</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Penjualan Terbaru</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Barang</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $q_recent_sales = mysqli_query($conn, "SELECT sk.tanggal, b.nama_barang, sk.jumlah, (sk.jumlah * b.harga_konsinyasi) AS total
                                                    FROM tb_stok_keluar sk
                                                    JOIN tb_barang b ON sk.id_barang = b.id_barang
                                                    WHERE sk.jenis_keluar = 'Terjual'
                                                    ORDER BY sk.tanggal DESC LIMIT 8") or die(mysqli_error($conn));
                                                $i = 1;
                                                while ($r = mysqli_fetch_assoc($q_recent_sales)) {
                                                    echo '<tr>';
                                                    echo '<td>' . $i++ . '</td>';
                                                    echo '<td>' . $r['tanggal'] . '</td>';
                                                    echo '<td>' . $r['nama_barang'] . '</td>';
                                                    echo '<td>' . $r['jumlah'] . '</td>';
                                                    echo '<td>Rp ' . number_format($r['total'], 0, ',', '.') . '</td>';
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Pembayaran Terbaru</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal Bayar</th>
                                                    <th>Supplier</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $q_recent_pay = mysqli_query($conn, "SELECT p.tanggal_pembayaran, s.nama_supplier, p.total_pembayaran
                                                    FROM tb_pembayaran_supplier p
                                                    JOIN tb_supplier s ON p.id_supplier = s.id_supplier
                                                    ORDER BY p.tanggal_pembayaran DESC LIMIT 8") or die(mysqli_error($conn));
                                                $j = 1;
                                                while ($rp = mysqli_fetch_assoc($q_recent_pay)) {
                                                    echo '<tr>';
                                                    echo '<td>' . $j++ . '</td>';
                                                    echo '<td>' . $rp['tanggal_pembayaran'] . '</td>';
                                                    echo '<td>' . $rp['nama_supplier'] . '</td>';
                                                    echo '<td>Rp ' . number_format($rp['total_pembayaran'], 0, ',', '.') . '</td>';
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <!-- /.content -->
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