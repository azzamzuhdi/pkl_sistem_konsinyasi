<?php
require_once '../db/conn.php';

if ($_SESSION['peran'] != '0') {
    session_destroy();
    header('location:../auth');
    exit();
}

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id = $_GET['id'];
    $aksi = $_GET['aksi'];

    if ($aksi == 'terima') {
        $status = 'Disetujui';
    } elseif ($aksi == 'tolak') {
        $status = 'Ditolak';
    } else {
        $status = 'Menunggu';
    }

    // Ambil data pengajuan berdasarkan ID
    $query = mysqli_query($conn, "SELECT * FROM tb_pengajuan_barang WHERE id_pengajuan = '$id'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Update status pengajuan
        $update = mysqli_query($conn, "UPDATE tb_pengajuan_barang SET status_pengajuan='$status' WHERE id_pengajuan='$id'");

        if ($update) {
            // Jika pengajuan diterima → masukkan ke tabel tb_barang
            if ($status == 'Disetujui') {
                $id_supplier = $data['id_supplier'];
                $kode_barang = $data['kode_barang'];
                $nama_barang = $data['nama_barang'];
                $harga_konsinyasi = $data['harga_konsinyasi'];
                $stok_masuk = $data['stok_masuk'];
                $sisa_stok = $stok_masuk; // awalnya sisa stok sama dengan stok masuk
                $harga_jual = NULL; // harga jual nanti ditentukan admin secara manual

                $insert = mysqli_query($conn, "INSERT INTO tb_barang 
                    (id_supplier, kode_barang, nama_barang, harga_konsinyasi, stok_masuk, sisa_stok)
                    VALUES 
                    ('$id_supplier', '$kode_barang', '$nama_barang', '$harga_konsinyasi', '$stok_masuk', '$sisa_stok')");

                if ($insert) {
                    echo "<script>
                            alert('Pengajuan disetujui dan barang berhasil ditambahkan ke data barang!');
                            window.location.href='index.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Status disetujui, tetapi gagal menambahkan ke tabel barang!');
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