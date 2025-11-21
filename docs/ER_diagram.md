ER Diagram (PlantUML)
======================

File: `docs/ER_diagram.puml`

Deskripsi singkat
- Diagram ini dibuat dari isi `sistem_konsinyasi.sql` (lihat root repo).
- Menggambarkan tabel inti dan relasi yang dideklarasikan (foreign keys) beserta beberapa relasi logis yang digunakan oleh aplikasi.

Tabel utama yang dimodelkan:
- `tb_supplier`
- `tb_user`
- `tb_barang`
- `tb_stok_masuk`
- `tb_stok_keluar`
- `tb_retur_barang`
- `tb_pengajuan_barang`
- `tb_pembayaran_supplier`

Cara merender diagram

1) VSCode (direkomendasikan)
- Pasang ekstensi "PlantUML" (jebbs.plantuml atau plantuml-visualizer).
- Buka `docs/ER_diagram.puml`, lalu klik preview PlantUML.

2) Menggunakan PlantUML CLI (Java)
- Install Java (jre/jdk) dan PlantUML jar.
- Contoh perintah (PowerShell):

```powershell
# ganti path sesuai lokasi plantuml.jar
java -jar C:\tools\plantuml\plantuml.jar docs\ER_diagram.puml
```

Perintah tersebut akan menghasilkan `docs/ER_diagram.png` di folder yang sama.

Catatan penting
- Diagram memetakan foreign keys persis seperti yang ada pada SQL dump (`ALTER TABLE ... ADD CONSTRAINT ...`). Beberapa kolom (mis. `tb_retur_barang.id_supplier`) tidak memiliki constraint eksplisit di SQL tetapi digunakan secara logis di kode; diagram menampilkan relasi logis tersebut sebagai komentar.
- Untuk laporan skripsi, gunakan file PlantUML sebagai sumber yang dapat diubah jika skema berubah.

Jika Anda ingin, saya dapat:
- Mengenerate PNG/SVG langsung (butuh tool PlantUML di environment), atau
- Membuat versi Mermaid (untuk langsung ditampilkan di Markdown), atau
- Menambahkan anotasi kolom kunci/nullable lebih lengkap.

