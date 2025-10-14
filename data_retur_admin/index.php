<?php
require_once '../db/conn.php';
$halaman = 'data_retur_admin';
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
                    <h1 class="">Daftar Pengajuan Retur</h1>
                </section>

                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Supplier</th>
                                            <th>Nama Barang</th>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = mysqli_query($conn, "
    SELECT 
        r.*, 
        b.nama_barang, 
        s.nama_supplier
    FROM tb_retur_barang r
    JOIN tb_barang b ON r.id_barang = b.id_barang
    JOIN tb_supplier s ON r.id_supplier = s.id_supplier
    WHERE r.alasan IN ('Rusak', 'Kadaluarsa')
    ORDER BY r.tanggal_retur DESC
") or die(mysqli_error($conn));

                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>";
                                            echo "<td>{$no}</td>";
                                            echo "<td>{$row['nama_supplier']}</td>";
                                            echo "<td>{$row['nama_barang']}</td>";
                                            echo "<td>{$row['tanggal_retur']}</td>";
                                            echo "<td>{$row['jumlah_retur']}</td>";

                                            // Tampilkan status retur dengan badge warna
                                            echo "<td>";
                                            if ($row['status_retur'] == 'Menunggu') {
                                                echo "<span class='badge badge-warning'>Menunggu</span>";
                                            } elseif ($row['status_retur'] == 'Diterima') {
                                                echo "<span class='badge badge-success'>Diterima</span>";
                                            } else {
                                                echo "<span class='badge badge-danger'>Ditolak</span>";
                                            }
                                            echo "</td>";

                                            // Tombol aksi (validasi oleh admin)
                                            echo "<td class='text-center'>";
                                            if ($row['status_retur'] == 'Menunggu') {
                                                echo "<a href='proses_retur.php?id={$row['id_retur']}&aksi=terima' 
       class='btn btn-success btn-sm' 
       onclick=\"return confirm('Setujui retur ini?')\" 
       title='Setujui'>
       <i class='fas fa-check'></i>
      </a> ";

                                                echo "<a href='proses_retur.php?id={$row['id_retur']}&aksi=tolak' 
       class='btn btn-danger btn-sm' 
       onclick=\"return confirm('Tolak retur ini?')\" 
       title='Tolak'>
       <i class='fas fa-times'></i>
      </a>";

                                            } else {
                                                echo "<i class='fas fa-minus text-muted'></i>"; // jika sudah divalidasi
                                            }
                                            echo "</td>";

                                            echo "</tr>";
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