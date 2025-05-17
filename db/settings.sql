-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.26 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for khemalin_dental_clinic
CREATE DATABASE IF NOT EXISTS `khemalin_dental_clinic` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `khemalin_dental_clinic`;

-- Dumping structure for table khemalin_dental_clinic.tbl_system_setting
CREATE TABLE IF NOT EXISTS `tbl_system_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_name_kh` varchar(150) DEFAULT NULL,
  `system_name_en` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `tax_no` varchar(50) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `remark_invoice` varchar(500) DEFAULT NULL,
  `license_to` varchar(250) DEFAULT NULL,
  `joined_date` date DEFAULT NULL,
  `balance_amount` float DEFAULT NULL,
  `valid_date` varchar(50) DEFAULT NULL,
  `licence_code` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `license_bill_adr` varchar(500) DEFAULT NULL,
  `license_status` int(1) DEFAULT NULL,
  `recuring` int(11) DEFAULT NULL,
  `billing_issue` int(11) DEFAULT NULL COMMENT 'auto, manual',
  `paid_amount` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='system setting';

-- Dumping data for table khemalin_dental_clinic.tbl_system_setting: 1 rows
DELETE FROM `tbl_system_setting`;
/*!40000 ALTER TABLE `tbl_system_setting` DISABLE KEYS */;
INSERT INTO `tbl_system_setting` (`id`, `system_name_kh`, `system_name_en`, `phone`, `email`, `address`, `tax_no`, `logo`, `remark_invoice`, `license_to`, `joined_date`, `balance_amount`, `valid_date`, `licence_code`, `type`, `license_bill_adr`, `license_status`, `recuring`, `billing_issue`, `paid_amount`) VALUES
	(1, 'ពេទ្យធ្មេញ សុជាតា', 'SOCHEATA DENTAL KABINET', '086 608 381/071 9399 989', '', 'ភូមិស្នោរខាងកើត សង្កាត់ស្នោរ ខណ្ឌកំបូល រាជធានីភ្នំពេញ', '', 'logo.png', '*** ទឹកប្រាក់ដែលបានបង់ហើយ មិនអាចដកវិញបានទេ! ធានារយៈពេល ១ ឆ្នាំលើការ​បែកបាក់ ឬប្រេះស្រាំចំពោះករណីស្រោបធ្មេញ! ***', 'Mr. Meung Reaksmey', '2025-04-24', 50000, '2025-04-24', '00000123456', 'Dental Clinic', 'ភូមិស្នោរខាងកើត សង្កាត់ស្នោរ ខណ្ឌកំបូល រាជធានីភ្នំពេញ', 1, 1, 1, 1000);
/*!40000 ALTER TABLE `tbl_system_setting` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
