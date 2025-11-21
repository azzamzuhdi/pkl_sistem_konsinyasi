<?php
require_once '../db/conn.php';
$id_supplier = $_GET['id_supplier'];

// Ambil daftar kode barang yang sudah ada (untuk dropdown)
$existing_kode = mysqli_query($conn, "SELECT DISTINCT kode_barang, nama_barang, harga_konsinyasi, harga_jual, stok_masuk FROM tb_barang ORDER BY kode_barang ASC") or die(mysqli_error($conn));

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
                                            $query_barang = mysqli_query($conn, "SELECT * FROM tb_barang WHERE id_supplier = '$id_supplier' AND status = 'aktif'") or die(mysqli_error($conn));
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
                                                                data-id-barang="<?= $row['id_barang'] ?>"
                                                                data-kode-barang="<?= $row['kode_barang'] ?>"
                                                                data-nama-barang="<?= $row['nama_barang'] ?>"
                                                                data-harga-konsinyasi="<?= $row['harga_konsinyasi'] ?>"
                                                                data-harga-jual="<?= $row['harga_jual'] ?>"
                                                                data-id-supplier="<?= $row['id_supplier'] ?>">
                                                                <i class=" fas fa-pencil-alt"></i> Edit
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


                                <div class="form-group">
                                    <label for="kode_select">Kode Barang</label>
                                    <select id="kode_select" name="kode_barang" class="form-control">
                                        <option value="">-- Pilih Kode atau Tambah Baru --</option>
                                        <option value="__new__">+ Tambah kode baru</option>
                                        <?php
                                        // tampilkan daftar kode barang yang sudah ada
                                        if (isset($existing_kode) && $existing_kode && mysqli_num_rows($existing_kode) > 0) {
                                            // catatan: setiap opsi menyimpan nama pada data-nama untuk autofill
                                            mysqli_data_seek($existing_kode, 0);
                                            while ($ek = mysqli_fetch_assoc($existing_kode)) {
                                                $kb = htmlspecialchars($ek['kode_barang']);
                                                $nb = htmlspecialchars($ek['nama_barang']);
                                                $hk = htmlspecialchars($ek['harga_konsinyasi']);
                                                $hj = htmlspecialchars($ek['harga_jual']);
                                                $sm = htmlspecialchars($ek['stok_masuk']);
                                                echo "<option value=\"$kb\" data-nama=\"$nb\" data-harga=\"$hk\" data-harga_jual=\"$hj\" data-stok=\"$sm\">$kb - $nb</option>";
                                            }
                                        }
                                        ?>
                                    </select>

                                    <!-- Input untuk kode baru, disembunyikan kecuali user memilih 'Tambah baru' -->
                                    <input type="text" class="form-control mt-2" id="kode_barang" maxlength="5"
                                        style="display:none;" placeholder="Masukkan kode baru">
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
                                    <input type="number" name="harga_konsinyasi" class="form-control"
                                        id="harga_konsinyasi" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_jual"> Harga Jual:
                                    </label>
                                    <input type="number" name="harga_jual" class="form-control" id="harga_jual"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="stok_masuk"> Stok Masuk:
                                    </label>
                                    <input type="number" name="stok_masuk" class="form-control" id="stok_masuk"
                                        required>
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
            $(document).ready(function () {
                $(document).on('click', '.open-modal-button', function () {

                    $('#modal-edit-barang').modal('show');

                    var id_barang = $(this).data('id-barang');
                    var kode_barang = $(this).data('kode-barang');
                    var nama_barang = $(this).data('nama-barang');
                    var harga_konsinyasi = $(this).data('harga-konsinyasi');
                    var harga_jual = $(this).data('harga-jual');
                    var id_supplier = $(this).data('id-supplier');


                    $('#id_barang2').val(id_barang);
                    $('#kode_barang2').val(kode_barang);
                    $('#nama_barang2').val(nama_barang);
                    $('#harga_konsinyasi2').val(harga_konsinyasi);
                    $('#harga_jual2').val(harga_jual);
                    $('#id_supplier2').val(id_supplier);
                });
            });


        </script>
        <script>
            // Toggle kode select / new-code input and autofill nama barang
            $(function () {
                // When selection changes
                $('#kode_select').on('change', function () {
                    var val = $(this).val();
                    if (val === '__new__') {
                        // user wants to add new kode -> show input, move name
                        $('#kode_barang').show().attr('name', 'kode_barang');
                        $('#kode_select').removeAttr('name');
                        $('#nama_barang').val('');
                        $('#nama_barang').prop('readonly', false);
                        $('#kode_barang').focus();
                    } else if (val === '') {
                        // no selection
                        $('#kode_barang').hide().removeAttr('name');
                        $('#kode_select').attr('name', 'kode_barang');
                        $('#nama_barang').val('');
                        $('#nama_barang').prop('readonly', false);
                        $('#harga_konsinyasi').val('');
                        $('#harga_jual').val('');
                        $('#stok_masuk').val('');
                    } else {
                        // existing code selected -> hide new-code input and autofill name
                        var nama = $('#kode_select option:selected').data('nama') || '';
                        var harga = $('#kode_select option:selected').data('harga') || '';
                        var harga_jual = $('#kode_select option:selected').data('harga_jual') || '';
                        var stok = $('#kode_select option:selected').data('stok') || '';
                        $('#kode_barang').hide().removeAttr('name');
                        $('#kode_select').attr('name', 'kode_barang');
                        $('#nama_barang').val(nama);
                        $('#nama_barang').prop('readonly', true);
                        $('#harga_konsinyasi').val(harga);
                        $('#harga_jual').val(harga_jual);
                        $('#stok_masuk').val(stok);
                    }
                });

                // When add modal is opened, reset fields
                $('#modal-tambah-barang').on('show.bs.modal', function () {
                    $('#kode_select').val('');
                    $('#kode_select').attr('name', 'kode_barang');
                    $('#kode_barang').hide().removeAttr('name').val('');
                    $('#nama_barang').val('');
                    $('#harga_konsinyasi').val('');
                    $('#harga_jual').val('');
                    $('#stok_masuk').val('');
                });
            });
        </script>
</body>

</html>
<?php
?>