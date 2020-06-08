-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.19 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5958
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for boilers
CREATE DATABASE IF NOT EXISTS `boilers` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `boilers`;

-- Dumping structure for table boilers.boilers_name
CREATE TABLE IF NOT EXISTS `boilers_name` (
  `id` int NOT NULL AUTO_INCREMENT,
  `number` int DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_boilers_name_work_regime` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table boilers.boilers_name: ~4 rows (approximately)
/*!40000 ALTER TABLE `boilers_name` DISABLE KEYS */;
INSERT INTO `boilers_name` (`id`, `number`, `name`) VALUES
	(167, 0, 'РК-85'),
	(168, 1, 'ТП-150'),
	(169, 2, 'ПК-8'),
	(170, 3, 'Е-120');
/*!40000 ALTER TABLE `boilers_name` ENABLE KEYS */;

-- Dumping structure for table boilers.work_regime
CREATE TABLE IF NOT EXISTS `work_regime` (
  `id` int NOT NULL AUTO_INCREMENT,
  `boiler` int DEFAULT NULL,
  `consumption` int DEFAULT NULL,
  `efficiency` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_regime_boiler_index` (`boiler`),
  CONSTRAINT `FK_work_regime_boilers_name` FOREIGN KEY (`boiler`) REFERENCES `boilers_name` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=741 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table boilers.work_regime: ~23 rows (approximately)
/*!40000 ALTER TABLE `work_regime` DISABLE KEYS */;
INSERT INTO `work_regime` (`id`, `boiler`, `consumption`, `efficiency`) VALUES
	(718, 0, 5000, 50),
	(719, 0, 10000, 56),
	(720, 0, 15000, 61),
	(721, 0, 20000, 65),
	(722, 0, 25000, 68),
	(723, 0, 30000, 71),
	(724, 0, 40000, 75),
	(725, 1, 10000, 57),
	(726, 1, 20000, 68),
	(727, 1, 30000, 72),
	(728, 1, 40000, 75),
	(729, 1, 50000, 69),
	(730, 2, 10000, 65),
	(731, 2, 35000, 73),
	(732, 2, 45000, 70),
	(733, 2, 60000, 68),
	(734, 2, 75000, 65),
	(735, 2, 90000, 61),
	(736, 3, 15000, 62),
	(737, 3, 25000, 66),
	(738, 3, 35000, 72),
	(739, 3, 45000, 74),
	(740, 3, 50000, 73);
/*!40000 ALTER TABLE `work_regime` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
