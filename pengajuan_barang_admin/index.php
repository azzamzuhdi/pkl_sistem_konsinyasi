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
                <section class="content-header">
                    <h1 class="text-center">Daftar Pengajuan Barang Supplier</h1>
                </section>

                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">Data Pengajuan Barang</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Supplier</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query tanpa kolom harga_jual dan tanggal_pengajuan
                                        $query = mysqli_query($conn, "SELECT p.id_pengajuan, s.nama_supplier, p.nama_barang, p.stok_masuk, p.status_pengajuan 
                                    FROM tb_pengajuan_barang p 
                                    JOIN tb_supplier s ON p.id_supplier = s.id_supplier
                                    ORDER BY p.id_pengajuan DESC");

                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>";
                                            echo "<td>{$no}</td>";
                                            echo "<td>{$row['nama_supplier']}</td>";
                                            echo "<td>{$row['nama_barang']}</td>";
                                            echo "<td>{$row['stok_masuk']}</td>";

                                            // Status pengajuan
                                            echo "<td>";
                                            if ($row['status_pengajuan'] == 'Menunggu') {
                                                echo "<span class='badge badge-warning'>Menunggu</span>";
                                            } elseif ($row['status_pengajuan'] == 'Disetujui') {
                                                echo "<span class='badge badge-success'>Diterima</span>";
                                            } else {
                                                echo "<span class='badge badge-danger'>Ditolak</span>";
                                            }
                                            echo "</td>";

                                            // Aksi
                                            echo "<td class='text-center'>";
                                            if ($row['status_pengajuan'] == 'Menunggu') {
                                                echo "
        <a href='pengajuan_tanggapi.php?id={$row['id_pengajuan']}&aksi=terima' class='btn btn-success btn-sm' title='Terima'>
            <i class='fas fa-check'></i>
        </a>
        <a href='pengajuan_tanggapi.php?id={$row['id_pengajuan']}&aksi=tolak' class='btn btn-danger btn-sm' title='Tolak'>
            <i class='fas fa-times'></i>
        </a>
    ";
                                            } else {
                                                echo "<span class='text-muted'>—</span>"; // tampilkan strip
                                            }
                                            echo "</td>";

                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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