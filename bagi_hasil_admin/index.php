<?php
require_once '../db/conn.php';
$halaman = 'bagi_hasil_admin';
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
        <title>Bagi Hasil | Sistem Konsinyasi</title>

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
                                <h1>Bagi Hasil</h1>
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
                                                            Data
                                                        </button>
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
                                        $query_bagi = mysqli_query($conn, "
                                                            SELECT 
    s.id_supplier,
    sk.tanggal,
    sk.id_barang,
    b.nama_barang,
    sk.jumlah,
    b.harga_konsinyasi,
    b.harga_jual,
    sk.jenis_keluar,
    sk.keterangan,
    (sk.jumlah * b.harga_jual) AS total_penjualan,
    (sk.jumlah * b.harga_konsinyasi) AS hak_supplier,
    (sk.jumlah * b.harga_jual) - (sk.jumlah * b.harga_konsinyasi) AS keuntungan_toko
FROM tb_stok_keluar sk
JOIN tb_barang b ON sk.id_barang = b.id_barang
JOIN tb_supplier s ON b.id_supplier = s.id_supplier
WHERE sk.jenis_keluar = 'Terjual' AND s.id_supplier = '$id_supplier'
ORDER BY sk.tanggal DESC;
    ");
                                        ?>
                                        <?php if ($id_supplier) { ?>
                                            <div class="card">
                                                <div class="card-body">
                                                    <table id="example1" class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Tanggal</th>
                                                                <th>Nama Barang</th>
                                                                <th>Jumlah Terjual</th>
                                                                <th>Harga Konsinyasi</th>
                                                                <th>Harga Jual</th>
                                                                <th>Total Penjualan</th>
                                                                <th>Hak Supplier</th>
                                                                <th>Keuntungan Toko</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            $total_penjualan = 0;
                                                            $hak_supplier = 0;
                                                            $keuntungan_toko = 0;
                                                            if (mysqli_num_rows($query_bagi) > 0) {
                                                                while ($row = mysqli_fetch_assoc($query_bagi)) {
                                                                    $total_penjualan += $row['total_penjualan'];
                                                                    $hak_supplier += $row['hak_supplier'];
                                                                    $keuntungan_toko += $row['keuntungan_toko'];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no++ ?></td>
                                                                        <td><?= $row['tanggal'] ?></td>
                                                                        <td><?= $row['nama_barang'] ?></td>
                                                                        <td><?= $row['jumlah'] ?> pcs</td>
                                                                        <td>Rp. <?= number_format($row['harga_konsinyasi'], 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($row['total_penjualan'], 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($row['hak_supplier'], 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($row['keuntungan_toko'], 0, ',', '.') ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='9'>Tidak ada barang untuk supplier ini</td></tr>";
                                                            }
                                                            
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="6" class="text-right">Total</th>
                                                                <th>Rp. <?= number_format($total_penjualan, 0, ',', '.') ?></th>
                                                                <th>Rp. <?= number_format($hak_supplier, 0, ',', '.') ?></th>
                                                                <th>Rp. <?= number_format($keuntungan_toko, 0, ',', '.') ?></th>
                                                            </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
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