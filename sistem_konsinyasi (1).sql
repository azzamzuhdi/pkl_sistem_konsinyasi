-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 02, 2026 at 12:23 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_konsinyasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id_barang` int NOT NULL,
  `id_supplier` int NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga_konsinyasi` int NOT NULL,
  `harga_jual` int DEFAULT NULL,
  `stok_masuk` int NOT NULL,
  `sisa_stok` int NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_barang`
--

INSERT INTO `tb_barang` (`id_barang`, `id_supplier`, `kode_barang`, `nama_barang`, `harga_konsinyasi`, `harga_jual`, `stok_masuk`, `sisa_stok`, `status`) VALUES
(28, 18, 'GF213', 'Magnum kretek', 25000, 28000, 132, 32, 'aktif'),
(30, 18, 'GTY52', 'Popok', 12000, 15000, 32, 25, 'aktif'),
(31, 18, 'HHY67', 'Nabati', 2000, 2500, 37, 37, 'aktif'),
(32, 18, 'GHHA', 'Oreo', 1000, 2000, 25, 25, 'nonaktif'),
(33, 20, 'HKK01', 'Susu Ultramilk', 2000, 3000, 100, 78, 'nonaktif'),
(34, 20, 'KKA2', 'Aqua', 4500, 5000, 30, 30, 'aktif'),
(35, 20, 'FFH', 'Aqua', 4500, 5000, 30, 30, 'aktif'),
(36, 20, 'GTR5', 'Vit', 4000, 6000, 100, -20, 'nonaktif'),
(37, 20, 'UI768', 'Tuton beras', 10000, 12000, 10, 10, 'nonaktif'),
(38, 18, 'GF210', 'Nabati', 2000, 3000, 34, 34, 'nonaktif'),
(39, 18, 'dE432', 'Good day', 3000, 4000, 12, 12, 'aktif'),
(40, 18, 'HUO78', 'Class mild', 25000, 26000, 40, 40, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran_supplier`
--

CREATE TABLE `tb_pembayaran_supplier` (
  `id_pembayaran` int NOT NULL,
  `id_supplier` int NOT NULL,
  `id_keluar` int DEFAULT NULL,
  `total_pembayaran` decimal(15,2) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `keterangan` text,
  `notifikasi_sent` tinyint(1) DEFAULT '0',
  `invoice_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pembayaran_supplier`
--

INSERT INTO `tb_pembayaran_supplier` (`id_pembayaran`, `id_supplier`, `id_keluar`, `total_pembayaran`, `tanggal_pembayaran`, `keterangan`, `notifikasi_sent`, `invoice_number`) VALUES
(1, 18, 1, '75000.00', '2025-10-16', 'Cash', 0, ''),
(2, 18, 4, '75000.00', '2025-10-17', 'cash', 0, ''),
(3, 18, 5, '100000.00', '2025-10-17', 'cash', 1, 'INV-20251017-0003'),
(4, 18, 6, '25000.00', '2025-10-18', 'transfer\r\n', 0, 'INV-20251018-0002'),
(5, 18, 7, '25000.00', '2025-10-18', 'Transfer', 0, 'INV-20251018-0003'),
(6, 18, 8, '1250000.00', '2025-11-07', 'Cash', 0, 'INV-20251107-0002'),
(7, 20, 11, '320000.00', '2025-11-08', 'Transfer', 0, 'INV-20251108-0002'),
(8, 20, 14, '4000.00', '2025-12-30', 'Cash', 0, 'INV-20251230-0002');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengajuan_barang`
--

CREATE TABLE `tb_pengajuan_barang` (
  `id_pengajuan` int NOT NULL,
  `id_supplier` int NOT NULL,
  `kode_barang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga_konsinyasi` int NOT NULL,
  `harga_jual` int DEFAULT NULL,
  `stok_masuk` int NOT NULL,
  `status_pengajuan` enum('Menunggu','Disetujui','Ditolak') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pengajuan_barang`
--

INSERT INTO `tb_pengajuan_barang` (`id_pengajuan`, `id_supplier`, `kode_barang`, `nama_barang`, `harga_konsinyasi`, `harga_jual`, `stok_masuk`, `status_pengajuan`) VALUES
(13, 20, NULL, 'Pepsodent', 1400, NULL, 20, 'Menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `tb_retur_barang`
--

CREATE TABLE `tb_retur_barang` (
  `id_retur` int NOT NULL,
  `id_supplier` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `jumlah_retur` int DEFAULT NULL,
  `alasan` enum('Rusak','Kadaluarsa') DEFAULT NULL,
  `tanggal_retur` datetime DEFAULT NULL,
  `status_retur` enum('Menunggu','Diterima','Ditolak') DEFAULT 'Menunggu',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_retur_barang`
--

INSERT INTO `tb_retur_barang` (`id_retur`, `id_supplier`, `id_barang`, `jumlah_retur`, `alasan`, `tanggal_retur`, `status_retur`, `keterangan`) VALUES
(1, 18, 28, 5, 'Rusak', '2025-10-16 16:40:37', 'Diterima', ''),
(2, 18, 28, 2, 'Rusak', '2025-10-16 16:54:34', 'Diterima', ''),
(3, 18, 28, 9, 'Rusak', '2025-11-03 10:22:50', 'Diterima', 'acc'),
(4, 20, 36, 20, 'Rusak', '2025-11-03 10:40:00', 'Diterima', 'bocor'),
(5, 18, 30, 5, 'Rusak', '2025-12-30 20:02:17', 'Ditolak', ''),
(6, 20, 33, 20, 'Rusak', '2025-12-30 20:14:55', 'Menunggu', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok_keluar`
--

CREATE TABLE `tb_stok_keluar` (
  `id_keluar` int NOT NULL,
  `id_barang` int NOT NULL,
  `id_supplier` int NOT NULL,
  `jumlah` int NOT NULL,
  `jenis_keluar` enum('Rusak','Kadaluarsa','Terjual') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('Sudah Dibayar','Belum Dibayar','Tidak Terjual') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Belum Dibayar',
  `status_retur` enum('Belum','Sudah') DEFAULT 'Belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_stok_keluar`
--

INSERT INTO `tb_stok_keluar` (`id_keluar`, `id_barang`, `id_supplier`, `jumlah`, `jenis_keluar`, `tanggal`, `keterangan`, `status_pembayaran`, `status_retur`) VALUES
(1, 28, 18, 3, 'Terjual', '2025-10-16 16:37:56', 'done', 'Sudah Dibayar', 'Belum'),
(2, 28, 18, 5, 'Rusak', '2025-10-16 16:39:58', 'rusak', 'Tidak Terjual', 'Sudah'),
(3, 28, 18, 2, 'Kadaluarsa', '2025-10-16 16:54:18', 'kadal', 'Tidak Terjual', 'Sudah'),
(4, 28, 18, 3, 'Terjual', '2025-10-17 21:12:54', 'd', 'Sudah Dibayar', 'Belum'),
(5, 28, 18, 4, 'Terjual', '2025-10-17 21:45:57', 'sa', 'Sudah Dibayar', 'Belum'),
(6, 28, 18, 1, 'Terjual', '2025-10-18 20:13:02', 'ya', 'Sudah Dibayar', 'Belum'),
(7, 28, 18, 1, 'Terjual', '2025-10-18 20:37:45', 'd', 'Sudah Dibayar', 'Belum'),
(8, 28, 18, 50, 'Terjual', '2025-11-03 10:13:29', 'terjual', 'Sudah Dibayar', 'Belum'),
(9, 30, 18, 5, 'Rusak', '2025-11-03 10:17:26', 'barang sudah terbuka', 'Tidak Terjual', 'Sudah'),
(10, 28, 18, 9, 'Kadaluarsa', '2025-11-03 10:22:30', 'tidak layak pake', 'Tidak Terjual', 'Sudah'),
(11, 36, 20, 80, 'Terjual', '2025-11-03 10:36:46', 'terjual', 'Sudah Dibayar', 'Belum'),
(12, 36, 20, 20, 'Rusak', '2025-11-03 10:37:20', 'bocor', 'Tidak Terjual', 'Sudah'),
(13, 33, 20, 20, 'Rusak', '2025-12-30 20:14:18', 'rusak', 'Tidak Terjual', 'Sudah'),
(14, 33, 20, 2, 'Terjual', '2025-12-30 20:16:44', 'ok', 'Sudah Dibayar', 'Belum');

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok_masuk`
--

CREATE TABLE `tb_stok_masuk` (
  `id_stok_masuk` int NOT NULL,
  `id_supplier` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah_masuk` int NOT NULL,
  `tanggal_masuk` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_stok_masuk`
--

INSERT INTO `tb_stok_masuk` (`id_stok_masuk`, `id_supplier`, `id_barang`, `jumlah_masuk`, `tanggal_masuk`) VALUES
(1, 18, 28, 5, '2025-10-16'),
(2, 18, 28, 50, '2025-10-30'),
(3, 18, 30, 14, '2025-10-30'),
(4, 18, 31, 17, '2025-10-30'),
(5, 18, 31, 20, '2025-10-30'),
(6, 18, 28, 20, '2025-11-03'),
(7, 18, 28, 10, '2025-11-03'),
(8, 18, 32, 25, '2025-11-03'),
(9, 20, 33, 100, '2025-11-03'),
(10, 20, 34, 30, '2025-11-03'),
(11, 20, 37, 10, '2025-11-21'),
(12, 18, 38, 34, '2025-11-21'),
(13, 18, 39, 12, '2025-11-21'),
(14, 18, 40, 40, '2025-12-30'),
(15, 18, 28, 1, '2026-01-02'),
(16, 18, 30, 2, '2026-01-02');

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier`
--

CREATE TABLE `tb_supplier` (
  `id_supplier` int NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `no_hp` varchar(12) NOT NULL,
  `alamat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_supplier`
--

INSERT INTO `tb_supplier` (`id_supplier`, `nama_supplier`, `no_hp`, `alamat`) VALUES
(18, 'Budi', '080981098390', 'bumiayu'),
(19, 'as', '082732500000', 'benda'),
(20, 'Wildan', '089999966666', 'Legok');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `peran` int NOT NULL,
  `id_supplier` int DEFAULT NULL,
  `nama_user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `peran`, `id_supplier`, `nama_user`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, NULL, 'Admin'),
(9, 'Budi', '6cc0f7b81d8dec3c2ee5ee3a0c70d01fa6fc0be7', 1, 18, 'Budi'),
(10, 'Sa', '50cf95cee82204c65fd924e1ab51401c2eb0dea6', 1, 19, 'Sa'),
(11, 'Wildan', '0217ec6597b6b55a628642fbf9c3dd332f7bab99', 1, 20, 'Wildan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `tb_pembayaran_supplier`
--
ALTER TABLE `tb_pembayaran_supplier`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `tb_pengajuan_barang`
--
ALTER TABLE `tb_pengajuan_barang`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `tb_retur_barang`
--
ALTER TABLE `tb_retur_barang`
  ADD PRIMARY KEY (`id_retur`);

--
-- Indexes for table `tb_stok_keluar`
--
ALTER TABLE `tb_stok_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `tb_stok_masuk`
--
ALTER TABLE `tb_stok_masuk`
  ADD PRIMARY KEY (`id_stok_masuk`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tb_pembayaran_supplier`
--
ALTER TABLE `tb_pembayaran_supplier`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_pengajuan_barang`
--
ALTER TABLE `tb_pengajuan_barang`
  MODIFY `id_pengajuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_retur_barang`
--
ALTER TABLE `tb_retur_barang`
  MODIFY `id_retur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_stok_keluar`
--
ALTER TABLE `tb_stok_keluar`
  MODIFY `id_keluar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_stok_masuk`
--
ALTER TABLE `tb_stok_masuk`
  MODIFY `id_stok_masuk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  MODIFY `id_supplier` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD CONSTRAINT `tb_barang_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `tb_supplier` (`id_supplier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pembayaran_supplier`
--
ALTER TABLE `tb_pembayaran_supplier`
  ADD CONSTRAINT `tb_pembayaran_supplier_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `tb_supplier` (`id_supplier`);

--
-- Constraints for table `tb_pengajuan_barang`
--
ALTER TABLE `tb_pengajuan_barang`
  ADD CONSTRAINT `tb_pengajuan_barang_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `tb_supplier` (`id_supplier`);

--
-- Constraints for table `tb_stok_keluar`
--
ALTER TABLE `tb_stok_keluar`
  ADD CONSTRAINT `tb_stok_keluar_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`) ON DELETE CASCADE;

--
-- Constraints for table `tb_stok_masuk`
--
ALTER TABLE `tb_stok_masuk`
  ADD CONSTRAINT `tb_stok_masuk_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `tb_supplier` (`id_supplier`),
  ADD CONSTRAINT `tb_stok_masuk_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
