-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2026 at 12:27 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indomaret_rpl4`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_detail_transaksi`
--

CREATE TABLE `tabel_detail_transaksi` (
  `kode_transaksi` varchar(50) NOT NULL,
  `id_produk` int DEFAULT NULL,
  `id_kasir` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `kuantitas` int DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `harga_jual` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_detail_transaksi`
--

INSERT INTO `tabel_detail_transaksi` (`kode_transaksi`, `id_produk`, `id_kasir`, `harga`, `kuantitas`, `diskon`, `harga_jual`) VALUES
('TRX001', 1, 1, 3500, 1, 0, 3500),
('TRX002', 2, 2, 4000, 2, 0, 8000),
('TRX003', 3, 3, 5000, 3, 500, 14500),
('TRX004', 4, 4, 15000, 2, 1000, 29000),
('TRX005', 5, 5, 75000, 1, 5000, 70000);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_donasi`
--

CREATE TABLE `tabel_donasi` (
  `id_donasi` int NOT NULL,
  `nama_donasi` varchar(50) DEFAULT NULL,
  `harga_donasi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_donasi`
--

INSERT INTO `tabel_donasi` (`id_donasi`, `nama_donasi`, `harga_donasi`) VALUES
(1, 'Donasi Pendidikan', 1000),
(2, 'Donasi Kemanusiaan', 2000),
(3, 'Donasi Bencana Alam', 5000),
(4, 'Donasi Anak Yatim', 10000),
(5, 'Donasi Lingkungan', 2000);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_kasir`
--

CREATE TABLE `tabel_kasir` (
  `id_kasir` int NOT NULL,
  `nama_kasir` varchar(50) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_kasir`
--

INSERT INTO `tabel_kasir` (`id_kasir`, `nama_kasir`, `no_telp`, `alamat`) VALUES
(1, 'Andi Saputra', '081234567801', 'Denpasar'),
(2, 'Budi Santoso', '081234567802', 'Badung'),
(3, 'Citra Dewi', '081234567803', 'Gianyar'),
(4, 'Dewi Lestari', '081234567804', 'Tabanan'),
(5, 'Eka Pratama', '081234567805', 'Bangli');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_produk`
--

CREATE TABLE `tabel_produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(50) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `diskon` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_produk`
--

INSERT INTO `tabel_produk` (`id_produk`, `nama_produk`, `harga`, `diskon`) VALUES
(1, 'Indomie Goreng', 3500, 0),
(2, 'Aqua 600ml', 4000, 0),
(3, 'Teh Botol Sosro', 5000, 500),
(4, 'Roti Tawar', 15000, 1000),
(5, 'Beras 5 Kg', 75000, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_transaksi`
--

CREATE TABLE `tabel_transaksi` (
  `kode_transaksi` varchar(50) NOT NULL,
  `tanggal_transaksi` datetime DEFAULT NULL,
  `no_order` int DEFAULT NULL,
  `total` float DEFAULT NULL,
  `QRIS` float DEFAULT NULL,
  `tunai` int DEFAULT NULL,
  `kembalian` int DEFAULT NULL,
  `id_donasi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_transaksi`
--

INSERT INTO `tabel_transaksi` (`kode_transaksi`, `tanggal_transaksi`, `no_order`, `total`, `QRIS`, `tunai`, `kembalian`, `id_donasi`) VALUES
('TRX001', '2026-09-16 08:00:00', 1001, 3500, 3500, 0, 0, 1),
('TRX002', '2026-09-16 08:15:00', 1002, 9000, 0, 10000, 1000, 2),
('TRX003', '2026-09-16 08:30:00', 1003, 14500, 14500, 0, 0, 3),
('TRX004', '2026-09-16 09:00:00', 1004, 29000, 0, 30000, 1000, 4),
('TRX005', '2026-09-16 09:30:00', 1005, 70000, 70000, 0, 0, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_detail_transaksi`
--
ALTER TABLE `tabel_detail_transaksi`
  ADD PRIMARY KEY (`kode_transaksi`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_kasir` (`id_kasir`);

--
-- Indexes for table `tabel_donasi`
--
ALTER TABLE `tabel_donasi`
  ADD PRIMARY KEY (`id_donasi`);

--
-- Indexes for table `tabel_kasir`
--
ALTER TABLE `tabel_kasir`
  ADD PRIMARY KEY (`id_kasir`);

--
-- Indexes for table `tabel_produk`
--
ALTER TABLE `tabel_produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `tabel_transaksi`
--
ALTER TABLE `tabel_transaksi`
  ADD PRIMARY KEY (`kode_transaksi`),
  ADD KEY `id_donasi` (`id_donasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_donasi`
--
ALTER TABLE `tabel_donasi`
  MODIFY `id_donasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tabel_kasir`
--
ALTER TABLE `tabel_kasir`
  MODIFY `id_kasir` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tabel_produk`
--
ALTER TABLE `tabel_produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tabel_detail_transaksi`
--
ALTER TABLE `tabel_detail_transaksi`
  ADD CONSTRAINT `tabel_detail_transaksi_ibfk_1` FOREIGN KEY (`kode_transaksi`) REFERENCES `tabel_transaksi` (`kode_transaksi`),
  ADD CONSTRAINT `tabel_detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `tabel_produk` (`id_produk`),
  ADD CONSTRAINT `tabel_detail_transaksi_ibfk_3` FOREIGN KEY (`id_kasir`) REFERENCES `tabel_kasir` (`id_kasir`);

--
-- Constraints for table `tabel_transaksi`
--
ALTER TABLE `tabel_transaksi`
  ADD CONSTRAINT `tabel_transaksi_ibfk_1` FOREIGN KEY (`id_donasi`) REFERENCES `tabel_donasi` (`id_donasi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
