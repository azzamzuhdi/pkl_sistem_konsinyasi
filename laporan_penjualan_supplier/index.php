<?php
require_once '../db/conn.php';
$halaman = 'laporan_penjualan_supplier';
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
        <title>Laporan Penjualan | Sistem Konsinyasi</title>

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
                                <h1>Laporan Penjualan</h1>
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
                                    $query_terjual = mysqli_query($conn, "
      SELECT 
    sk.id_keluar,
    s.id_supplier,
    sk.tanggal,
    sk.id_barang,
    b.nama_barang,
    b.kode_barang,
    b.harga_jual,
    sk.jumlah,
    sk.jenis_keluar,
    sk.status_pembayaran,
    (sk.jumlah * b.harga_jual) AS total
FROM tb_stok_keluar sk
JOIN tb_barang b ON sk.id_barang = b.id_barang
JOIN tb_supplier s ON b.id_supplier = s.id_supplier
WHERE sk.jenis_keluar = 'Terjual' AND s.id_supplier = '$id_supplier'
ORDER BY sk.tanggal DESC;
    ");
                                    ?>
                                    <?php if ($id_supplier) { ?>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Kode Barang</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah Terjual</th>
                                                        <th>Status</th>
                                                        <th>Harga per Pcs</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 1;
                                                    $grand_total = 0;
                                                    if (mysqli_num_rows($query_terjual) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query_terjual)) {
                                                            $grand_total += $row['total'];
                                                            ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= $row['tanggal'] ?></td>
                                                                <td><?= $row['kode_barang'] ?></td>
                                                                <td><?= $row['nama_barang'] ?></td>
                                                                <td><?= $row['jumlah'] ?> pcs</td>
                                                                <td>
                                                                    <?php if ($row['status_pembayaran'] == 'Sudah Dibayar') { ?>
                                                                        <span class="badge bg-success">Sudah Dibayar</span>
                                                                    <?php } else { ?>
                                                                        <span class="badge bg-danger">Belum Dibayar</span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>Rp. <?= number_format($row['harga_jual'], 0, ',', '.') ?>
                                                                </td>
                                                                <td>Rp. <?= number_format($row['total'], 0, ',', '.') ?></td>
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
                                                        <th colspan="7" class="text-right">Total Penjualan</th>
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