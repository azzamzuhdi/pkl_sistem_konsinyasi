<?php
require_once '../db/conn.php';
$halaman = 'data_supplier_admin';
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
        <title>Data Supplier | Sistem Konsinyasi</title>

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
                                <h1>Data Supplier</h1>
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
                                        <button type="button" class="btn btn-info btn-md" data-toggle="modal"
                                            data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Data</button>
                                        <p></p>
                                        <table id="example1"
                                            class="table table-bordered table-striped align-middle text-center">
                                            <thead>
                                                <tr>
                                                    <th style="width : 5%">No</th>
                                                    <th>Nama Supplier</th>
                                                    <th>No Hp</th>
                                                    <th>Alamat</th>
                                                    <th style="width : 25%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier") or die(mysqli_error($conn));
                                                $rv = mysqli_num_rows($query_supplier);
                                                $no = 1;
                                                if ($rv > 0) {
                                                    while ($row = mysqli_fetch_array($query_supplier)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td><?= $row['nama_supplier'] ?></td>
                                                            <td><?= $row['no_hp'] ?></td>
                                                            <td><?= $row['alamat'] ?></td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-success btn-md open-modal-button"
                                                                    data-toggle="modal" data-target="#modal-edit"
                                                                    data-id_supplier="<?= $row['id_supplier'] ?>"
                                                                    data-nama_supplier="<?= $row['nama_supplier'] ?>"
                                                                    data-no_hp="<?= $row['no_hp'] ?>"
                                                                    data-alamat="<?= $row['alamat'] ?>"><i
                                                                        class="fas fa-pencil-alt"></i> Edit</button>
                                                                <a href="barang.php?id_supplier=<?= $row['id_supplier'] ?>"
                                                                    class="btn btn-primary btn-md"><i class="fas fa-box"></i>
                                                                    Barang</a>
                                                                <a href="aksi.php?id_supplier=<?= $row['id_supplier'] ?>"
                                                                    class="btn btn-danger btn-md"
                                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><i
                                                                        class="fas fa-trash"></i> Hapus</a>
                                                            </td>
                                                            <?php
                                                    }
                                                } else {

                                                }
                                                ?>
                                                </tr>
                                            </tbody>
                                        </table>
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

            <!-- modal tambah -->
            <div class="modal fade" id="modal-tambah">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Supplier</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="aksi.php" method="POST">
                                <div class="form-group">
                                    <label for="nama_supplier"> Nama Supplier:
                                    </label>
                                    <input type="text" name="nama_supplier" class="form-control" id="nama_supplier"
                                        maxlength="255">
                                </div>
                                <div class="form-group">
                                    <label for="no_hp"> No Hp:
                                    </label>
                                    <input type="text" name="no_hp" class="form-control" id="no_hp" maxlength="12">
                                </div>
                                <div class="form-group">
                                    <label for="alamat"> Alamat:
                                    </label>
                                    <input type="text" name="alamat" class="form-control" id="alamat" maxlength="255">
                                </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.control-sidebar -->

                <!-- Main Footer -->
            </div>

            <!-- modal edit -->
            <div class="modal fade" id="modal-edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Supplier</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="aksi.php" method="POST">
                                <div class="form-group">
                                    <input type="hidden" name="id_supplier2" id="id_supplier2">
                                    <label for="nama_supplier"> Nama Supplier:
                                    </label>
                                    <input type="text" name="nama_supplier2" class="form-control" id="nama_supplier2"
                                        maxlength="255">
                                </div>
                                <div class="form-group">
                                    <label for="no_hp"> No Hp:
                                    </label>
                                    <input type="text" name="no_hp2" class="form-control" id="no_hp2" maxlength="12">
                                </div>
                                <div class="form-group">
                                    <label for="alamat"> Alamat:
                                    </label>
                                    <input type="text" name="alamat2" class="form-control" id="alamat2" maxlength="255">
                                </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.control-sidebar -->
            </div>

            <!-- ./wrapper -->

            <?php
            include '../layout/footer.php';
            ?>
            <?php
            include '../layout/script.php';
            ?>
           
    </body>

    </html>
    <?php
}
?>