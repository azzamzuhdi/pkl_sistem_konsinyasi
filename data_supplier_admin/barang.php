<?php
require_once '../db/conn.php';
$id_supplier = $_GET['id_supplier'];

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
                            <h1>Data Barang</h1>
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
                                        data-target="#modal-tambah-barang" data-id_supplier="<?= $id_supplier ?>"><i
                                            class="fas fa-plus"></i> Tambah
                                        Barang</button>
                                    <p></p>
                                    <table id="example1"
                                        class="table table-bordered table-striped align-middle text-center">
                                        <thead>
                                            <tr>
                                                <th style="width : 5%">No</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Harga Konsinyasi</th>
                                                <th>Harga Jual</th>
                                                <th>Stok Masuk</th>
                                                <th>Sisa Stok</th>
                                                <th style="width : 25%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_barang = mysqli_query($conn, "SELECT * FROM tb_barang WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
                                            $no = 1;
                                            $rv = mysqli_num_rows($query_barang);
                                            if ($rv > 0) {
                                                while ($row = mysqli_fetch_array($query_barang)) {
                                                    ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= $row['kode_barang'] ?></td>
                                                        <td><?= $row['nama_barang'] ?></td>
                                                        <td><?= $row['harga_konsinyasi'] ?></td>
                                                        <td><?= $row['harga_jual'] ?></td>
                                                        <td><?= $row['stok_masuk'] ?></td>
                                                        <td><?= $row['sisa_stok'] ?></td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-success btn-md open-modal-button"
                                                                data-toggle="modal" data-target="#modal-edit-barang"
                                                                data-id_barang="<?= $row['id_barang'] ?>"
                                                                data-kode_barang="<?= $row['kode_barang'] ?>"
                                                                data-nama_barang="<?= $row['nama_barang'] ?>"
                                                                data-harga_konsinyasi="<?= $row['harga_konsinyasi'] ?>"
                                                                data-harga_jual="<?= $row['harga_jual'] ?>"
                                                                data-id_supplier="<?= $row['id_supplier'] ?>">
                                                                <i class="fas fa-pencil-alt"></i> Edit
                                                            </button>

                                                            <a href="aksi.php?id_barang=<?= $row['id_barang'] ?>"
                                                                class="btn btn-danger btn-md"
                                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><i
                                                                    class="fas fa-trash"></i> Hapus</a>
                                                        </td>
                                                        <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='9'>Tidak ada data</td></tr>";
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
        <div class="modal fade" id="modal-tambah-barang">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Barang</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="aksi.php" method="POST">
                            <div class="form-group">
                                <input type="hidden" name="id_supplier" value="<?= $id_supplier ?>">
                                <input type="hidden" name="id_barang" id="id_barang">

                                <label for="kode_barang">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control" id="kode_barang"
                                    maxlength="5" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_barang"> Nama Barang:
                                </label>
                                <input type="text" name="nama_barang" class="form-control" id="nama_barang"
                                    maxlength="100" required>
                            </div>
                            <div class="form-group">
                                <label for="harga_konsinyasi"> Harga Konsinyasi:
                                </label>
                                <input type="number" name="harga_konsinyasi" class="form-control" id="harga_konsinyasi"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="harga_jual"> Harga Jual:
                                </label>
                                <input type="number" name="harga_jual" class="form-control" id="harga_jual" required>
                            </div>
                            <div class="form-group">
                                <label for="stok_masuk"> Stok Masuk:
                                </label>
                                <input type="number" name="stok_masuk" class="form-control" id="stok_masuk" required>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="tambah_barang" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
        </div>

        <!-- modal edit -->
        <div class="modal fade" id="modal-edit-barang">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Barang</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="aksi.php" method="POST">
                            <div class="form-group">
                                <input type="hidden" name="id_supplier2" id="id_supplier2">
                                <input type="hidden" name="id_barang2" id="id_barang2">
                                <label for="kode_barang"> Kode Barang:
                                </label>
                                <input type="text" name="kode_barang2" class="form-control" id="kode_barang2"
                                    maxlength="5" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama_barang"> Nama Barang:
                                </label>
                                <input type="text" name="nama_barang2" class="form-control" id="nama_barang2"
                                    maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="harga_konsinyasi"> Harga Konsinyasi:
                                </label>
                                <input type="number" name="harga_konsinyasi2" class="form-control"
                                    id="harga_konsinyasi2">
                            </div>
                            <div class="form-group">
                                <label for="harga_jual"> Harga Jual:
                                </label>
                                <input type="number" name="harga_jual2" class="form-control" id="harga_jual2">
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit_barang" class="btn btn-primary">Simpan</button>
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
        <!-- REQUIRED SCRIPTS -->
        <?php
        include '../layout/script.php';
        ?>
        <script type="text/javascript">
            $(document).on('click', '.open-modal-button', function () {
                var id_barang = $(this).data('id_barang');
                var kode_barang = $(this).data('kode_barang');
                var nama_barang = $(this).data('nama_barang');
                var harga_konsinyasi = $(this).data('harga_konsinyasi');
                var harga_jual = $(this).data('harga_jual');
                var stok_masuk = $(this).data('stok_masuk');
                var id_supplier = $(this).data('id_supplier');
                $('#id_barang2').val(id_barang);
                $('#kode_barang2').val(kode_barang);
                $('#nama_barang2').val(nama_barang);
                $('#harga_konsinyasi2').val(harga_konsinyasi);
                $('#harga_jual2').val(harga_jual);
                $('#stok_masuk2').val(stok_masuk);
                $('#id_supplier2').val(id_supplier);
                $('#modal-edit-barang').modal('show');
            });

        </script>
</body>

</html>
<?php
?>