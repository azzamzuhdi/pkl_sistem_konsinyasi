<?php
require_once '../db/conn.php';
$halaman = 'data_retur_supplier';
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
        <title>Retur Barang | Sistem Konsinyasi</title>

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
                <section class="content-header">
                    <h1>Retur Barang</h1>
                </section>

                <section class="content">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Daftar Barang Rusak / Kadaluarsa</h3>
                        </div>
                        <div class="card-body">
                            <p></p>
                            <?php
                            $id_supplier = $_SESSION['id_supplier'];
                            $query = mysqli_query($conn, "
                            SELECT sk.*, b.nama_barang
                            FROM tb_stok_keluar sk
                            JOIN tb_barang b ON sk.id_barang = b.id_barang
                            WHERE sk.id_supplier = '$id_supplier'
                            AND sk.jenis_keluar IN ('Rusak', 'Kadaluarsa')
                            ") or die(mysqli_error($conn));
                            ?>
                            <table id="example1" class="table table-bordered table-striped align-middle text-center">
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    while ($row = mysqli_fetch_assoc($query)) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['nama_barang']; ?></td>
                                            <td><?= $row['tanggal']; ?></td>
                                            <td><?= $row['jumlah']; ?></td>
                                            <td><?= $row['jenis_keluar']; ?></td>
                                            <td>
                                                <button class="btn btn-info" data-toggle="modal"
                                                    data-target="#modal-retur<?= $row['id_barang']; ?>">
                                                    <i class="fas fa-plus"></i> Ajukan Retur
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Modal Retur -->
                                        <div class="modal fade" id="modal-retur<?= $row['id_barang']; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h4 class="modal-title">Ajukan Retur Barang</h4>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="proses_retur.php" method="POST">
                                                            <input type="hidden" name="id_supplier" value="<?= $id_supplier ?>">
                                                            <div class="form-group">
                                                                <input type="hidden" name="id_barang"
                                                                    value="<?= $row['id_barang']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Jumlah Retur</label>
                                                                <input type="number" name="jumlah_retur" class="form-control"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Alasan Retur</label>
                                                                <select name="alasan" class="form-control" required>
                                                                    <option value="Rusak">Rusak</option>
                                                                    <option value="Kadaluarsa">Kadaluarsa</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Keterangan</label>
                                                                <textarea name="keterangan" class="form-control"></textarea>
                                                            </div>
                                                            <button type="submit" name="ajukan_retur"
                                                                class="btn btn-primary">Kirim
                                                                Retur</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </section>
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