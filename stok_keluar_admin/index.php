<?php
require_once '../db/conn.php';
$halaman = 'stok_keluar_admin';
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
        <title>Stok Keluar | Sistem Konsinyasi</title>

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
                                <h1>Stok Keluar</h1>
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
                                                                Barang</button>
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

                                        <?php if ($id_supplier) { ?>
                                            <div class="card">
                                                <div class="card-body">
                                                    <table id="example1" class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Kode Barang</th>
                                                                <th>Nama Barang</th>
                                                                <th>Harga Konsinyasi</th>
                                                                <th>Harga Jual</th>
                                                                <th>Stok Masuk</th>
                                                                <th>Sisa Stok</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            if (mysqli_num_rows($barang) > 0) {
                                                                while ($row = mysqli_fetch_assoc($barang)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no++ ?></td>
                                                                        <td><?= $row['kode_barang'] ?></td>
                                                                        <td><?= $row['nama_barang'] ?></td>
                                                                        <td>Rp. <?= number_format($row['harga_konsinyasi'], 0, ',', '.') ?>
                                                                        </td>
                                                                        <td>Rp. <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                                                                        <td><?= $row['stok_masuk'] ?></td>
                                                                        <td> <?php
                                                                        if ($row['sisa_stok'] <= 0 ) {
                                                                            ?>
                                                                            <span class="badge badge-danger">Habis</span>   
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                             <?= $row['sisa_stok'] ?>
                                                                            <?php
                                                                        }
                                                                        ?></td>
                                                                        <td><?php
                                                                        if ($row['sisa_stok'] <= 0 ) {
                                                                            ?>
                                                                            <span class="badge badge-danger">Habis</span>   
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <button type="button" class="btn btn-success btn-sm"
                                                                                data-toggle="modal" data-target="#modal-stok-keluar"
                                                                                data-id_barang="<?= $row['id_barang'] ?>"
                                                                                data-nama_barang="<?= $row['nama_barang'] ?>"
                                                                                data-id_supplier="<?= $row['id_supplier'] ?>">
                                                                                Kurangi Stok
                                                                            </button>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='8'>Tidak ada barang untuk supplier ini</td></tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php } ?>
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



            <!-- Modal kurangi Stok -->
            <!-- Modal Stok Keluar -->
            <div class="modal fade" id="modal-stok-keluar">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Catat Stok Keluar</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="aksi.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id_supplier" value="<?= $_GET['id_supplier'] ?>">
                                <div class="form-group">
                                    <label for="id_barang">Pilih Barang</label>
                                    <select name="id_barang" id="id_barang" class="form-control" required>
                                        <option value="">-- Pilih Barang --</option>
                                        <?php
                                        $barang = mysqli_query($conn, "SELECT * FROM tb_barang WHERE id_supplier = '$id_supplier'");
                                        while ($row = mysqli_fetch_assoc($barang)) {
                                            echo "<option value='" . $row['id_barang'] . "'>" . $row['nama_barang'] . " (Stok: " . $row['sisa_stok'] . ")</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_keluar">Jumlah</label>
                                    <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control"
                                        min="1" required>
                                </div>

                                <div class="form-group">
                                    <label for="jenis_keluar">Jenis Keluar</label>
                                    <select name="jenis_keluar" id="jenis_keluar" class="form-control" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Rusak">Rusak</option>
                                        <option value="Kadaluarsa">Kadaluarsa</option>
                                        <option value="Terjual">Terjual</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control"  value="<?= date('Y-m-d') ?>" 
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="2"
                                        placeholder="Contoh: kemasan bocor / expired 09-2025" required></textarea>
                                </div>
                                 <input type="hidden" name="status_pembayaran">
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                <button type="submit" name="tambah_stok_keluar" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


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