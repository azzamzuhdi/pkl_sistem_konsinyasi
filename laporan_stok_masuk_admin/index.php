<?php
require_once '../db/conn.php';
$halaman = 'laporan_stok_masuk_admin';
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
        <title>Laporan Stok Masuk | Sistem Konsinyasi</title>

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
                                <h1>Laporan Stok Masuk</h1>
                            </div>
                            <div class="col-sm-6">
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <!-- /.card -->

                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <form method="GET" action="">
                                                    <?php
                                                    $query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier") or die(mysqli_error($conn));
                                                    $id_supplier = isset($_GET['id_supplier']) ? mysqli_real_escape_string($conn, $_GET['id_supplier']) : '';
                                                    $filter_tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($conn, $_GET['tanggal']) : '';
                                                    ?>
                                                    <label for="id_supplier">Pilih Supplier:</label>
                                                    <select name="id_supplier" id="id_supplier" class="form-control" required>
                                                        <option value="">-- Pilih Supplier --</option>
                                                        <?php while ($row = mysqli_fetch_assoc($query_supplier)) { ?>
                                                            <option value="<?= $row['id_supplier'] ?>" <?= ($id_supplier == $row['id_supplier']) ? 'selected' : '' ?>>
                                                                <?= $row['nama_supplier'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>

                                                    <label for="tanggal" class="mt-2">Filter Tanggal (opsional):</label>
                                                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= $filter_tanggal ?>">

                                                    <button type="submit" class="btn btn-primary mt-2">Tampilkan Laporan Stok Masuk</button>
                                                    <?php if ($id_supplier) { ?>
                                                        <a href="export_pdf.php?id_supplier=<?= $id_supplier ?><?= $filter_tanggal ? '&tanggal='.$filter_tanggal : '' ?>" target="_blank" class="btn btn-danger mt-2 ml-2">Cetak PDF</a>
                                                    <?php } ?>
                                                </form>
                                            </div>
                                        </div>

                                        <?php
                                        if ($id_supplier) {
                                            // Jika filter tanggal diberikan, batasi query per hari
                                            if (!empty($filter_tanggal)) {
                                                $query = mysqli_query($conn, "SELECT sm.id_stok_masuk, sm.tanggal_masuk, sm.jumlah_masuk, b.kode_barang, b.nama_barang
                                                    FROM tb_stok_masuk sm
                                                    JOIN tb_barang b ON sm.id_barang = b.id_barang
                                                    WHERE sm.id_supplier = '$id_supplier' AND sm.tanggal_masuk = '$filter_tanggal'
                                                    ORDER BY sm.tanggal_masuk DESC") or die(mysqli_error($conn));
                                            } else {
                                                $query = mysqli_query($conn, "SELECT sm.id_stok_masuk, sm.tanggal_masuk, sm.jumlah_masuk, b.kode_barang, b.nama_barang
                                                    FROM tb_stok_masuk sm
                                                    JOIN tb_barang b ON sm.id_barang = b.id_barang
                                                    WHERE sm.id_supplier = '$id_supplier'
                                                    ORDER BY sm.tanggal_masuk DESC") or die(mysqli_error($conn));
                                            }
                                        }
                                        ?>

                                        <?php if ($id_supplier) { ?>
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <table class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Tanggal Masuk</th>
                                                                <th>Kode Barang</th>
                                                                <th>Nama Barang</th>
                                                                <th>Jumlah Masuk</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            if (isset($query) && mysqli_num_rows($query) > 0) {
                                                                while ($row = mysqli_fetch_assoc($query)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no++ ?></td>
                                                                        <td><?= $row['tanggal_masuk'] ?></td>
                                                                        <td><?= $row['kode_barang'] ?></td>
                                                                        <td><?= $row['nama_barang'] ?></td>
                                                                        <td><?= $row['jumlah_masuk'] ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='5'>Tidak ada data stok masuk untuk supplier ini</td></tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>



            <!-- ./wrapper -->

            <?php
            include '../layout/footer.php';
            ?>
            <!-- REQUIRED SCRIPTS -->
            <?php
            include '../layout/script.php';
            ?>
    </body>

    </html>
    <?php
}
?>
