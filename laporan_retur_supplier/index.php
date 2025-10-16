<?php
require_once '../db/conn.php';
$halaman = 'laporan_retur_supplier';
$id_supplier = $_SESSION['id_supplier'];
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
        <title>Laporan Retur | Sistem Konsinyasi</title>

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
                                <h1>Laporan Retur</h1>
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

                                    <?php
                                    $query_retur = mysqli_query($conn, "
     SELECT 
        r.id_retur,
        b.nama_barang,
        b.harga_konsinyasi,
        r.jumlah_retur,
        r.tanggal_retur,
        r.alasan,
        r.keterangan,
        r.status_retur
    FROM tb_retur_barang r
    JOIN tb_barang b ON r.id_barang = b.id_barang
    WHERE r.id_supplier = '$id_supplier'
    AND r.status_retur = 'Diterima'
    ORDER BY r.tanggal_retur DESC
") or die(mysqli_error($conn));
                                    ?>
                                    <?php if ($id_supplier) { ?>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah Retur</th>
                                                        <th>Harga</th>
                                                        <th>Tanggal Retur</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 1;
                                                    $grand_total = 0;
                                                    if (mysqli_num_rows($query_retur) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query_retur)) {
                                                            $grand_total += $row['jumlah_retur'] * $row['harga_konsinyasi'];
                                                            ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= $row['nama_barang'] ?></td>
                                                                <td><?= $row['jumlah_retur'] ?> pcs</td>
                                                                <td>Rp. <?= number_format($row['harga_konsinyasi'], 0, ',', '.') ?>
                                                                </td>
                                                                <td><?= $row['tanggal_retur'] ?></td>
                                                                <td><?= $row['alasan'] ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='8'>Tidak ada barang untuk supplier ini</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="5" class="text-right">Total Retur</th>
                                                        <th>Rp. <?= number_format($grand_total, 0, ',', '.') ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- /.card-body -->
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
            <script>
                $('#modal-stok').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var id_barang = button.data('id_barang');
                    var nama_barang = button.data('nama_barang');
                    var id_supplier = button.data('id_supplier');

                    $('#id_barang_modal').val(id_barang);
                    $('#nama_barang_modal').val(nama_barang);
                    $('#id_supplier_modal').val(id_supplier);
                });
            </script>
    </body>

    </html>
    <?php
}
?>