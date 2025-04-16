-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for restoran
CREATE DATABASE IF NOT EXISTS `restoran` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `restoran`;

-- Dumping structure for table restoran.detail_transaksi
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `iddetail` int NOT NULL AUTO_INCREMENT,
  `idpesanan` int NOT NULL,
  `idmenu` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `idpesanan` (`idpesanan`),
  KEY `idmenu` (`idmenu`),
  CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`idpesanan`) REFERENCES `transaksi` (`idpesanan`),
  CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.detail_transaksi: ~0 rows (approximately)

-- Dumping structure for table restoran.meja
CREATE TABLE IF NOT EXISTS `meja` (
  `MejaID` varchar(10) NOT NULL,
  `Kapasitas` int NOT NULL DEFAULT '4',
  `Status` enum('Tersedia','Dipesan') DEFAULT 'Tersedia',
  `nomor_meja` varchar(50) NOT NULL,
  PRIMARY KEY (`MejaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.meja: ~0 rows (approximately)

-- Dumping structure for table restoran.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `idmenu` int NOT NULL AUTO_INCREMENT,
  `namamenu` varchar(100) DEFAULT NULL,
  `Kategori` enum('Makanan','Minuman') NOT NULL,
  `harga` int DEFAULT NULL,
  `Stok` int NOT NULL,
  PRIMARY KEY (`idmenu`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.menu: ~0 rows (approximately)

-- Dumping structure for table restoran.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `idpelanggan` int NOT NULL AUTO_INCREMENT,
  `namapelanggan` varchar(100) DEFAULT NULL,
  `jeniskelamin` tinyint(1) DEFAULT NULL,
  `nohp` char(13) DEFAULT NULL,
  `alamat` varchar(95) DEFAULT NULL,
  PRIMARY KEY (`idpelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.pelanggan: ~31 rows (approximately)
INSERT INTO `pelanggan` (`idpelanggan`, `namapelanggan`, `jeniskelamin`, `nohp`, `alamat`) VALUES
	(1, 'reza', NULL, '1234567890', 'gunung putri'),
	(2, 'reza', NULL, '1234567890', 'gunung putri'),
	(3, 'reza', NULL, '1234567890', 'gunung putri'),
	(4, 'reza', NULL, '1234567890', 'gunung putri'),
	(5, 'reza', NULL, '1234567890', 'gunung putri'),
	(6, 'reza', NULL, '1234567890', 'gunung putri'),
	(7, 'reza', NULL, '1234567890', 'gunung putri'),
	(8, 'reza', NULL, '1234567890', 'gunung putri'),
	(9, 'reza', NULL, '1234567890', 'gunung putri'),
	(10, 'reza', NULL, '1234567890', 'gunung putri'),
	(11, 'reza', NULL, '1234567890', 'gunung putri'),
	(12, 'reza', NULL, '1234567890', 'gunung putri'),
	(13, 'reza', NULL, '1234567890', 'gunung putri'),
	(14, 'reza', NULL, '1234567890', 'gunung putri'),
	(15, 'reza', NULL, '1234567890', 'gunung putri'),
	(16, 'reza', NULL, '1234567890', 'gunung putri'),
	(17, 'reza', NULL, '1234567890', 'gunung putri'),
	(18, 'reza', NULL, '1234567890', 'gunung putri'),
	(19, 'reza', NULL, '1234567890', 'gunung putri'),
	(20, 'reza', NULL, '1234567890', 'gunung putri'),
	(21, 'reza', NULL, '1234567890', 'gunung putri'),
	(22, 'reza', NULL, '1234567890', 'gunung putri'),
	(23, 'reza', NULL, '1234567890', 'gunung putri'),
	(24, 'reza', NULL, '1234567890', 'gunung putri'),
	(25, 'reza', NULL, '1234567890', 'gunung putri'),
	(26, 'reza', NULL, '1234567890', 'gunung putri'),
	(27, 'reza', NULL, '1234567890', 'gunung putri'),
	(28, 'reza', NULL, '1234567890', 'gunung putri'),
	(29, 'reza', NULL, '1234567890', 'gunung putri'),
	(30, 'reza', NULL, '1234567890', 'gunung putri'),
	(31, 'reza', NULL, '1234567890', 'gunung putri');

-- Dumping structure for table restoran.pesanan
CREATE TABLE IF NOT EXISTS `pesanan` (
  `idpesanan` int NOT NULL AUTO_INCREMENT,
  `idmenu` int DEFAULT NULL,
  `idpelanggan` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  PRIMARY KEY (`idpesanan`),
  KEY `idmenu` (`idmenu`),
  KEY `idpelanggan` (`idpelanggan`),
  KEY `iduser` (`iduser`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`),
  CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`),
  CONSTRAINT `pesanan_ibfk_3` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.pesanan: ~0 rows (approximately)

-- Dumping structure for table restoran.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `ProdukID` int NOT NULL AUTO_INCREMENT,
  `NamaProduk` varchar(255) NOT NULL,
  `Harga` decimal(10,2) NOT NULL,
  `Stok` int NOT NULL,
  PRIMARY KEY (`ProdukID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.produk: ~0 rows (approximately)

-- Dumping structure for table restoran.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `idtransaksi` int NOT NULL AUTO_INCREMENT,
  `idpesanan` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `bayar` int DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  PRIMARY KEY (`idtransaksi`),
  KEY `idpesanan` (`idpesanan`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`idpesanan`) REFERENCES `pesanan` (`idpesanan`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.transaksi: ~19 rows (approximately)
INSERT INTO `transaksi` (`idtransaksi`, `idpesanan`, `total`, `bayar`, `tanggal`) VALUES
	(4, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(5, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(6, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(7, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(10, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(11, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(12, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(13, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(14, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(15, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(17, NULL, NULL, NULL, '2025-04-16 00:00:00'),
	(18, NULL, 4, NULL, '2025-04-16 09:36:43'),
	(19, NULL, 4, NULL, '2025-04-16 09:37:21'),
	(20, NULL, 4, NULL, '2025-04-16 09:38:13'),
	(21, NULL, 4, NULL, '2025-04-16 09:39:01'),
	(22, NULL, 4, NULL, '2025-04-16 09:39:40'),
	(23, NULL, 4, NULL, '2025-04-16 09:41:15'),
	(24, NULL, 4, NULL, '2025-04-16 09:41:53'),
	(25, NULL, 4, NULL, '2025-04-16 09:42:34');

-- Dumping structure for table restoran.user
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `namauser` varchar(100) DEFAULT NULL,
  `role` enum('admin','kasir','waiter','owner') NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table restoran.user: ~4 rows (approximately)
INSERT INTO `user` (`iduser`, `namauser`, `role`, `password`) VALUES
	(1, 'barnett', 'admin', '$2y$10$pcTMTuVHpguBYFrdWeaQOuUzBoKNO6DUxdjT2x7/BGbR3FufqqJd.'),
	(13, 'nio', 'kasir', '$2y$10$UDzySPWwbpz/WYqt62cC9OPgijF9pmMS5jvBGxlgAM1yW1VP9tsXi'),
	(16, 'nio', 'admin', '$2y$10$FBn5NleeAoZ6nztNzwEgn.DJYKd/i39xZWxh2dbu4.KY6kRZ/H2mK'),
	(17, 'nio', 'admin', '$2y$10$78z/jQLo4VyXenDf701bB.WQVM4IkS.zz/bOXclKnCQXilkuforbS');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
