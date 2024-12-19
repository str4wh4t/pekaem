-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.3.0 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table pkm.anggota_pkm
CREATE TABLE IF NOT EXISTS `anggota_pkm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usulan_pkm_id` int NOT NULL,
  `mhs_nim` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sebagai` tinyint NOT NULL COMMENT '0: ketua 1:anggota',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 4` (`usulan_pkm_id`,`mhs_nim`,`sebagai`),
  KEY `nim` (`mhs_nim`),
  CONSTRAINT `FK_anggota_pkm_usulan_pkm` FOREIGN KEY (`usulan_pkm_id`) REFERENCES `usulan_pkm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.jenis_pkm
CREATE TABLE IF NOT EXISTS `jenis_pkm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pkm` varchar(50) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.pegawai_roles
CREATE TABLE IF NOT EXISTS `pegawai_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint unsigned NOT NULL DEFAULT '0',
  `roles_id` int NOT NULL,
  `status_role` tinyint NOT NULL DEFAULT '0' COMMENT '1 => AKTIF , 0 => TIDAK AKTIF',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 2` (`pegawai_id`,`roles_id`),
  KEY `roles_id` (`roles_id`),
  CONSTRAINT `FK_admin_roles_roles` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.perbaikan
CREATE TABLE IF NOT EXISTS `perbaikan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usulan_pkm_id` int NOT NULL,
  `catatan_perbaikan` tinytext NOT NULL,
  `mhs_nim` char(15) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usulan_pkm_id` (`usulan_pkm_id`),
  KEY `mhs_nim` (`mhs_nim`),
  CONSTRAINT `FK_perbaikan_usulan_pkm` FOREIGN KEY (`usulan_pkm_id`) REFERENCES `usulan_pkm` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.review
CREATE TABLE IF NOT EXISTS `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usulan_pkm_id` int NOT NULL,
  `catatan_reviewer` tinytext NOT NULL,
  `pegawai_id` int NOT NULL,
  `status_usulan_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usulan_pkm_id` (`usulan_pkm_id`),
  KEY `pegawai_id` (`pegawai_id`),
  KEY `status_usulan_id` (`status_usulan_id`),
  CONSTRAINT `FK_review_status_usulan` FOREIGN KEY (`status_usulan_id`) REFERENCES `status_usulan` (`id`),
  CONSTRAINT `FK_review_usulan_pkm` FOREIGN KEY (`usulan_pkm_id`) REFERENCES `usulan_pkm` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='''pkm.reviewer'' is not BASE TABLE';

-- Data exporting was unselected.

-- Dumping structure for table pkm.reviewer_usulan_pkm
CREATE TABLE IF NOT EXISTS `reviewer_usulan_pkm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usulan_pkm_id` int NOT NULL,
  `reviewer_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 4` (`usulan_pkm_id`,`reviewer_id`),
  KEY `usulan_pkm_id` (`usulan_pkm_id`),
  KEY `pegawai_id` (`reviewer_id`),
  CONSTRAINT `FK_usulan_pkm_reviewer_usulan_pkm` FOREIGN KEY (`usulan_pkm_id`) REFERENCES `usulan_pkm` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.revisi
CREATE TABLE IF NOT EXISTS `revisi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usulan_pkm_id` int NOT NULL,
  `catatan_pembimbing` tinytext NOT NULL,
  `pegawai_id` int NOT NULL,
  `status_usulan_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usulan_pkm_id` (`usulan_pkm_id`),
  KEY `pegawai_id` (`pegawai_id`),
  KEY `status_usulan_id` (`status_usulan_id`),
  CONSTRAINT `FK_revisi_status_usulan` FOREIGN KEY (`status_usulan_id`) REFERENCES `status_usulan` (`id`),
  CONSTRAINT `FK_revisi_usulan_pkm` FOREIGN KEY (`usulan_pkm_id`) REFERENCES `usulan_pkm` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL DEFAULT '',
  `keterangan` tinytext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.status_usulan
CREATE TABLE IF NOT EXISTS `status_usulan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `keterangan` varchar(50) NOT NULL,
  `urutan` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table pkm.usulan_pkm
CREATE TABLE IF NOT EXISTS `usulan_pkm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) DEFAULT NULL,
  `mhs_nim` char(15) DEFAULT NULL,
  `judul` tinytext NOT NULL,
  `jenis_pkm_id` int NOT NULL DEFAULT '0',
  `status_usulan_id` int NOT NULL DEFAULT '0',
  `pegawai_id` varchar(50) NOT NULL COMMENT 'Pembimbing',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_usulan_pkm_status_usulan` (`status_usulan_id`),
  KEY `FK_usulan_pkm_jenis_pkm` (`jenis_pkm_id`),
  KEY `pembimbing` (`pegawai_id`),
  KEY `mhs_nim` (`mhs_nim`),
  CONSTRAINT `FK_usulan_pkm_jenis_pkm` FOREIGN KEY (`jenis_pkm_id`) REFERENCES `jenis_pkm` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_usulan_pkm_status_usulan` FOREIGN KEY (`status_usulan_id`) REFERENCES `status_usulan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
