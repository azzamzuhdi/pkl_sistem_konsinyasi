<?php
require_once '../db/conn.php';
$halaman = 'pembayaran_supplier';
if ($_SESSION['peran'] != '1') {
    session_destroy();
    header('location:../auth');
} else {

    $id_supplier = $_SESSION['id_supplier'];

    // Ambil pembayaran untuk supplier ini
    $q = mysqli_query($conn, "SELECT * FROM tb_pembayaran_supplier WHERE id_supplier = '$id_supplier' ORDER BY tanggal_pembayaran DESC") or die(mysqli_error($conn));

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pembayaran Saya | Sistem Konsinyasi</title>
        <?php include '../layout/style.php'; ?>
    </head>

    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <?php include '../layout/navbar.php'; ?>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1>Pembayaran Saya</h1>
                </section>

                <section class="content">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Invoice</th>
                                        <th>Total</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Status Notifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($q)) { ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= isset($row['invoice_number']) ? $row['invoice_number'] : '-' ?></td>
                                            <td>Rp. <?= number_format($row['total_pembayaran'],0,',','.') ?></td>
                                            <td><?= $row['tanggal_pembayaran'] ?></td>
                                            <td>
                                                <?php if (isset($row['notifikasi_sent']) && $row['notifikasi_sent'] == '1') { ?>
                                                    <span class="badge bg-success">Pembayaran Terkirim</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if (isset($row['notifikasi_sent']) && $row['notifikasi_sent'] == '1') { ?>
                                                    <a href="../pembayaran_supplier_admin/cetak_invoice.php?id_keluar=<?= $row['id_keluar'] ?>" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-print"></i> Cetak Invoice
                                                    </a>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <?php include '../layout/footer.php'; ?>
            <?php include '../layout/script.php'; ?>
        </div>
    </body>

    </html>

    <?php
}
?>