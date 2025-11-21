# Konsep Pemakaian CRUD pada Sistem Konsinyasi

## 1. Create (Membuat Data Baru)

Operasi Create ditujukan untuk menambah data baru dalam sistem. Di dalam aplikasi web, kegiatan ini umumnya dilakukan melalui form isian yang terhubung ke server. Saat pengguna mengirim form isian menggunakan tombol submit, sistem akan memproses dan menyimpan data ke dalam basis data. Setelah itu, data baru akan ditampilkan di daftar atau halaman yang menyajikan hasil entri yang baru dibuat, menandakan proses penambahan data berhasil dilakukan.

**Contoh Implementasi dalam Sistem:**
- **Tambah Supplier**: Form modal pada halaman `data_supplier_admin/index.php` yang mengirim data ke `data_supplier_admin/aksi.php` dengan method POST. Data yang diinput (nama_supplier, no_hp, alamat) akan disimpan ke tabel `tb_supplier` dan secara otomatis membuat akun user di tabel `tb_user`.
- **Tambah Barang**: Form pada halaman `data_barang_supplier/index.php` yang menyimpan data barang (kode_barang, nama_barang, harga_konsinyasi, harga_jual, stok_masuk) ke tabel `tb_barang` dan mencatat stok masuk di tabel `tb_stok_masuk`.
- **Tambah Stok Masuk**: Form pada halaman `stok_masuk_admin/index.php` yang menambahkan jumlah stok baru ke barang yang sudah ada, kemudian memperbarui stok masuk dan sisa stok di tabel `tb_barang`.

**Alur Proses:**
1. Pengguna mengisi form yang tersedia (biasanya dalam modal atau halaman form)
2. Data dikirim ke file `aksi.php` melalui method POST
3. Sistem melakukan validasi (misalnya cek duplikasi data)
4. Data disimpan ke database menggunakan query INSERT
5. Sistem menampilkan notifikasi sukses dan redirect ke halaman daftar data

---

## 2. Read (Membaca/Menampilkan Data)

Operasi Read adalah menampilkan data atau mengambil data dari basis data yang tidak merubah isi data. Pada aplikasi berbasis web, fungsi ini biasanya diimplementasikan dalam bentuk query yang menampilkan seluruh isi tabel atau entri tertentu sesuai permintaan pengguna. Hal ini dilakukan untuk memberikan akses yang cepat terhadap informasi yang tersimpan dalam sistem.

**Contoh Implementasi dalam Sistem:**
- **Menampilkan Daftar Supplier**: Query `SELECT * FROM tb_supplier` pada halaman `data_supplier_admin/index.php` yang menampilkan semua data supplier dalam bentuk tabel dengan kolom nama supplier, no HP, dan alamat.
- **Menampilkan Daftar Barang**: Query dengan JOIN antara tabel `tb_barang` dan `tb_supplier` untuk menampilkan informasi lengkap barang beserta data supplier pemiliknya.
- **Menampilkan Laporan**: Query kompleks dengan agregasi data untuk menampilkan laporan penjualan, laporan retur, atau laporan stok yang dapat diekspor ke PDF.

**Alur Proses:**
1. Sistem menjalankan query SELECT dari database
2. Data yang diambil ditampilkan dalam bentuk tabel menggunakan DataTables
3. Pengguna dapat melakukan pencarian, sorting, dan filtering data
4. Data ditampilkan tanpa mengubah isi data asli di database

---

## 3. Update (Memperbarui Data)

Operasi Update dimaksudkan untuk merevisi data yang ada di database. Tujuannya adalah untuk mengubah nilai data agar tetap relevan dan tepat. Dalam praktiknya, pengguna mengedit informasi melalui formulir pembaruan, setelah itu sistem menyimpan perubahan ke database yang sama. Setelah prosedur pembaruan selesai, sistem menampilkan informasi yang diperbarui untuk memastikan bahwa informasi tersebut akurat.

**Contoh Implementasi dalam Sistem:**
- **Edit Supplier**: Form modal edit pada halaman `data_supplier_admin/index.php` yang mengambil data supplier berdasarkan `id_supplier`, kemudian mengisi form dengan data lama. Setelah pengguna mengubah data dan submit, sistem menjalankan query `UPDATE tb_supplier SET ... WHERE id_supplier = ...` di file `aksi.php`.
- **Edit Barang**: Form edit pada halaman `data_barang_supplier/index.php` yang memperbarui informasi barang seperti kode_barang, nama_barang, harga_konsinyasi, dan harga_jual menggunakan query `UPDATE tb_barang SET ... WHERE id_barang = ...`.
- **Update Harga Barang**: Fitur khusus pada `data_barang_supplier/simpan_perubahan_harga.php` yang memungkinkan supplier memperbarui harga konsinyasi dan harga jual barang mereka.

**Alur Proses:**
1. Pengguna mengklik tombol Edit pada data yang ingin diubah
2. Sistem mengambil data lama dari database berdasarkan ID
3. Form modal atau halaman edit diisi dengan data lama (auto-fill)
4. Pengguna mengubah data yang diperlukan
5. Data dikirim ke file `aksi.php` melalui method POST
6. Sistem menjalankan query UPDATE dengan kondisi WHERE berdasarkan ID
7. Sistem menampilkan notifikasi sukses dan redirect ke halaman daftar

---

## 4. Delete (Menghapus Data)

Operasi Delete dirancang untuk menghapus data yang tidak lagi dibutuhkan. Penghapusan dapat dipicu melalui sebuah tombol, atau perintah yang dikirim ke server, yang kemudian memproses penghapusan data dari tabel database. Setelah data dihapus, sistem biasanya akan menyajikan daftar baru yang tidak menunjukkan entri yang telah dihapus.

**Contoh Implementasi dalam Sistem:**
- **Hapus Supplier**: Tombol hapus pada halaman `data_supplier_admin/index.php` yang mengirim parameter `id_supplier` melalui URL GET ke file `aksi.php`. Sistem akan menghapus data dari tabel `tb_supplier` dan juga menghapus akun user terkait di tabel `tb_user` karena ada relasi antara kedua tabel tersebut.
- **Hapus Barang**: Tombol hapus pada halaman `data_barang_supplier/index.php` yang menghapus data barang berdasarkan `id_barang` dari tabel `tb_barang`. Sistem juga melakukan redirect kembali ke halaman daftar barang supplier yang sesuai.
- **Konfirmasi Hapus**: Sebelum menghapus, sistem menampilkan dialog konfirmasi JavaScript `confirm('Apakah anda yakin ingin menghapus data ini?')` untuk mencegah penghapusan yang tidak disengaja.

**Alur Proses:**
1. Pengguna mengklik tombol Hapus pada data yang ingin dihapus
2. Sistem menampilkan dialog konfirmasi untuk memastikan niat pengguna
3. Jika dikonfirmasi, sistem mengirim parameter ID melalui URL GET ke file `aksi.php`
4. Sistem menjalankan query DELETE dengan kondisi WHERE berdasarkan ID
5. Jika ada data terkait (cascade), sistem juga menghapus data di tabel relasi
6. Sistem menampilkan notifikasi sukses dan redirect ke halaman daftar

**Catatan Penting:**
- Operasi delete bersifat permanen dan tidak dapat di-undo
- Sistem melakukan cascade delete untuk menjaga integritas data (misalnya menghapus user ketika supplier dihapus)
- Konfirmasi sebelum delete sangat penting untuk mencegah kehilangan data yang tidak disengaja

---

## Pola Umum Implementasi CRUD dalam Sistem

### Struktur File
- **index.php**: Halaman utama yang menampilkan daftar data (Read) dan form untuk Create/Update
- **aksi.php**: File yang menangani semua operasi CRUD (Create, Update, Delete) melalui method POST dan GET

### Keamanan
- Validasi data sebelum disimpan (cek duplikasi, format data)
- Konfirmasi sebelum operasi Delete
- Session management untuk kontrol akses berdasarkan peran user

### User Experience
- Penggunaan modal untuk form Create dan Update agar lebih interaktif
- Notifikasi sukses/error menggunakan JavaScript alert
- Auto-redirect setelah operasi berhasil
- DataTables untuk menampilkan data dengan fitur pencarian dan sorting

