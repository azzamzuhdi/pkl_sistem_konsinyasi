<?php
require_once '../db/conn.php';
$id_supplier = $_SESSION['id_supplier'];
$halaman = 'pengajuan_barang_supplier';

// Ambil data pengajuan milik supplier yang login
$query = mysqli_query($conn, "SELECT * FROM tb_pengajuan_barang WHERE id_supplier = '$id_supplier' ORDER BY id_pengajuan DESC") or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Barang | Sistem Konsinyasi</title>
    <?php include '../layout/style.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include '../layout/navbar.php'; ?>
        <!-- /.navbar -->

     
        <!-- /.sidebar -->

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Daftar Pengajuan Barang</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped align-middle text-center">
                                <thead>
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Harga Konsinyasi</th>
                                        <th>Stok Masuk</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>
                                            <td>{$no}</td>
                                            <td>{$row['kode_barang']}</td>
                                            <td>{$row['nama_barang']}</td>
                                            <td>Rp " . number_format($row['harga_konsinyasi'], 0, ',', '.') . "</td>
                                            <td>{$row['stok_masuk']}</td>
                                            <td>";
                                            if ($row['status_pengajuan'] == 'Menunggu') {
                                                echo "<span class='badge badge-warning'>Menunggu</span>";
                                            } elseif ($row['status_pengajuan'] == 'Disetujui') {
                                                echo "<span class='badge badge-success'>Disetujui</span>";
                                            } else {
                                                echo "<span class='badge badge-danger'>Ditolak</span>";
                                            }
                                            echo "</td>
                                        </tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>Belum ada pengajuan barang.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include '../layout/footer.php'; ?>
    </div>

    <?php include '../layout/script.php'; ?>
</body>

</html>