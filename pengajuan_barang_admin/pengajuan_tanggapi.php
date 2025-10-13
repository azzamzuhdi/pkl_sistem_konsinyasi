<?php
require_once '../db/conn.php';
session_start();

if ($_SESSION['peran'] != '0') {
    session_destroy();
    header('location:../auth');
    exit();
}

if ((isset($_POST['id']) && isset($_POST['aksi'])) || (isset($_GET['id']) && isset($_GET['aksi']))) {
    // Ambil data dari POST jika ada, kalau tidak ambil dari GET
    $id = $_POST['id'] ?? $_GET['id'];
    $aksi = $_POST['aksi'] ?? $_GET['aksi'];
    $harga_jual = $_POST['harga_jual'] ?? null; // <- ambil harga jual dari form

    if ($aksi == 'terima') {
        $status = 'Disetujui';
    } elseif ($aksi == 'tolak') {
        $status = 'Ditolak';
    } else {
        $status = 'Menunggu';
    }

    // Ambil data pengajuan
    $query = mysqli_query($conn, "SELECT * FROM tb_pengajuan_barang WHERE id_pengajuan = '$id'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Update status pengajuan
        $update = mysqli_query($conn, "UPDATE tb_pengajuan_barang SET status_pengajuan='$status' WHERE id_pengajuan='$id'");

        if ($update) {
            // Jika pengajuan diterima → tambahkan ke tb_barang
            if ($status == 'Disetujui') {
                $id_supplier = $data['id_supplier'];
                $kode_barang = $data['kode_barang'];
                $nama_barang = $data['nama_barang'];
                $harga_konsinyasi = $data['harga_konsinyasi'];
                $stok_masuk = $data['stok_masuk'];
                $sisa_stok = $stok_masuk;

                // pastikan harga_jual tidak null
                if (!empty($harga_jual)) {
                    $insert = mysqli_query($conn, "INSERT INTO tb_barang 
                        (id_supplier, kode_barang, nama_barang, harga_konsinyasi, harga_jual, stok_masuk, sisa_stok)
                        VALUES 
                        ('$id_supplier', '$kode_barang', '$nama_barang', '$harga_konsinyasi', '$harga_jual', '$stok_masuk', '$sisa_stok')");
                } else {
                    echo "<script>
                            alert('Gagal! Harga jual belum diisi.');
                            window.history.back();
                          </script>";
                    exit;
                }

                if ($insert) {
                    echo "<script>
                            alert('Barang berhasil ditambahkan ke tabel barang dengan harga jual Rp. $harga_jual!');
                            window.location.href='index.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Status disetujui, tetapi gagal menambahkan ke tabel barang: " . mysqli_error($conn) . "');
                            window.location.href='index.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Status pengajuan berhasil diperbarui menjadi $status.');
                        window.location.href='index.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Gagal memperbarui status pengajuan!');
                    window.location.href='index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data pengajuan tidak ditemukan!');
                window.location.href='index.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Permintaan tidak valid!');
            window.location.href='index.php';
          </script>";
}
?>