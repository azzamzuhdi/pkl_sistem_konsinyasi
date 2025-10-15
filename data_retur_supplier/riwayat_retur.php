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
                            <a href="riwayat_retur.php" class="btn btn-primary"><i class="fas fa-history"></i>Riwayat
                                Retur</a>
                            <p></p>
                            <?php
                            $id_supplier = $_SESSION['id_supplier'];
                            $query = mysqli_query($conn, "
                            SELECT sk.*, b.nama_barang
                            FROM tb_stok_keluar sk
                            JOIN tb_barang b ON sk.id_barang = b.id_barang
                            WHERE sk.id_supplier = '$id_supplier'
                            AND sk.jenis_keluar IN ('Rusak', 'Kadaluarsa') AND sk.status_retur = 'Sudah'
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
                                        </tr>
                                  
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