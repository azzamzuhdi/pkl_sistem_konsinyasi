<?php
require_once '../db/conn.php';
$halaman = 'pengajuan_barang_admin';
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
        <title>Pengajuan  Barang | Sistem Konsinyasi</title>

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
                    <h1>Daftar Pengajuan Barang Supplier</h1>
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
                                                ?>
                                                <button type="button" class="btn btn-success btn-sm" title="Setujui"
                                                    onclick="bukaModalHarga('<?= $row['id_pengajuan'] ?>', '<?= htmlspecialchars($row['nama_barang']) ?>')">
                                                    <i class="fas fa-check"></i>
                                                </button>

                                                <a href="pengajuan_tanggapi.php"
                                                    onclick="return tolakPengajuan(<?= $row['id_pengajuan'] ?>)"
                                                    class="btn btn-danger btn-sm" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </a>


                                                <?php
                                            } else {
                                                echo "<span class='text-muted'>—</span>"; // tampilkan strip
                                            }
                                            echo "</td>";

                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- Modal Input Harga Jual -->
                                <div class="modal fade" id="modalHarga" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="formHarga" action="pengajuan_tanggapi.php" method="POST">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">Tentukan Harga Jual</h5>
                                                    <button type="button" class="close text-white"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" id="id_pengajuan">
                                                    <input type="hidden" name="aksi" value="terima">
                                                    <div class="form-group">
                                                        <label>Nama Barang</label>
                                                        <input type="text" id="nama_barang" class="form-control" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Harga Jual</label>
                                                        <input type="number" name="harga_jual" id="harga_jual"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan & Setujui</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

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
            <script>
                function bukaModalHarga(id, namaBarang) {
                    // Isi data ke form
                    document.getElementById('id_pengajuan').value = id;
                    document.getElementById('nama_barang').value = namaBarang;
                    document.getElementById('harga_jual').value = '';

                    // Tampilkan modal
                    $('#modalHarga').modal('show');
                }
            </script>

    </body>

    </html>
    <?php
}
?>