<?php
require_once '../db/conn.php';
$halaman = 'pembayaran_supplier_admin';
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
        <title>Pembayaran Supplier | Sistem Konsinyasi</title>
        <?php include '../layout/style.php'; ?>
    </head>

    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">

            <!-- Navbar -->
            <?php include '../layout/navbar.php'; ?>
            <!-- /.navbar -->

            <!-- Content Wrapper -->
            <div class="content-wrapper">

                <!-- Content Header -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Pembayaran ke Supplier</h1>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-12">

                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card-body">
                                                    <form method="GET" action="">
                                                        <?php
                                                        $query_supplier = mysqli_query($conn, "SELECT * FROM tb_supplier") or die(mysqli_error($conn));
                                                        $id_supplier = isset($_GET['id_supplier']) ? mysqli_real_escape_string($conn, $_GET['id_supplier']) : '';
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
                                                            Data</button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card-body">
                                                    <?php
                                                    if ($id_supplier) {
                                                        $query_supplier_detail = mysqli_query($conn, "SELECT * FROM tb_supplier WHERE id_supplier = '$id_supplier'") or die(mysqli_error($conn));
                                                        $row_supplier = mysqli_fetch_assoc($query_supplier_detail);
                                                        ?>
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
                                            <?php
                                            // Ambil data hak supplier yang belum dibayar
                                            $query_tagihan = mysqli_query($conn, "
                                           SELECT 
    sk.id_keluar,
    sk.tanggal,
    b.nama_barang,
    sk.jumlah,
    sk.status_pembayaran,
    b.harga_konsinyasi,
    (sk.jumlah * b.harga_konsinyasi) AS total_hak
FROM tb_stok_keluar sk
JOIN tb_barang b ON sk.id_barang = b.id_barang
WHERE b.id_supplier = '$id_supplier'
  AND sk.jenis_keluar = 'Terjual'
ORDER BY sk.tanggal DESC;

                                        ") or die(mysqli_error($conn));
                                            ?>

                                            <div class="card">
                                                <div class="card-body">
                                                    <table id="example1" class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Tanggal</th>
                                                                <th>Nama Barang</th>
                                                                <th>Jumlah</th>
                                                                <th>Harga Konsinyasi</th>
                                                                <th>Total Hak Supplier</th>
                                                                <th>Status Pembayaran</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            $total_tagihan = 0;
                                                            while ($row = mysqli_fetch_assoc($query_tagihan)) {
                                                                $total_tagihan += $row['total_hak'];
                                                                ?>
                                                                <tr>
                                                                    <td><?= $no++ ?></td>
                                                                    <td><?= $row['tanggal'] ?></td>
                                                                    <td><?= $row['nama_barang'] ?></td>
                                                                    <td><?= $row['jumlah'] ?> pcs</td>
                                                                    <td>Rp.
                                                                        <?= number_format($row['harga_konsinyasi'], 0, ',', '.') ?>
                                                                    </td>
                                                                    <td>Rp. <?= number_format($row['total_hak'], 0, ',', '.') ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($row['status_pembayaran'] == 'Sudah Dibayar') { ?>
                                                                            <span class="badge bg-success">Sudah Dibayar</span>
                                                                        <?php } else { ?>
                                                                            <span class="badge bg-danger">Belum Dibayar</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($row['status_pembayaran'] == 'Belum Dibayar') { ?>
                                                                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                                                data-target="#modalBayar"
                                                                                data-idkeluar="<?= $row['id_keluar'] ?>"
                                                                                data-total="<?= $row['total_hak'] ?>">
                                                                                Bayar
                                                                            </button>

                                                                        <?php } else { ?>
                                                                            <a href="cetak_invoice.php?id_keluar=<?= $row['id_keluar'] ?>" target="_blank" class="btn btn-sm btn-info">
                                                                                <i class="fas fa-print"></i> Cetak Invoice
                                                                            </a>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="7" class="text-right">Total Hak Supplier</th>
                                                                <th colspan="1" class="text-right">Rp.
                                                                    <?= number_format($total_tagihan, 0, ',', '.') ?>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>

            <!-- Modal Pembayaran -->
            <div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="proses_bayar.php" method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pembayaran Barang</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="id_keluar" id="id_keluar">
                                <input type="hidden" name="id_supplier" id="id_supplier">

                                <div class="mb-3">
                                    <label>Total Pembayaran</label>
                                    <input type="text" name="total_pembayaran_tampil" id="total_pembayaran"
                                        class="form-control" readonly>

                                    <!-- nilai mentah untuk disimpan ke database -->
                                    <input type="hidden" name="total_pembayaran" id="total_pembayaran_hidden">
                                </div>

                                <div class="mb-3">
                                    <label>Tanggal Pembayaran</label>
                                    <input type="date" name="tanggal_pembayaran" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" placeholder="Cash / Transfer"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>




        </div>
    </body>
    <?php include '../layout/footer.php'; ?>
    <?php include '../layout/script.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Saat modal akan ditampilkan
            $('#modalBayar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // tombol yang diklik
                var id_keluar = button.data('idkeluar');
                var total = button.data('total');

                // Ambil id_supplier dari URL
                var urlParams = new URLSearchParams(window.location.search);
                var id_supplier = urlParams.get('id_supplier');

                // Format total menjadi Rupiah
                var totalFormatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(total);

                // Debugging — biar tahu nilainya benar atau tidak
                console.log("ID Supplier dari URL:", id_supplier);
                console.log("ID Keluar:", id_keluar);

                // Isi ke input dalam modal
                $(this).find('#id_keluar').val(id_keluar);
                $(this).find('#id_supplier').val(id_supplier);
                $(this).find('#total_pembayaran').val(totalFormatted);
                $(this).find('#total_pembayaran_hidden').val(total);
            });
        });
    </script>

    </html>

    <?php
}
?>