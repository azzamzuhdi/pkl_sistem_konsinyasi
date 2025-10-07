<?php
require_once '../db/conn.php';
$halaman = 'laporan_rusak_admin';
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
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Laporan Barang Rusak</h1>
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
                                        <!-- <div class="card mb-3"> -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card-body">
                                                    <form method="GET" action="">
                                                        <?php
                                                        $query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier") or die(mysqli_error($conn));
                                                        $id_supplier = isset($_GET['id_supplier']) ? mysqli_real_escape_string($conn, $_GET['id_supplier']) : '';
                                                        $barang = [];
                                                        if ($id_supplier) {
                                                            $barang = mysqli_query($conn, "SELECT * FROM tb_barang WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
                                                        }
                                                        ?>
                                                        <label for="id_supplier">Pilih Supplier:</label>
                                                        <select name="id_supplier" id="id_supplier" class="form-control"
                                                            required>
                                                            <option value="">-- Pilih Supplier --</option>
                                                            <?php while ($row = mysqli_fetch_assoc($query_supplier)) { ?>
                                                                <option value="<?= $row['id_supplier'] ?>"
                                                                    <?= ($id_supplier == $row['id_supplier']) ? 'selected' : '' ?>>
                                                                    <?= $row['nama_supplier'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <button type="submit" class="btn btn-primary mt-2">Tampilkan
                                                            Laporan
                                                            Barang Rusak</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card-body">
                                                    <?php
                                                    $query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
                                                    $row_supplier = mysqli_fetch_assoc($query_supplier);
                                                    ?>
                                                    <?php if ($id_supplier) { ?>
                                                        <table class="table table-borderless">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Supplier</td>
                                                                    <td>:</td>
                                                                    <td><?= $row_supplier['nama_supplier'] ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Alamat</td>
                                                                    <td>:</td>
                                                                    <td><?= $row_supplier['alamat'] ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>No. Hp</td>
                                                                    <td>:</td>
                                                                    <td><?= $row_supplier['no_hp'] ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        $query_rusak = mysqli_query($conn, "
      SELECT 
    sk.id_keluar,
    s.id_supplier,
    sk.tanggal,
    sk.id_barang,
    b.nama_barang,
    sk.jumlah,
    sk.jenis_keluar,
    sk.keterangan,
    (sk.jumlah * b.harga_konsinyasi) AS total
FROM tb_stok_keluar sk
JOIN tb_barang b ON sk.id_barang = b.id_barang
JOIN tb_supplier s ON b.id_supplier = s.id_supplier
WHERE sk.jenis_keluar IN ('Rusak', 'Kadaluarsa') AND s.id_supplier = '$id_supplier'
ORDER BY sk.tanggal DESC;
    ");
                                        ?>
                                        <?php if ($id_supplier) { ?>
                                            <div class="card">
                                                <div class="card-body">
                                                    <table class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Tanggal</th>
                                                                <th>Nama Barang</th>
                                                                <th>Jumlah Rusak</th>
                                                                <th>Jenis Kerusakan</th>
                                                                <th>Keterangan</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            $grand_total = 0;
                                                            if (mysqli_num_rows($query_rusak) > 0) {
                                                                while ($row = mysqli_fetch_assoc($query_rusak)) {
                                                                    $grand_total += $row['total'];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no++ ?></td>
                                                                        <td><?= $row['tanggal'] ?></td>
                                                                        <td><?= $row['nama_barang'] ?></td>
                                                                        <td><?= $row['jumlah'] ?> pcs</td>
                                                                        <td><?= $row['jenis_keluar'] ?></td>
                                                                        <td><?= $row['keterangan'] ?></td>
                                                                        <td>Rp. <?= number_format($row['total'], 0, ',', '.') ?></td>

                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='8'>Tidak ada barang untuk supplier ini</td></tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot></tfoot>
                                                            <tr>
                                                                <th colspan="6" class="text-right">Total Kerugian</th>
                                                                <th>Rp. <?= number_format($grand_total, 0, ',', '.') ?></th>
                                                            </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!-- /.card-body -->
                                        <!-- </div> -->
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