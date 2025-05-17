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

-- Dumping structure for table khemalin_dental_clinic.tbl_appointment
CREATE TABLE IF NOT EXISTS `tbl_appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cust_id` int(11) NOT NULL,
  `appo_datetime` datetime NOT NULL,
  `appo_duration` int(1) NOT NULL,
  `appo_note` mediumtext,
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `appo_repeat` int(1) NOT NULL DEFAULT '0',
  `appo_status` int(1) DEFAULT '0' COMMENT '1: appoitment;2:queue;3:serving;4: issued invoice',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_appointment: ~7 rows (approximately)
DELETE FROM `tbl_appointment`;
/*!40000 ALTER TABLE `tbl_appointment` DISABLE KEYS */;
INSERT INTO `tbl_appointment` (`id`, `timestamp`, `cust_id`, `appo_datetime`, `appo_duration`, `appo_note`, `staff_id`, `user_id`, `appo_repeat`, `appo_status`) VALUES
	(1, '2025-05-02 13:59:28', 1, '2025-04-30 09:00:00', 2, 'test', 2, 1, 0, 1),
	(2, '2025-05-02 13:54:14', 2, '2025-04-30 09:00:00', 2, 'test', 2, 1, 0, 1),
	(3, '2025-05-02 14:00:12', 1, '2025-04-30 09:00:00', 2, '2222', 2, 1, 0, 1),
	(4, '2025-05-02 14:00:42', 1, '2025-04-30 09:00:00', 2, 'adsfdsf', 2, 1, 0, 1),
	(5, '2025-05-02 14:01:15', 2, '2025-04-30 09:00:00', 2, '2', 2, 1, 0, 1),
	(6, '2025-05-04 16:02:46', 1, '2025-05-04 09:00:00', 2, 'test teeee', 2, 1, 0, 3),
	(7, '2025-05-04 15:56:31', 2, '2025-05-04 09:00:00', 2, 'test', 2, 1, 0, 3);
/*!40000 ALTER TABLE `tbl_appointment` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_background_image
CREATE TABLE IF NOT EXISTS `tbl_background_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_image` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_background_image: ~6 rows (approximately)
DELETE FROM `tbl_background_image`;
/*!40000 ALTER TABLE `tbl_background_image` DISABLE KEYS */;
INSERT INTO `tbl_background_image` (`id`, `file_image`) VALUES
	(7, '212606201120231.jpg'),
	(8, '262606201120232.jpg'),
	(10, '372606201120234.jpg'),
	(13, '522606201120237.jpg'),
	(14, '562606201120238.jpg'),
	(15, '012706201120239.jpg');
/*!40000 ALTER TABLE `tbl_background_image` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_clinical_note
CREATE TABLE IF NOT EXISTS `tbl_clinical_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cust_id` int(11) NOT NULL,
  `tooth_id` varchar(250) DEFAULT NULL,
  `clinical_note` mediumtext NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_clinical_note: ~1 rows (approximately)
DELETE FROM `tbl_clinical_note`;
/*!40000 ALTER TABLE `tbl_clinical_note` DISABLE KEYS */;
INSERT INTO `tbl_clinical_note` (`id`, `timestamp`, `cust_id`, `tooth_id`, `clinical_note`, `user_id`) VALUES
	(1, '2025-05-08 10:27:00', 1, '1', '<p>1: RCT :&nbsp;</p><p>2: Test តេសត</p>', 2);
/*!40000 ALTER TABLE `tbl_clinical_note` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_customer
CREATE TABLE IF NOT EXISTS `tbl_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cust_code` varchar(8) NOT NULL,
  `cust_fname` varchar(50) NOT NULL,
  `cust_gender` int(1) NOT NULL,
  `cust_dob` date NOT NULL,
  `cust_contact` varchar(25) DEFAULT NULL,
  `cust_email` varchar(250) DEFAULT NULL,
  `cust_address` varchar(250) DEFAULT NULL,
  `cust_image` varchar(50) NOT NULL,
  `memb_id` int(3) NOT NULL,
  `med_history` varchar(50) NOT NULL,
  `user_id` int(3) NOT NULL,
  `dentist_id` int(11) NOT NULL DEFAULT '2',
  `cust_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_customer: ~2 rows (approximately)
DELETE FROM `tbl_customer`;
/*!40000 ALTER TABLE `tbl_customer` DISABLE KEYS */;
INSERT INTO `tbl_customer` (`id`, `timestamp`, `cust_code`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_email`, `cust_address`, `cust_image`, `memb_id`, `med_history`, `user_id`, `dentist_id`, `cust_status`) VALUES
	(1, '2025-04-28 10:02:00', '44ChN1Qw', 'Sin Vuthy', 1, '1998-01-01', '  ', 'vuthy.sin5284@gmail.com', '#55', '0', 3, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', 1, 2, 1),
	(2, '2025-05-02 13:54:36', '222', 'test test', 2, '2025-05-02', NULL, NULL, NULL, '0', 3, '0', 1, 2, 1);
/*!40000 ALTER TABLE `tbl_customer` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_diagnosis
CREATE TABLE IF NOT EXISTS `tbl_diagnosis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cust_id` int(11) NOT NULL,
  `pres_code` varchar(50) NOT NULL,
  `pres_diagnosis` text,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_diagnosis: ~5 rows (approximately)
DELETE FROM `tbl_diagnosis`;
/*!40000 ALTER TABLE `tbl_diagnosis` DISABLE KEYS */;
INSERT INTO `tbl_diagnosis` (`id`, `timestamp`, `cust_id`, `pres_code`, `pres_diagnosis`, `user_id`) VALUES
	(1, '2025-05-06 10:35:22', 1, '1', '111', 1),
	(2, '2025-05-06 12:54:10', 0, '', '', 1),
	(3, '2025-05-06 12:57:29', 0, '', '', 1),
	(4, '2025-05-06 13:17:42', 0, '', '', 1),
	(5, '2025-05-06 13:19:21', 0, '', '', 1);
/*!40000 ALTER TABLE `tbl_diagnosis` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_expense_category
CREATE TABLE IF NOT EXISTS `tbl_expense_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_category` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_expense_category: ~4 rows (approximately)
DELETE FROM `tbl_expense_category`;
/*!40000 ALTER TABLE `tbl_expense_category` DISABLE KEYS */;
INSERT INTO `tbl_expense_category` (`id`, `expense_category`) VALUES
	(1, 'Staff Salary'),
	(2, 'Dental Lab'),
	(3, 'Operations & Services'),
	(4, 'Instrument & Material');
/*!40000 ALTER TABLE `tbl_expense_category` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_expense_image
CREATE TABLE IF NOT EXISTS `tbl_expense_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exp_image` text NOT NULL,
  `exp_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_expense_image: ~0 rows (approximately)
DELETE FROM `tbl_expense_image`;
/*!40000 ALTER TABLE `tbl_expense_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_expense_image` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_expense_payment
CREATE TABLE IF NOT EXISTS `tbl_expense_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `exp_id` int(11) NOT NULL,
  `exp_payment_amount` float NOT NULL,
  `paym_id` int(11) NOT NULL,
  `payment_note` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_expense_payment: ~1 rows (approximately)
DELETE FROM `tbl_expense_payment`;
/*!40000 ALTER TABLE `tbl_expense_payment` DISABLE KEYS */;
INSERT INTO `tbl_expense_payment` (`id`, `timestamp`, `exp_id`, `exp_payment_amount`, `paym_id`, `payment_note`, `user_id`) VALUES
	(1, '2025-05-03 14:20:33', 1, 500, 2, 'test', 1);
/*!40000 ALTER TABLE `tbl_expense_payment` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_files_category
CREATE TABLE IF NOT EXISTS `tbl_files_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `files_description` varchar(250) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `files_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_files_category: ~0 rows (approximately)
DELETE FROM `tbl_files_category`;
/*!40000 ALTER TABLE `tbl_files_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_files_category` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_files_image
CREATE TABLE IF NOT EXISTS `tbl_files_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `files_image` varchar(250) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `files_categ_id` int(11) NOT NULL,
  `files_image_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_files_image: ~0 rows (approximately)
DELETE FROM `tbl_files_image`;
/*!40000 ALTER TABLE `tbl_files_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_files_image` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_followup
CREATE TABLE IF NOT EXISTS `tbl_followup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cust_id` int(11) DEFAULT NULL,
  `last_appointment` datetime DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `fup_note` mediumtext,
  `next_appointment` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fup_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_followup: ~0 rows (approximately)
DELETE FROM `tbl_followup`;
/*!40000 ALTER TABLE `tbl_followup` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_followup` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_invoice_expense
CREATE TABLE IF NOT EXISTS `tbl_invoice_expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `exp_code` varchar(11) NOT NULL,
  `exp_description` text,
  `exp_cate_id` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `exp_amount` float NOT NULL,
  `exp_remain` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `exp_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_invoice_expense: ~1 rows (approximately)
DELETE FROM `tbl_invoice_expense`;
/*!40000 ALTER TABLE `tbl_invoice_expense` DISABLE KEYS */;
INSERT INTO `tbl_invoice_expense` (`id`, `timestamp`, `exp_code`, `exp_description`, `exp_cate_id`, `supp_id`, `exp_amount`, `exp_remain`, `user_id`, `exp_status`) VALUES
	(1, '2025-05-03 14:20:33', 'jAz0zpHf', 'Putty Silicone', 3, 3, 1111, 611, 1, 1);
/*!40000 ALTER TABLE `tbl_invoice_expense` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_invoice_patient
CREATE TABLE IF NOT EXISTS `tbl_invoice_patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inv_code` varchar(50) DEFAULT NULL,
  `inv_title` varchar(250) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `inv_discount` float DEFAULT NULL,
  `inv_discount_type` int(1) DEFAULT NULL,
  `inv_grandtotal` float DEFAULT NULL,
  `inv_remain` float DEFAULT NULL,
  `change_en` float DEFAULT NULL,
  `change_kh` float DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `share_amount` float DEFAULT NULL,
  `share_status` int(1) DEFAULT NULL,
  `inv_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_invoice_patient: ~18 rows (approximately)
DELETE FROM `tbl_invoice_patient`;
/*!40000 ALTER TABLE `tbl_invoice_patient` DISABLE KEYS */;
INSERT INTO `tbl_invoice_patient` (`id`, `timestamp`, `inv_code`, `inv_title`, `cust_id`, `inv_discount`, `inv_discount_type`, `inv_grandtotal`, `inv_remain`, `change_en`, `change_kh`, `staff_id`, `user_id`, `share_amount`, `share_status`, `inv_status`) VALUES
	(1, '2025-04-28 10:37:44', 'UttDW3G9', '2025-0001', 1, 0, 0, 150, 0, NULL, NULL, 1, 2, NULL, 0, 3),
	(2, '2025-04-28 11:23:35', 'vCH0un2b', '2025-0002', 1, 0, 0, 250, 0, NULL, NULL, 1, 2, NULL, 0, 3),
	(3, '2025-05-02 09:15:50', 'erxO1jTh', '2025-0003', 1, 0, 0, 150, 0, NULL, NULL, 1, 2, NULL, 0, 3),
	(4, '2025-05-02 09:56:47', 'a7ckc0hj', '2025-0004', 1, 0, 0, 1410, 910, NULL, 720000, 1, 2, NULL, 0, 2),
	(5, '2025-04-28 11:21:41', 'JBzF5Fg4', '2025-0005', 1, 0, 0, 180, 0, NULL, 720000, 1, 2, NULL, 0, 3),
	(6, '2025-05-02 09:02:37', 'gHogxfX1', '2025-0006', 1, 0, 0, 225, 0, NULL, 900000, 1, 2, NULL, 0, 3),
	(7, '2025-05-04 16:04:15', 'Kh7WrGr1', '2025-0007', 1, 0, 0, 150, 150, NULL, 600000, 1, 2, NULL, 0, 0),
	(8, '2025-05-04 16:04:16', 'dP6rHSW1', '2025-0008', 1, 0, 0, 150, 150, NULL, 600000, 1, 1, NULL, 0, 0),
	(9, '2025-05-04 16:04:18', 'NbE2tCEJ', '2025-0009', 1, 0, 0, 405, 405, NULL, 1620000, 1, 1, NULL, 0, 0),
	(10, '2025-05-04 16:04:19', 'aEsZLOTU', '2025-0010', 1, 0, 0, 405, 405, NULL, 1620000, 1, 1, NULL, 0, 0),
	(11, '2025-05-04 16:04:21', 'MNhQgVdx', '2025-0011', 1, 0, 0, 405, 405, NULL, 1620000, 1, 1, NULL, 0, 0),
	(12, '2025-05-04 15:33:35', 'GTbl0tHz', '2025-0012', 1, 0, 0, 150, 0, NULL, 600000, 1, 1, NULL, 0, 3),
	(13, '2025-05-04 16:04:23', 'WPYX27cW', '2025-0013', 2, 0, 0, 150, 150, NULL, 600000, 1, 1, NULL, 0, 0),
	(14, '2025-05-04 15:57:03', 'xHtouSy0', '2025-0014', 2, 0, 0, 175, 175, NULL, 700000, 1, 1, NULL, 0, 2),
	(15, '2025-05-06 14:37:01', 'RuN2cdMi', '2025-0015', 1, 0, 0, 300, 100, NULL, 1200000, 1, 1, NULL, 0, 2),
	(16, '2025-05-07 23:26:55', 'ZDHVdGrr', '2025-0016', 2, 0, 0, 555, 0, NULL, 2220000, 1, 1, NULL, 0, 3),
	(17, '2025-05-07 23:22:28', '2n6fyLAh', '2025-0017', 2, 0, 0, 150, 0, NULL, 600000, 1, 1, NULL, 0, 3),
	(18, '2025-05-08 21:08:17', '9pa46dGU', '2025-0018', 1, 0, 0, 150, 100, NULL, 600000, 1, 1, NULL, 0, 2);
/*!40000 ALTER TABLE `tbl_invoice_patient` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_invoice_payment
CREATE TABLE IF NOT EXISTS `tbl_invoice_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inv_id` int(11) NOT NULL,
  `code_number` varchar(10) NOT NULL DEFAULT '',
  `amount_en` float NOT NULL,
  `amount_kh` float NOT NULL,
  `change_en` float NOT NULL,
  `change_kh` float NOT NULL,
  `payment_amount` float NOT NULL,
  `change_amount` float NOT NULL,
  `paym_id` int(11) NOT NULL,
  `payment_note` varchar(250) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `is_received` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `code_number` (`code_number`),
  KEY `inv_id` (`inv_id`),
  KEY `user_id` (`user_id`),
  KEY `paym_id` (`paym_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_invoice_payment: ~19 rows (approximately)
DELETE FROM `tbl_invoice_payment`;
/*!40000 ALTER TABLE `tbl_invoice_payment` DISABLE KEYS */;
INSERT INTO `tbl_invoice_payment` (`id`, `timestamp`, `inv_id`, `code_number`, `amount_en`, `amount_kh`, `change_en`, `change_kh`, `payment_amount`, `change_amount`, `paym_id`, `payment_note`, `user_id`, `is_received`) VALUES
	(1, '2025-05-05 23:14:53', 1, 'P-00001', 0, 0, 0, 0, 0, 0, 2, '', 1, 1),
	(2, '2025-05-05 21:53:39', 1, '', 150, 0, 0, 0, 150, 0, 2, '', 1, 1),
	(3, '2025-04-28 10:40:00', 1, '', 100, 0, 0, 0, 0, 100, 2, '', 1, 0),
	(4, '2025-05-05 21:53:39', 5, '', 180, 0, 0, 0, 180, 0, 2, '', 1, 1),
	(5, '2025-05-05 21:53:39', 2, '', 250, 0, 0, 0, 250, 0, 2, '', 1, 1),
	(6, '2025-05-06 13:57:30', 2, '', 10, 0, 10, 40, 0, 10, 2, '', 1, 1),
	(7, '2025-05-06 13:57:30', 2, '', 10, 0, 10, 40, 0, 10, 3, '', 1, 1),
	(8, '2025-05-06 13:57:30', 2, '', 10, 0, 10, 0, 0, 10, 2, '', 1, 1),
	(9, '2025-05-06 13:57:30', 2, '', 10, 0, 10, 40000, 0, 10, 2, '', 1, 1),
	(10, '2025-05-05 21:53:39', 6, '', 225, 0, 0, 0, 225, 0, 2, '', 1, 1),
	(11, '2025-05-05 23:14:53', 6, '', 0, 0, 0, 0, 0, 0, 2, '', 1, 1),
	(12, '2025-05-05 23:10:10', 3, 'P-00001', 100, 0, -50, -200000, 100, 0, 2, '', 1, 1),
	(13, '2025-05-05 21:33:01', 3, '', 50, 0, 0, 0, 50, 0, 2, '', 1, 1),
	(14, '2025-05-05 23:14:53', 4, 'P-00001', 500, 0, -910, -3640000, 500, 0, 2, '', 1, 1),
	(15, '2025-05-05 23:05:44', 12, 'P-00001', 150, 0, 0, 0, 150, 0, 2, '', 1, 1),
	(16, '2025-05-06 14:36:00', 15, '', 200, 0, -100, -400000, 200, 0, 2, '', 1, 0),
	(17, '2025-05-07 23:22:00', 17, '', 150, 0, 0, 0, 150, 0, 2, '', 1, 0),
	(18, '2025-05-07 23:26:00', 16, '', 555, 0, 0, 0, 555, 0, 2, '', 1, 0),
	(19, '2025-05-08 21:08:00', 18, '', 50, 0, -100, -400000, 50, 0, 2, '', 1, 0);
/*!40000 ALTER TABLE `tbl_invoice_payment` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_invoice_treatment
CREATE TABLE IF NOT EXISTS `tbl_invoice_treatment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `inv_id` int(111) NOT NULL,
  `tmsv_id` int(11) NOT NULL,
  `tmt_price` float NOT NULL,
  `tooth_qty` int(11) NOT NULL,
  `tooth_id` mediumtext NOT NULL,
  `tmt_discount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_invoice_treatment: ~37 rows (approximately)
DELETE FROM `tbl_invoice_treatment`;
/*!40000 ALTER TABLE `tbl_invoice_treatment` DISABLE KEYS */;
INSERT INTO `tbl_invoice_treatment` (`id`, `timestamp`, `inv_id`, `tmsv_id`, `tmt_price`, `tooth_qty`, `tooth_id`, `tmt_discount`) VALUES
	(1, '2025-04-28 10:03:01', 1, 35, 150, 1, '#11', 0),
	(2, '2025-04-28 10:49:38', 2, 31, 250, 1, '#14', 0),
	(3, '2025-04-28 10:53:46', 3, 35, 150, 1, '#13', 0),
	(4, '2025-04-28 11:02:46', 4, 23, 180, 1, '#14', 0),
	(5, '2025-04-28 11:06:31', 4, 13, 150, 1, '#12', 0),
	(6, '2025-04-28 11:07:35', 4, 23, 180, 1, '#15', 0),
	(7, '2025-04-28 11:13:27', 4, 23, 180, 1, '#16', 0),
	(8, '2025-04-28 11:17:12', 4, 23, 180, 1, '#16', 0),
	(9, '2025-04-28 11:17:59', 4, 23, 180, 1, '#16', 0),
	(10, '2025-04-28 11:19:05', 4, 23, 180, 1, '#16', 0),
	(11, '2025-04-28 11:20:05', 4, 23, 180, 1, '#16', 0),
	(12, '2025-04-28 11:20:38', 5, 23, 180, 1, '#16', 0),
	(13, '2025-05-02 09:02:10', 6, 35, 150, 1, '#11', 0),
	(14, '2025-05-02 09:02:10', 6, 34, 75, 1, '#12', 0),
	(15, '2025-05-04 12:45:35', 7, 35, 150, 1, '#12', 0),
	(16, '2025-05-04 13:01:44', 8, 35, 150, 1, '#12', 0),
	(17, '2025-05-04 13:06:22', 9, 35, 150, 1, '#11', 0),
	(18, '2025-05-04 13:06:22', 9, 34, 75, 1, '#12', 0),
	(19, '2025-05-04 13:06:22', 9, 23, 180, 1, '#11', 0),
	(20, '2025-05-04 13:09:16', 10, 35, 150, 1, '#11', 0),
	(21, '2025-05-04 13:09:16', 10, 34, 75, 1, '#12', 0),
	(22, '2025-05-04 13:09:16', 10, 23, 180, 1, '#11', 0),
	(23, '2025-05-04 13:09:58', 11, 35, 150, 1, '#11', 0),
	(24, '2025-05-04 13:09:58', 11, 34, 75, 1, '#12', 0),
	(25, '2025-05-04 13:09:58', 11, 23, 180, 1, '#11', 0),
	(26, '2025-05-04 13:25:11', 12, 35, 150, 1, '#12', 0),
	(27, '2025-05-04 15:24:12', 13, 35, 150, 1, '#11', 0),
	(28, '2025-05-04 15:57:03', 14, 35, 150, 1, '#11', 0),
	(29, '2025-05-04 15:57:03', 18, 26, 25, 1, '#11', 0),
	(30, '2025-05-04 16:03:01', 18, 35, 150, 1, '#11', 0),
	(31, '2025-05-04 16:03:01', 18, 13, 150, 1, '#11', 0),
	(32, '2025-05-07 23:20:34', 18, 35, 150, 1, '#11', 0),
	(33, '2025-05-07 23:20:34', 18, 34, 75, 1, '#12', 0),
	(34, '2025-05-07 23:20:34', 18, 23, 180, 1, '#13', 0),
	(35, '2025-05-07 23:20:34', 18, 13, 150, 1, '#14', 0),
	(36, '2025-05-07 23:21:48', 18, 35, 150, 1, '#11', 0),
	(37, '2025-05-08 13:52:11', 18, 35, 150, 1, '#11', 0);
/*!40000 ALTER TABLE `tbl_invoice_treatment` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_measurement
CREATE TABLE IF NOT EXISTS `tbl_measurement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `measure_eng` varchar(50) NOT NULL,
  `measure_kh` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_measurement: ~5 rows (approximately)
DELETE FROM `tbl_measurement`;
/*!40000 ALTER TABLE `tbl_measurement` DISABLE KEYS */;
INSERT INTO `tbl_measurement` (`id`, `measure_eng`, `measure_kh`) VALUES
	(1, 'Solution', 'ដប'),
	(2, 'Gel', 'ដប'),
	(3, 'Sachet', 'កញ្ចប់'),
	(4, 'Tablet', 'គ្រាប់'),
	(6, 'Capsule', 'គ្រាប់');
/*!40000 ALTER TABLE `tbl_measurement` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_medical_history
CREATE TABLE IF NOT EXISTS `tbl_medical_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medical_history` text NOT NULL,
  `med_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_medical_history: ~15 rows (approximately)
DELETE FROM `tbl_medical_history`;
/*!40000 ALTER TABLE `tbl_medical_history` DISABLE KEYS */;
INSERT INTO `tbl_medical_history` (`id`, `medical_history`, `med_status`) VALUES
	(1, 'អាការៈប្រតិកម្ម (Allergies)', 0),
	(2, 'ជំងឺបេះដូង (Heart Diseases)', 0),
	(3, 'បញ្ហាសម្ពាធឈាម (Blood Pressure Problems)', 0),
	(4, 'បញ្ហាហូរឈាមយូរ ឬជំងឺឈាមក្រកក (Hemophilia or Blood Diseases)', 0),
	(5, 'បញ្ហាថ្លើម ឬជំងឺរលាកថ្លើម (Hepatitis or Liver Diseases)', 0),
	(6, 'បញ្ហាដំណកដង្ហើម បញ្ហាសួត ឬក្អកជាប្រចាំ (Pulmonary Disease)', 0),
	(7, 'បញ្ហាតម្រងនោម ឬបត់ជើងតូចមិនប្រក្រតី (Kidney Diseases)', 0),
	(8, 'ជំងឺអេដស៍ ឬវីរុសហ៊ីវ (HIV or AIDS)', 0),
	(9, 'ជំងឺប្រកាច់ ឬជំងឺឆ្កួតជ្រូក (Seizure or Epilepsy)', 0),
	(10, 'ជំងឺមហារីក (Cancers)', 0),
	(11, 'ជំងឺក្រពះ (Gastric Diseases)', 0),
	(12, 'ជំងឺសន្លាក់ (Arthritis Diseases)', 0),
	(13, 'អាការៈក្ដៅខ្លួន ឈឺបំពង់ក និងកន្ទួលក្រហមលើស្បែក (Rheumatic Fever)', 0),
	(14, 'បញ្ហាសុខភាពផ្លូវចិត្ត (Mental Health Problems)', 0),
	(15, 'មានផ្ទៃពោះ ឬទើបសម្រាលបុត្ររួច (Pregnancy or Post Maternity)', 0);
/*!40000 ALTER TABLE `tbl_medical_history` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_membership
CREATE TABLE IF NOT EXISTS `tbl_membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memb_type` varchar(250) NOT NULL,
  `memb_discount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_membership: ~3 rows (approximately)
DELETE FROM `tbl_membership`;
/*!40000 ALTER TABLE `tbl_membership` DISABLE KEYS */;
INSERT INTO `tbl_membership` (`id`, `memb_type`, `memb_discount`) VALUES
	(1, 'Diamond Member', 20),
	(2, 'Gold Member', 10),
	(3, 'Silver Member', 0);
/*!40000 ALTER TABLE `tbl_membership` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_menu
CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(15) NOT NULL,
  `menu_kh` varchar(14) NOT NULL,
  `menu_icon` varchar(16) NOT NULL,
  `menu_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_menu: ~10 rows (approximately)
DELETE FROM `tbl_menu`;
/*!40000 ALTER TABLE `tbl_menu` DISABLE KEYS */;
INSERT INTO `tbl_menu` (`id`, `menu_name`, `menu_kh`, `menu_icon`, `menu_order`) VALUES
	(1, 'Dashboard', 'ផ្ទាំងពត៌មាន', 'bx bxs-dashboard', 1),
	(2, 'Appointments', 'ការរំលឹក', 'bx bx-calendar', 2),
	(3, 'Patients', 'អតិថិជន', 'bx bx-walk', 3),
	(4, 'Revenues', 'វិក្កយបត្រ', 'bx bx-receipt', 4),
	(5, 'Expenses', 'ការចំណាយ', 'bx bx-wallet', 5),
	(6, 'Inventory', 'គ្រប់គ្រងស្តុក', 'bx bx-store', 6),
	(7, 'Employee', 'បុគ្គលិក', 'bx bx-user', 7),
	(8, 'Report', 'របាយការណ៍', 'bx bx-bar-chart', 8),
	(9, 'Deleted History', 'ប្រវត្តិការលុប', 'bx bx-history', 9),
	(10, 'Setting', 'ការកំណត់', 'bx bx-cog', 10);
/*!40000 ALTER TABLE `tbl_menu` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_notification
CREATE TABLE IF NOT EXISTS `tbl_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cust_id` varchar(11) NOT NULL,
  `notify_id` int(2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notify_status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_notification: ~2 rows (approximately)
DELETE FROM `tbl_notification`;
/*!40000 ALTER TABLE `tbl_notification` DISABLE KEYS */;
INSERT INTO `tbl_notification` (`id`, `timestamp`, `cust_id`, `notify_id`, `user_id`, `notify_status`) VALUES
	(1, '2025-05-04 16:03:01', '1', 4, 1, 1),
	(2, '2025-05-02 13:56:14', '2', 4, 1, 1);
/*!40000 ALTER TABLE `tbl_notification` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_payment_method
CREATE TABLE IF NOT EXISTS `tbl_payment_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method_image` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_payment_method: ~3 rows (approximately)
DELETE FROM `tbl_payment_method`;
/*!40000 ALTER TABLE `tbl_payment_method` DISABLE KEYS */;
INSERT INTO `tbl_payment_method` (`id`, `method_image`, `payment_method`) VALUES
	(1, '27521120102023', 'Cash'),
	(2, '35521120102023', 'ABA Bank'),
	(3, '38541120102023', 'ACELEDA Bank');
/*!40000 ALTER TABLE `tbl_payment_method` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_prescription
CREATE TABLE IF NOT EXISTS `tbl_prescription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pres_id` int(11) NOT NULL,
  `pres_medicine` varchar(250) NOT NULL,
  `pres_cate_id` int(11) NOT NULL,
  `pres_m` int(11) NOT NULL,
  `pres_a` int(11) NOT NULL,
  `pres_e` int(11) NOT NULL,
  `pres_duration` int(11) NOT NULL,
  `pres_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_prescription: ~9 rows (approximately)
DELETE FROM `tbl_prescription`;
/*!40000 ALTER TABLE `tbl_prescription` DISABLE KEYS */;
INSERT INTO `tbl_prescription` (`id`, `pres_id`, `pres_medicine`, `pres_cate_id`, `pres_m`, `pres_a`, `pres_e`, `pres_duration`, `pres_status`) VALUES
	(1, 1, 'Augmentin 625mg', 4, 1, 1, 1, 5, 0),
	(2, 1, 'Ibuprofen 400mg', 4, 1, 1, 1, 5, 0),
	(3, 1, 'Doliprane 500mg', 4, 1, 1, 1, 5, 0),
	(4, 1, 'Septyl', 1, 1, 1, 1, 5, 0),
	(5, 7, 'ញុាំថ្នាំ', 4, 1, 1, 1, 5, 0),
	(6, 7, 'ញុាំថ្នាំ', 4, 1, 1, 1, 5, 1),
	(7, 7, 'ញុាំថ្នាំ', 4, 1, 1, 1, 5, 0),
	(8, 7, 'ញុាំថ្នាំ', 4, 1, 1, 1, 5, 0),
	(9, 7, 'ញុាំថ្នាំ', 6, 1, 1, 1, 5, 0);
/*!40000 ALTER TABLE `tbl_prescription` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_product
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `prod_code` varchar(15) NOT NULL,
  `prod_description` varchar(250) NOT NULL,
  `prod_cate_id` int(11) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `prod_unit_cost` float NOT NULL,
  `prod_image` varchar(50) NOT NULL,
  `prod_min_qty` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prod_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_product: ~2 rows (approximately)
DELETE FROM `tbl_product`;
/*!40000 ALTER TABLE `tbl_product` DISABLE KEYS */;
INSERT INTO `tbl_product` (`id`, `timestamp`, `prod_code`, `prod_description`, `prod_cate_id`, `prod_qty`, `prod_unit_cost`, `prod_image`, `prod_min_qty`, `supp_id`, `user_id`, `prod_status`) VALUES
	(1, '2025-05-04 12:44:06', 'vlGwzh36', 'Putty Silicone', 5, 2, 2000, '0', 2, 1, 1, 1),
	(2, '2025-05-08 12:07:31', 'RIqKQDEY', '2222222222222', 5, 2, 2, '0', 2, 4, 1, 0);
/*!40000 ALTER TABLE `tbl_product` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_product_category
CREATE TABLE IF NOT EXISTS `tbl_product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_category` varchar(150) DEFAULT NULL,
  `type_name` varchar(150) DEFAULT NULL,
  `num_code` varchar(80) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_product_category: ~15 rows (approximately)
DELETE FROM `tbl_product_category`;
/*!40000 ALTER TABLE `tbl_product_category` DISABLE KEYS */;
INSERT INTO `tbl_product_category` (`id`, `prod_category`, `type_name`, `num_code`, `status`) VALUES
	(1, 'Impression', 'Product', NULL, 1),
	(2, 'Luting Cement', 'Product', NULL, 1),
	(3, 'Orthodontics', 'Product', NULL, 1),
	(5, 'Endodontic', 'Product', NULL, 1),
	(6, 'Material', 'Product', NULL, 1),
	(7, 'Staff Salary', 'Expense', NULL, 1),
	(8, 'Dental Lab', 'Expense', NULL, 1),
	(9, 'Operations & Services', 'Expense', NULL, 1),
	(10, 'Instrument & Material', 'Expense', NULL, 1),
	(11, 'Orthodontics', 'Service', NULL, 1),
	(12, 'Dental Implant', 'Service', NULL, 1),
	(13, 'Restoration', 'Service', NULL, 1),
	(14, 'Minor Surgery', 'Service', NULL, 1),
	(15, 'General', 'Service', NULL, 1),
	(16, 'Dental Lab', 'Service', NULL, 1);
/*!40000 ALTER TABLE `tbl_product_category` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_queue
CREATE TABLE IF NOT EXISTS `tbl_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `appo_id` int(11) DEFAULT NULL,
  `cust_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `queue_duration` int(11) DEFAULT NULL,
  `queue_note` mediumtext,
  `queue_status` int(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_queue: ~2 rows (approximately)
DELETE FROM `tbl_queue`;
/*!40000 ALTER TABLE `tbl_queue` DISABLE KEYS */;
INSERT INTO `tbl_queue` (`id`, `timestamp`, `appo_id`, `cust_id`, `staff_id`, `queue_duration`, `queue_note`, `queue_status`, `user_id`) VALUES
	(1, '2025-05-04 13:48:05', 1, 1, 1, 1, NULL, 2, 1),
	(2, '2025-05-02 16:26:48', 1, 1, 2, 2, 'test', 2, 1);
/*!40000 ALTER TABLE `tbl_queue` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_receipt_payment_h
CREATE TABLE IF NOT EXISTS `tbl_receipt_payment_h` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date DEFAULT NULL,
  `post_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT '0',
  `total_amount` float DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `count_invoice` int(11) DEFAULT '0',
  `remark` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table khemalin_dental_clinic.tbl_receipt_payment_h: 2 rows
DELETE FROM `tbl_receipt_payment_h`;
/*!40000 ALTER TABLE `tbl_receipt_payment_h` DISABLE KEYS */;
INSERT INTO `tbl_receipt_payment_h` (`id`, `entry_date`, `post_date`, `created_date`, `created_by`, `total_amount`, `status`, `count_invoice`, `remark`) VALUES
	(1, '2025-05-05', '2025-05-05', '2025-05-05 23:14:53', 1, 500, 1, 3, ''),
	(2, '2025-05-06', '2025-05-06', '2025-05-06 13:57:30', 1, 0, 1, 4, 'rrrr');
/*!40000 ALTER TABLE `tbl_receipt_payment_h` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_receipt_payment_item
CREATE TABLE IF NOT EXISTS `tbl_receipt_payment_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) DEFAULT '0',
  `inv_id` int(11) DEFAULT NULL,
  `inv_code` varchar(50) DEFAULT NULL,
  `paid_code` varchar(50) DEFAULT NULL COMMENT 'payment id',
  `cust_id` int(11) DEFAULT '1',
  `inv_date` date DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `method_id` int(11) DEFAULT '1',
  `payment_id` int(11) DEFAULT '1',
  `created_inv_by` int(11) DEFAULT '1',
  `doctor_id` int(11) DEFAULT '1',
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `receipt_id` (`receipt_id`),
  KEY `inv_id` (`inv_id`),
  KEY `payment_id` (`payment_id`),
  KEY `method_id` (`method_id`),
  KEY `inv_code` (`inv_code`),
  KEY `cust_id` (`cust_id`),
  KEY `created_by` (`created_inv_by`) USING BTREE,
  KEY `doctor_id` (`doctor_id`),
  KEY `paid_code` (`paid_code`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED;

-- Dumping data for table khemalin_dental_clinic.tbl_receipt_payment_item: 7 rows
DELETE FROM `tbl_receipt_payment_item`;
/*!40000 ALTER TABLE `tbl_receipt_payment_item` DISABLE KEYS */;
INSERT INTO `tbl_receipt_payment_item` (`id`, `receipt_id`, `inv_id`, `inv_code`, `paid_code`, `cust_id`, `inv_date`, `amount`, `method_id`, `payment_id`, `created_inv_by`, `doctor_id`, `status`) VALUES
	(1, 1, 6, '2025-0006', '2025-0006', 1, '2025-05-02', 0, 2, 11, 2, 1, 1),
	(2, 1, 4, '2025-0004', '2025-0004', 1, '2025-05-02', 500, 2, 14, 2, 1, 1),
	(3, 1, 1, '2025-0001', '2025-0001', 1, '2025-04-28', 0, 2, 1, 2, 1, 1),
	(4, 2, 2, '2025-0002', '', 1, '2025-04-28', 0, 3, 7, 2, 1, 1),
	(5, 2, 2, '2025-0002', '', 1, '2025-04-28', 0, 2, 6, 2, 1, 1),
	(6, 2, 2, '2025-0002', '', 1, '2025-04-28', 0, 2, 9, 2, 1, 1),
	(7, 2, 2, '2025-0002', '', 1, '2025-04-28', 0, 2, 8, 2, 1, 1);
/*!40000 ALTER TABLE `tbl_receipt_payment_item` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_serving
CREATE TABLE IF NOT EXISTS `tbl_serving` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `queue_id` int(11) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `serve_note` mediumtext,
  `serve_status` int(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_serving: ~2 rows (approximately)
DELETE FROM `tbl_serving`;
/*!40000 ALTER TABLE `tbl_serving` DISABLE KEYS */;
INSERT INTO `tbl_serving` (`id`, `timestamp`, `queue_id`, `cust_id`, `staff_id`, `room_id`, `serve_note`, `serve_status`, `user_id`) VALUES
	(1, '2025-05-02 16:26:48', 2, 1, 2, NULL, 'test', 1, 1),
	(2, '2025-05-04 13:48:05', 1, 1, 1, NULL, '', 1, 1);
/*!40000 ALTER TABLE `tbl_serving` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_staff
CREATE TABLE IF NOT EXISTS `tbl_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `staff_code` varchar(25) NOT NULL,
  `staff_fname` varchar(50) NOT NULL,
  `staff_gender` int(1) NOT NULL,
  `staff_dob` date NOT NULL,
  `staff_contact` varchar(25) NOT NULL,
  `staff_address` varchar(250) NOT NULL,
  `staff_image` varchar(50) NOT NULL,
  `staff_position_id` int(11) NOT NULL,
  `staff_salary` float NOT NULL,
  `staff_commission` float NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` mediumtext NOT NULL,
  `user_permission` mediumtext NOT NULL,
  `user_add_perm` mediumtext NOT NULL,
  `user_edit_perm` mediumtext NOT NULL,
  `user_delete_perm` mediumtext NOT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `user_lang` int(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `system_id` varchar(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT 'id of owner system or license to',
  `staff_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `system_id` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_staff: ~2 rows (approximately)
DELETE FROM `tbl_staff`;
/*!40000 ALTER TABLE `tbl_staff` DISABLE KEYS */;
INSERT INTO `tbl_staff` (`id`, `timestamp`, `staff_code`, `staff_fname`, `staff_gender`, `staff_dob`, `staff_contact`, `staff_address`, `staff_image`, `staff_position_id`, `staff_salary`, `staff_commission`, `username`, `password`, `user_permission`, `user_add_perm`, `user_edit_perm`, `user_delete_perm`, `rating`, `user_lang`, `user_id`, `system_id`, `parent_id`, `staff_status`) VALUES
	(1, '2025-05-08 14:15:31', 'K33Nwk02', 'Administrator', 1, '1990-10-10', '078 896 800', 'Tuol Kork, Phnom Penh', '32280115102023', 1, 0, 0, 'Dentyst', '$2y$10$Br8f97h3kq5JUTtDTZmViuUIKOufikCqyHt59Hx0g81CxQIS5.odm', '1, 40, 5, 6, 7, 8, 10, 11, 50, 12, 16, 36, 14, 33, 45, 46, 47, 48, 49, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 29, 30, 31, 44, 34, 35, 38,  15, 41', '1, 40, 5, 6, 7, 8, 10, 11, 50, 12, 16, 36, 14, 33, 45, 46, 47, 48, 49, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 29, 30, 31, 44, 34, 35, 38,  15, 41', '1, 40, 5, 6, 7, 8, 10, 11, 50, 12, 16, 36, 14, 33, 45, 46, 47, 48, 49, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 29, 30, 31, 44, 34, 35, 38,  15, 41', '1, 40, 5, 6, 7, 8, 10, 11, 50, 12, 16, 36, 14, 33, 45, 46, 47, 48, 49, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 29, 30, 31, 44, 34, 35, 38,  15, 41', 0, 1, 1, '00000000001', 0, 1),
	(2, '2025-05-03 12:18:59', 'Zx9MeAyb', 'Dr. Horn Panha', 1, '1996-12-01', '078 896 800', 'Khmuonh, Sen Sok', '0', 1, 0, 30, 'drpanha', '$2y$10$G07hcI0HPp3TV9EmlO0fD.1XE9YToh2xZHJjGoiBegxjUNLkylnHq', '1, 40, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 36, 14, 33, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 44, 29, 30, 31, 34, 35, 38,  15, 41', '1, 40, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 36, 14, 33, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 44, 29, 30, 31, 34, 35, 38,  15, 41', '1, 40, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 36, 14, 33, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 44, 29, 30, 31, 34, 35, 38,  15, 41', '1, 40, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 36, 14, 33, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 44, 29, 30, 31, 34, 35, 38,  15, 41', 0, 1, 1, NULL, 1, 1);
/*!40000 ALTER TABLE `tbl_staff` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_staff_position
CREATE TABLE IF NOT EXISTS `tbl_staff_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_position` varchar(150) NOT NULL,
  `default_permission` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_staff_position: ~4 rows (approximately)
DELETE FROM `tbl_staff_position`;
/*!40000 ALTER TABLE `tbl_staff_position` DISABLE KEYS */;
INSERT INTO `tbl_staff_position` (`id`, `staff_position`, `default_permission`) VALUES
	(1, 'Administrator', '1, 40, 5, 6, 7, 8, 10, 11, 12, 16, 36, 14, 33, 45, 46, 47, 48, 49, 17, 20, 42, 43, 22, 21, 24, 25, 26, 27, 28, 29, 30, 31, 44, 34, 35, 38,  15, 41'),
	(2, 'Receptionist', '2, 3, 4, 5, 6, 7, 9, 10, 11, 12'),
	(3, 'Dentist', '37, 2, 3, 4, 39, 36'),
	(4, 'Assistant', '3, 4, 36');
/*!40000 ALTER TABLE `tbl_staff_position` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_stock_request
CREATE TABLE IF NOT EXISTS `tbl_stock_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `prod_id` int(11) NOT NULL,
  `request_qty` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_stock_request: ~0 rows (approximately)
DELETE FROM `tbl_stock_request`;
/*!40000 ALTER TABLE `tbl_stock_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_stock_request` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_submenu
CREATE TABLE IF NOT EXISTS `tbl_submenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `sub_menu_name` varchar(250) NOT NULL,
  `sub_menu_kh` varchar(250) NOT NULL,
  `sub_menu_link` varchar(250) NOT NULL,
  `sub_menu_order` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table khemalin_dental_clinic.tbl_submenu: ~47 rows (approximately)
DELETE FROM `tbl_submenu`;
/*!40000 ALTER TABLE `tbl_submenu` DISABLE KEYS */;
INSERT INTO `tbl_submenu` (`id`, `menu_id`, `sub_menu_name`, `sub_menu_kh`, `sub_menu_link`, `sub_menu_order`) VALUES
	(1, 1, 'Dashboards', 'ផ្ទាំងអ្នកគ្រប់គ្រង', 'dashboard.php?pgid=1', 1),
	(2, 0, 'Appointments-c', 'ការណាត់ជួប', 'notification_appointment.php?pgid=2', 2),
	(3, 0, 'Queues List', 'កំពុងរង់ចាំ', 'notification_queue.php?pgid=3', 1),
	(4, 0, 'Served List', 'កំពុងព្យាបាល', 'notification_serving.php?pgid=4', 2),
	(5, 2, 'Follow Up', 'តាមដាន', 'notification_follow_up.php?pgid=5', 3),
	(6, 2, 'Served History', 'បញ្ចប់ការព្យាបាល', 'notification_served_history.php?pgid=6', 4),
	(7, 3, 'Patient List', 'អ្នកជំងឺ', 'patient_list.php?pgid=7', 1),
	(8, 3, 'Membership', 'កញ្ចប់សមាជិក', 'patient_membership.php?pgid=8', 2),
	(9, 0, 'Quotation List', 'វិក្កយបត្រព្រៀង', 'invoice_draft.php?pgid=9', 1),
	(10, 4, 'Pending Invoice', 'វិក្កយបត្របង់ប្រាក់', 'invoice_pending.php?pgid=10', 2),
	(11, 4, 'Receipt Payment', 'វិក្កយបត្របង់ប្រាក់រួចរាល់', 'receipt_payment.php?pgid=11', 3),
	(12, 5, 'Make Payment', 'ការចំណាយ', 'expense_list.php?pgid=12', 1),
	(13, 0, 'Expense Category', 'ប្រភេទចំណាយ', 'expense_category.php?pgid=13', 2),
	(14, 6, 'Product List', 'សម្ភារៈ', 'stock_product_list.php?pgid=14', 1),
	(15, 10, 'Category', 'ប្រភេទសម្ភារៈ', 'stock_product_category.php?pgid=15', 2),
	(16, 5, 'Supplier List', 'អ្នកផ្គត់ផ្គង់', 'stock_supplier_list.php?pgid=16', 3),
	(17, 7, 'Staff List', 'បុគ្គលិក', 'staff_list.php?pgid=17', 1),
	(18, 7, 'Position', 'តួនាទី', 'staff_position.php?pgid=18', 2),
	(20, 7, 'Staff Benefit', 'បែងចែកភាគលាភ', 'invoice_commission.php?pgid=20', 4),
	(21, 8, 'Patient Report', 'របាយការណ៍អតិថិជន', 'report_patient.php?pgid=21', 1),
	(22, 8, 'Revenues Report', 'របាយការណ៍វិក្កយបត្រ', 'report_invoice.php?pgid=22', 2),
	(24, 8, 'Expense Report', 'របាយការណ៍ចំណាយ', 'report_expense.php?pgid=24', 3),
	(25, 8, 'Stock Report', 'របាយការណ៍ស្តុកសម្ភារៈ', 'report_stock.php?pgid=25', 4),
	(26, 8, 'Staff Report', 'របាយការណ៍បុគ្គលិក', 'report_staff.php?pgid=26', 5),
	(27, 9, 'Deleted Patient', 'អតិថិជនដែលបានលុប', 'deleted_patient.php?pgid=27', 1),
	(28, 9, 'Deleted Invoice', 'វិក្កយបត្រដែលបានលុប', 'deleted_invoice.php?pgid=28', 2),
	(29, 9, 'Deleted Expense', 'ការចំណាយដែលបានលុប', 'deleted_expense.php?pgid=29', 3),
	(30, 9, 'Deleted Product', 'សម្ភារៈដែលបានលុប', 'deleted_product.php?pgid=30', 4),
	(31, 9, 'Deleted Staff', 'បុគ្គលិកដែលបានលុប', 'deleted_staff.php?pgid=31', 5),
	(33, 6, 'Service List', 'សេវាកម្មព្យាបាល', 'setting_treatment_service.php?pgid=33', 1),
	(34, 10, 'Tooth Number', 'លេខធ្មេញ', 'setting_tooth_item.php?pgid=34', 2),
	(35, 10, 'Payment Method', 'វិធីបង់ប្រាក់', 'setting_payment_method.php?pgid=35', 3),
	(36, 5, 'Stock Request', 'ស្នើសម្ភារៈ', 'stock_request.php?pgid=36', 4),
	(37, 1, 'Dashboards', 'ផ្ទាំងទន្តបណ្ឌិត', 'dashboard_dentist.php?pgid=37', 2),
	(38, 10, 'Measurement', 'ប្រភេទរវាស់', 'setting_measurement.php?pgid=38', 4),
	(39, 0, 'Prescription', 'វេជ្ជបញ្ជា', 'notification_diagnosis.php?pgid=39', 6),
	(40, 2, 'Reservation', 'ប្រតិទិន', 'notification_calendar.php?pgid=40', 1),
	(41, 10, 'System Info', 'System Info', 'setting_system_info.php?pgid=41', 5),
	(42, 8, 'Receipt Payment Report', 'Receipt Payment Report', 'report_receipt.php?pgid=42', 6),
	(43, 8, 'Pending Invoice Report', 'Pending Invoice Report', 'report_ar_invoice.php?pgid=43', 7),
	(44, 10, 'Royalty', 'Royalty', 'setting_royalty.php?pgid=44', 1),
	(45, 6, 'Purchase Request', 'Purchase Request', 'Purchase Request', 1),
	(46, 6, 'Good Receive ', 'Good Receive ', 'Good Receive ', 2),
	(47, 6, 'Stock Movement', 'Stock Movement', 'Stock Movement', 3),
	(48, 6, 'Stock Usage Settle', 'Stock Usage Settle', 'Stock Usage Settle', 4),
	(49, 6, 'Manual Count Stock', 'Manual Count Stock', 'Manual Count Stock', 5),
	(50, 4, 'Cash Transaction', 'Cash Transaction', 'cash_transaction.php?pgid=50', 4);
/*!40000 ALTER TABLE `tbl_submenu` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_submenu_bak
CREATE TABLE IF NOT EXISTS `tbl_submenu_bak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `sub_menu_name` varchar(250) NOT NULL,
  `sub_menu_kh` varchar(250) NOT NULL,
  `sub_menu_link` varchar(250) NOT NULL,
  `sub_menu_order` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table khemalin_dental_clinic.tbl_submenu_bak: ~38 rows (approximately)
DELETE FROM `tbl_submenu_bak`;
/*!40000 ALTER TABLE `tbl_submenu_bak` DISABLE KEYS */;
INSERT INTO `tbl_submenu_bak` (`id`, `menu_id`, `sub_menu_name`, `sub_menu_kh`, `sub_menu_link`, `sub_menu_order`) VALUES
	(1, 1, 'Admin Dashboard', 'ផ្ទាំងអ្នកគ្រប់គ្រង', 'dashboard.php?pgid=1', 1),
	(2, 2, 'Appointments', 'ការណាត់ជួប', 'notification_appointment.php?pgid=2', 2),
	(3, 2, 'Queues', 'កំពុងរង់ចាំ', 'notification_queue.php?pgid=3', 3),
	(4, 2, 'Served', 'កំពុងព្យាបាល', 'notification_serving.php?pgid=4', 4),
	(5, 2, 'Follow Up', 'តាមដាន', 'notification_follow_up.php?pgid=5', 5),
	(6, 2, 'Served History', 'បញ្ចប់ការព្យាបាល', 'notification_served_history.php?pgid=6', 7),
	(7, 3, 'Patient List', 'អ្នកជំងឺ', 'patient_list.php?pgid=7', 1),
	(8, 3, 'Membership', 'កញ្ចប់សមាជិក', 'patient_membership.php?pgid=8', 2),
	(9, 4, 'Quotation', 'វិក្កយបត្រព្រៀង', 'invoice_draft.php?pgid=9', 1),
	(10, 4, 'Pending Invoice', 'វិក្កយបត្របង់ប្រាក់', 'invoice_pending.php?pgid=10', 2),
	(11, 4, 'Completed Invoice', 'វិក្កយបត្របង់ប្រាក់រួចរាល់', 'invoice_completed.php?pgid=11', 3),
	(12, 5, 'Expense List', 'ការចំណាយ', 'expense_list.php?pgid=12', 1),
	(13, 5, 'Expense Category', 'ប្រភេទចំណាយ', 'expense_category.php?pgid=13', 2),
	(14, 6, 'Product List', 'សម្ភារៈ', 'stock_product_list.php?pgid=14', 1),
	(15, 6, 'Product Category', 'ប្រភេទសម្ភារៈ', 'stock_product_category.php?pgid=15', 2),
	(16, 6, 'Supplier List', 'អ្នកផ្គត់ផ្គង់', 'stock_supplier_list.php?pgid=16', 3),
	(17, 7, 'Staff List', 'បុគ្គលិក', 'staff_list.php?pgid=17', 1),
	(18, 7, 'Position', 'តួនាទី', 'staff_position.php?pgid=18', 2),
	(20, 7, 'Share Commission', 'បែងចែកភាគលាភ', 'invoice_commission.php?pgid=20', 4),
	(21, 8, 'Patient Report', 'របាយការណ៍អតិថិជន', 'report_patient.php?pgid=21', 1),
	(22, 8, 'Invoice Report', 'របាយការណ៍វិក្កយបត្រ', 'report_invoice.php?pgid=22', 2),
	(24, 8, 'Expense Report', 'របាយការណ៍ចំណាយ', 'report_expense.php?pgid=24', 4),
	(25, 8, 'Stock Report', 'របាយការណ៍ស្តុកសម្ភារៈ', 'report_stock.php?pgid=25', 5),
	(26, 8, 'Staff Report', 'របាយការណ៍បុគ្គលិក', 'report_staff.php?pgid=26', 6),
	(27, 8, 'Deleted Patient', 'អតិថិជនដែលបានលុប', 'deleted_patient.php?pgid=27', 7),
	(28, 8, 'Deleted Invoice', 'វិក្កយបត្រដែលបានលុប', 'deleted_invoice.php?pgid=28', 8),
	(29, 8, 'Deleted Expense', 'ការចំណាយដែលបានលុប', 'deleted_expense.php?pgid=29', 9),
	(30, 8, 'Deleted Product', 'សម្ភារៈដែលបានលុប', 'deleted_product.php?pgid=30', 10),
	(31, 8, 'Deleted Staff', 'បុគ្គលិកដែលបានលុប', 'deleted_staff.php?pgid=31', 11),
	(33, 10, 'Treatment Service', 'សេវាកម្មព្យាបាល', 'setting_treatment_service.php?pgid=33', 1),
	(34, 10, 'Tooth Number', 'លេខធ្មេញ', 'setting_tooth_item.php?pgid=34', 2),
	(35, 10, 'Payment Method', 'វិធីបង់ប្រាក់', 'setting_payment_method.php?pgid=35', 3),
	(36, 6, 'Stock Request', 'ស្នើសម្ភារៈ', 'stock_request.php?pgid=36', 4),
	(37, 1, 'Dentist Dashboard', 'ផ្ទាំងទន្តបណ្ឌិត', 'dashboard_dentist.php?pgid=37', 2),
	(38, 10, 'Measurement', 'ប្រភេទរវាស់', 'setting_measurement.php?pgid=38', 4),
	(39, 2, 'Prescription', 'វេជ្ជបញ្ជា', 'notification_diagnosis.php?pgid=39', 6),
	(40, 2, 'Calendar', 'ប្រតិទិន', 'notification_calendar.php?pgid=40', 1),
	(41, 10, 'System Info', 'System Info', 'setting_system_info.php?pgid=41', 5);
/*!40000 ALTER TABLE `tbl_submenu_bak` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_supplier
CREATE TABLE IF NOT EXISTS `tbl_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `supp_fname` varchar(150) NOT NULL,
  `supp_code` varchar(50) NOT NULL,
  `exp_cate_id` int(11) NOT NULL,
  `supp_contact` varchar(50) NOT NULL,
  `supp_address` mediumtext NOT NULL,
  `supp_image` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supp_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_supplier: ~4 rows (approximately)
DELETE FROM `tbl_supplier`;
/*!40000 ALTER TABLE `tbl_supplier` DISABLE KEYS */;
INSERT INTO `tbl_supplier` (`id`, `timestamp`, `supp_fname`, `supp_code`, `exp_cate_id`, `supp_contact`, `supp_address`, `supp_image`, `user_id`, `supp_status`) VALUES
	(1, '2023-10-21 00:59:26', 'Dynamic Cambodia', 'PSbm3ece', 4, '012 345 678', 'Sen Sok, Phnom Penh', '16120315102023', 1, 1),
	(2, '2023-10-15 15:12:28', 'Delta Laboratory', 'PSbm3eHX', 2, '012 345 678', 'Tuol Kork, Phnom Penh', '28120315102023', 5, 1),
	(3, '2023-10-15 15:12:50', 'Electricity (EDC)', 'Y1luDuOq', 3, '012 345 6789', 'Sen Sok, Phnom Penh', '50120315102023', 5, 1),
	(4, '2024-03-05 19:51:55', 'Camdent ', '76tp11ki', 4, '012 345 678', 'PP', '0', 2, 0);
/*!40000 ALTER TABLE `tbl_supplier` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_system_setting
CREATE TABLE IF NOT EXISTS `tbl_system_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_name_kh` varchar(150) DEFAULT NULL,
  `system_name_en` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `tax_no` varchar(50) DEFAULT NULL,
  `rating` float DEFAULT '0',
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
INSERT INTO `tbl_system_setting` (`id`, `system_name_kh`, `system_name_en`, `phone`, `email`, `address`, `tax_no`, `rating`, `logo`, `remark_invoice`, `license_to`, `joined_date`, `balance_amount`, `valid_date`, `licence_code`, `type`, `license_bill_adr`, `license_status`, `recuring`, `billing_issue`, `paid_amount`) VALUES
	(1, 'ពេទ្យធ្មេញ សុជាតា', 'SOCHEATA    DENTAL    CABINET', '086 608 381/071 9399 989', '', 'ភូមិស្នោរខាងកើត សង្កាត់ស្នោរ ខណ្ឌកំបូល រាជធានីភ្នំពេញ', '', 4000, 'logo.png', ' ទឹកប្រាក់ដែលបានបង់ហើយ មិនអាចដកវិញបានទេ! ធានារយៈពេល ១ ឆ្នាំលើការ​បែកបាក់ ឬប្រេះស្រាំចំពោះករណីស្រោបធ្មេញ! ', 'Mr. Meung Reaksmey', '2025-04-24', 50000, '2025-04-24', '00000000001', 'Dental Clinic', 'ភូមិស្នោរខាងកើត សង្កាត់ស្នោរ ខណ្ឌកំបូល រាជធានីភ្នំពេញ', 1, 1, 1, 1000);
/*!40000 ALTER TABLE `tbl_system_setting` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_tooth_item
CREATE TABLE IF NOT EXISTS `tbl_tooth_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tooth_description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_tooth_item: ~55 rows (approximately)
DELETE FROM `tbl_tooth_item`;
/*!40000 ALTER TABLE `tbl_tooth_item` DISABLE KEYS */;
INSERT INTO `tbl_tooth_item` (`id`, `tooth_description`) VALUES
	(1, '#11'),
	(2, '#12'),
	(3, '#13'),
	(4, '#14'),
	(5, '#15'),
	(6, '#16'),
	(7, '#17'),
	(8, '#18'),
	(9, '#21'),
	(10, '#22'),
	(11, '#23'),
	(12, '#24'),
	(13, '#25'),
	(14, '#26'),
	(15, '#27'),
	(16, '#28'),
	(17, '#31'),
	(18, '#32'),
	(19, '#33'),
	(20, '#34'),
	(21, '#35'),
	(22, '#36'),
	(23, '#37'),
	(24, '#38'),
	(25, '#41'),
	(26, '#42'),
	(27, '#43'),
	(28, '#44'),
	(29, '#45'),
	(30, '#46'),
	(31, '#47'),
	(32, '#48'),
	(33, '#51'),
	(34, '#52'),
	(35, '#53'),
	(36, '#54'),
	(37, '#55'),
	(38, '#61'),
	(39, '#62'),
	(40, '#63'),
	(41, '#64'),
	(42, '#65'),
	(43, '#71'),
	(44, '#72'),
	(45, '#73'),
	(46, '#74'),
	(47, '#75'),
	(48, '#81'),
	(49, '#82'),
	(50, '#83'),
	(51, '#84'),
	(52, '#85'),
	(53, 'Upper Arch'),
	(54, 'Lower Arch'),
	(55, 'Upper and Lower Arch');
/*!40000 ALTER TABLE `tbl_tooth_item` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_transaction
CREATE TABLE IF NOT EXISTS `tbl_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trans_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `trans_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_transaction: ~20 rows (approximately)
DELETE FROM `tbl_transaction`;
/*!40000 ALTER TABLE `tbl_transaction` DISABLE KEYS */;
INSERT INTO `tbl_transaction` (`id`, `timestamp`, `trans_id`, `payment_id`, `trans_status`) VALUES
	(1, '2025-04-28 10:27:49', 1, 1, 1),
	(2, '2025-04-28 10:37:44', 1, 2, 1),
	(3, '2025-04-28 10:40:44', 1, 3, 1),
	(4, '2025-04-28 11:21:41', 5, 4, 1),
	(5, '2025-04-28 11:23:35', 2, 5, 1),
	(6, '2025-04-28 11:29:34', 2, 6, 1),
	(7, '2025-04-28 11:33:43', 2, 7, 1),
	(8, '2025-04-28 11:37:03', 2, 8, 1),
	(9, '2025-04-28 11:39:15', 2, 9, 1),
	(10, '2025-05-02 09:02:37', 6, 10, 1),
	(11, '2025-05-02 09:04:25', 6, 11, 1),
	(12, '2025-05-02 09:15:39', 3, 12, 1),
	(13, '2025-05-02 09:15:50', 3, 13, 1),
	(14, '2025-05-02 09:56:47', 4, 14, 1),
	(15, '2025-05-03 14:20:33', 1, 1, 2),
	(16, '2025-05-04 15:33:35', 12, 15, 1),
	(17, '2025-05-06 14:37:01', 15, 16, 1),
	(18, '2025-05-07 23:22:28', 17, 17, 1),
	(19, '2025-05-07 23:26:55', 16, 18, 1),
	(20, '2025-05-08 21:08:17', 18, 19, 1);
/*!40000 ALTER TABLE `tbl_transaction` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_treatment
CREATE TABLE IF NOT EXISTS `tbl_treatment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tmpl_id` int(11) NOT NULL DEFAULT '0',
  `tmsv_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `tmt_price` float NOT NULL DEFAULT '0',
  `tmt_discount` float NOT NULL DEFAULT '0',
  `tooth_id` mediumtext,
  `tmt_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_treatment: ~21 rows (approximately)
DELETE FROM `tbl_treatment`;
/*!40000 ALTER TABLE `tbl_treatment` DISABLE KEYS */;
INSERT INTO `tbl_treatment` (`id`, `timestamp`, `tmpl_id`, `tmsv_id`, `user_id`, `tmt_price`, `tmt_discount`, `tooth_id`, `tmt_status`) VALUES
	(1, '2025-04-28 10:02:57', 1, 35, 1, 150, 0, '#11', 1),
	(2, '2025-04-28 10:49:35', 2, 31, 1, 250, 0, '#14', 1),
	(3, '2025-04-28 10:53:41', 4, 35, 1, 150, 0, '#13', 1),
	(4, '2025-04-28 11:02:40', 5, 23, 1, 180, 0, '#14', 1),
	(5, '2025-04-28 11:06:27', 6, 13, 1, 150, 0, '#12', 1),
	(6, '2025-04-28 11:07:33', 7, 23, 1, 180, 0, '#15', 1),
	(7, '2025-04-28 11:09:56', 8, 23, 1, 180, 0, '#16', 1),
	(8, '2025-05-02 09:02:06', 1, 34, 1, 75, 0, '#12', 1),
	(9, '2025-05-04 12:45:17', 21, 35, 1, 150, 0, '#12', 1),
	(10, '2025-05-04 13:06:06', 1, 23, 1, 180, 0, '#11', 1),
	(11, '2025-05-04 15:17:57', 30, 35, 1, 150, 0, '#11', 1),
	(12, '2025-05-04 15:56:55', 31, 35, 1, 150, 0, '#11', 1),
	(13, '2025-05-04 15:57:01', 31, 26, 1, 25, 0, '#11', 1),
	(14, '2025-05-04 16:02:52', 32, 35, 1, 150, 0, '#11', 1),
	(15, '2025-05-04 16:02:59', 32, 13, 1, 150, 0, '#11', 1),
	(16, '2025-05-07 23:20:12', 35, 35, 1, 150, 0, '#11', 1),
	(17, '2025-05-07 23:20:18', 35, 34, 1, 75, 0, '#12', 1),
	(18, '2025-05-07 23:20:24', 35, 23, 1, 180, 0, '#13', 1),
	(19, '2025-05-07 23:20:31', 35, 13, 1, 150, 0, '#14', 1),
	(20, '2025-05-07 23:21:45', 34, 35, 1, 150, 0, '#11', 1),
	(21, '2025-05-08 13:52:07', 36, 35, 1, 150, 0, '#11', 1);
/*!40000 ALTER TABLE `tbl_treatment` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_treatment_category
CREATE TABLE IF NOT EXISTS `tbl_treatment_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treatment_category` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_treatment_category: ~6 rows (approximately)
DELETE FROM `tbl_treatment_category`;
/*!40000 ALTER TABLE `tbl_treatment_category` DISABLE KEYS */;
INSERT INTO `tbl_treatment_category` (`id`, `treatment_category`) VALUES
	(1, 'Orthodontics'),
	(2, 'Dental Implant'),
	(3, 'Restoration'),
	(4, 'Minor Surgery'),
	(5, 'General'),
	(6, 'Dental Lab');
/*!40000 ALTER TABLE `tbl_treatment_category` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_treatment_duration
CREATE TABLE IF NOT EXISTS `tbl_treatment_duration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trmt_duration` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_treatment_duration: ~8 rows (approximately)
DELETE FROM `tbl_treatment_duration`;
/*!40000 ALTER TABLE `tbl_treatment_duration` DISABLE KEYS */;
INSERT INTO `tbl_treatment_duration` (`id`, `trmt_duration`) VALUES
	(1, '15 mins'),
	(2, '30 mins'),
	(3, '45 mins'),
	(4, '60 mins'),
	(5, '90 mins'),
	(6, '120 mins'),
	(7, '150 mins'),
	(8, '180 mins');
/*!40000 ALTER TABLE `tbl_treatment_duration` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_treatment_plan
CREATE TABLE IF NOT EXISTS `tbl_treatment_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `plan_title` varchar(150) DEFAULT NULL,
  `cust_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_status` int(1) NOT NULL,
  `is_invoice` int(1) NOT NULL DEFAULT '0',
  `apid` int(11) NOT NULL DEFAULT '0' COMMENT 'appoitment id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_treatment_plan: ~36 rows (approximately)
DELETE FROM `tbl_treatment_plan`;
/*!40000 ALTER TABLE `tbl_treatment_plan` DISABLE KEYS */;
INSERT INTO `tbl_treatment_plan` (`id`, `timestamp`, `plan_title`, `cust_id`, `user_id`, `plan_status`, `is_invoice`, `apid`) VALUES
	(1, '2025-05-04 16:02:26', NULL, 1, 1, 0, 0, 0),
	(2, '2025-05-04 16:02:27', NULL, 1, 1, 0, 0, 0),
	(3, '2025-05-04 16:02:41', NULL, 1, 1, 0, 0, 0),
	(4, '2025-05-04 16:02:39', NULL, 1, 1, 0, 0, 0),
	(5, '2025-05-04 16:02:38', NULL, 1, 1, 0, 0, 0),
	(6, '2025-05-04 16:02:36', NULL, 1, 1, 0, 0, 0),
	(7, '2025-05-04 16:02:35', NULL, 1, 1, 0, 0, 0),
	(8, '2025-05-04 16:02:33', NULL, 1, 1, 0, 0, 0),
	(9, '2025-05-04 16:02:31', NULL, 1, 1, 0, 0, 0),
	(10, '2025-05-04 16:02:29', NULL, 1, 1, 0, 0, 0),
	(11, '2025-05-04 15:56:23', NULL, 2, 1, 0, 0, 0),
	(12, '2025-05-04 15:56:21', NULL, 2, 1, 0, 0, 0),
	(13, '2025-05-04 15:56:20', NULL, 2, 1, 0, 0, 0),
	(14, '2025-05-04 15:56:18', NULL, 2, 1, 0, 0, 0),
	(15, '2025-05-04 16:02:23', NULL, 1, 1, 0, 0, 0),
	(16, '2025-05-04 16:02:21', NULL, 1, 1, 0, 0, 0),
	(17, '2025-05-04 15:56:17', NULL, 2, 1, 0, 0, 0),
	(18, '2025-05-04 16:02:19', NULL, 1, 1, 0, 0, 0),
	(19, '2025-05-04 15:56:15', NULL, 2, 1, 0, 0, 0),
	(20, '2025-05-04 16:02:17', NULL, 1, 1, 0, 0, 0),
	(21, '2025-05-04 16:02:16', NULL, 1, 1, 0, 1, 0),
	(22, '2025-05-04 16:02:14', NULL, 1, 1, 0, 0, 0),
	(23, '2025-05-04 16:02:13', NULL, 1, 1, 0, 0, 0),
	(24, '2025-05-04 16:02:11', NULL, 1, 1, 0, 0, 0),
	(25, '2025-05-04 16:02:09', NULL, 1, 1, 0, 0, 0),
	(26, '2025-05-04 16:02:08', NULL, 1, 1, 0, 0, 0),
	(27, '2025-05-04 16:02:05', NULL, 1, 1, 0, 0, 0),
	(28, '2025-05-04 16:02:03', NULL, 1, 1, 0, 0, 0),
	(29, '2025-05-04 16:02:01', NULL, 1, 1, 0, 0, 0),
	(30, '2025-05-04 15:56:14', NULL, 2, 1, 0, 1, 0),
	(31, '2025-05-06 09:20:24', 'test tttt', 2, 1, 1, 1, 7),
	(32, '2025-05-04 16:03:01', NULL, 1, 1, 1, 1, 6),
	(33, '2025-05-06 12:00:12', NULL, 2, 1, 1, 0, 7),
	(34, '2025-05-07 23:21:48', NULL, 2, 1, 1, 1, 0),
	(35, '2025-05-07 23:20:34', NULL, 2, 1, 1, 1, 0),
	(36, '2025-05-08 13:52:11', NULL, 1, 1, 1, 1, 6);
/*!40000 ALTER TABLE `tbl_treatment_plan` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_treatment_service
CREATE TABLE IF NOT EXISTS `tbl_treatment_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_cate_id` int(11) NOT NULL DEFAULT '1',
  `service_description` varchar(250) DEFAULT NULL,
  `service_price` float NOT NULL,
  `service_cost` float NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_treatment_service: ~51 rows (approximately)
DELETE FROM `tbl_treatment_service`;
/*!40000 ALTER TABLE `tbl_treatment_service` DISABLE KEYS */;
INSERT INTO `tbl_treatment_service` (`id`, `service_cate_id`, `service_description`, `service_price`, `service_cost`, `status`) VALUES
	(1, 5, 'X-Ray Periapical / ថតធ្មេញហ្វីលតូច Periapical', 3.5, 100, 1),
	(2, 5, 'X-Ray Panoramic / ថតធ្មេញហ្វីលធំ Panoramic', 15, 100, 1),
	(3, 5, 'X-Ray Cephalometric / ថតធ្មេញហ្វីលធំ Cephalometric', 15, 100, 1),
	(4, 6, 'Crown Metal Ceramic / គ្រាប់ធ្មេញប្រភេទសេរ៉ាមិច', 100, 35, 1),
	(5, 6, 'Crown Free-Nikel Ceramic / គ្រាប់ធ្មេញប្រភេទសេរ៉ាមិច', 180, 35, 1),
	(6, 6, 'Crown Zirconium (Japan) / គ្រាប់ធ្មេញប្រភេទស៊ីកូនៀម', 280, 35, 1),
	(7, 6, 'Crown Zirconium (German) / គ្រាប់ធ្មេញប្រភេទស៊ីកូនៀម', 350, 35, 1),
	(8, 6, 'Crown E-Max / គ្រាប់ធ្មេញពិសេសប្រភេទអ៊ីមែកស៍', 400, 35, 1),
	(9, 6, 'Veneer E-Max / សន្លឹកធ្មេញពិសេសប្រភេទអ៊ីមែកស៍', 350, 35, 1),
	(10, 6, 'Veneer Composite 1 Tooth / សន្លឹកធ្មេញប្រភេទ Composite', 35, 25, 1),
	(11, 6, 'Skelete Denture with Metal Framework 1 Arch / ធ្មេញដោះចេញ-ចូលប្រភេទគ្រោងដែក', 450, 35, 1),
	(12, 6, 'Complete Denture 1 Arch / ធ្មេញស្និតដោះចេញ-ចូល', 350, 35, 1),
	(13, 3, 'Attachment Key / គន្លឹះកឹបសម្រាប់ធ្មេញដោះចេញ-ចូល', 150, 35, 1),
	(14, 3, 'Diamond Fixation / បិតត្បូងលើធ្មេញធម្មជាតិ', 25, 25, 1),
	(15, 3, 'Whitening Bleaching ខាំពុម្ភធ្មេញឲ្យស-ស្អាត', 90, 35, 1),
	(16, 3, 'Whitening Plasma Light / បាញ់កាំរស្មីធ្មេញឲ្យស-ស្អាត', 120, 25, 1),
	(17, 3, 'Toothmousse Package / ខាំពុម្ភធ្មេញបំបាត់ការស្រៀវស្រគៀរ', 90, 35, 1),
	(18, 1, 'Orthodontic Treatment Class I,II,III Simple Case / ពត់តម្រង់ធ្មេញសម្រាប់ករណីធម្មតា', 1500, 25, 1),
	(19, 1, 'Orthodontic Treatment Class I,II,III Moderate Case / ពត់តម្រង់ធ្មេញសម្រាប់ករណីស្មុគស្មាញ', 2000, 25, 1),
	(20, 1, 'Miniscrew / បង្គោល Miniscrew សម្រាប់ពត់តម្រង់ធ្មេញ', 100, 25, 1),
	(21, 1, 'Clear Retainer / ឧបករណ៍ទប់ធ្មេញប្រភេទជ័រថ្លា', 100, 100, 1),
	(22, 4, 'Surgical Removal of Tooth & Wisdom Tooth / ការវះកាត់ធ្មេញកប់ក្នុងឆ្អឹង', 150, 25, 1),
	(23, 4, 'Apicoectomy / ការវះកាត់ចុងឫសធ្មេញ', 180, 25, 1),
	(24, 4, 'Root-Planning 1 Tooth / ការព្យាបាលរលាកអញ្ចាញស្រួចស្រាវ', 35, 25, 1),
	(25, 4, 'Root-Planning 1 Arch Package with ToothMousse / ការព្យាបាលរលាកអញ្ចាញរាំរ៉ៃ', 168, 25, 0),
	(26, 4, 'Abscess Drainage / ការព្យាបាលចោះបង្ហូរខ្ទុះ', 25, 25, 0),
	(27, 4, 'Crown Lengthening 1 Tooth / ការវះកាត់លើកកម្ពស់ធ្មេញ', 45, 25, 0),
	(28, 2, 'Implant System Korea / ការដាំបង្គោលធ្មេញក្នុងឆ្អឹង Neo-Implant (Korea)', 850, 50, 0),
	(29, 2, 'Implant System Switzerland / ការដាំបង្គោលធ្មេញក្នុងឆ្អឹង SGS-Implant (Switzerland)', 1200, 50, 0),
	(30, 4, 'Bone Graft Small / ការបន្ថែមឆ្អឹងសិប្បនិម្មិតទំហំតូច', 150, 100, 0),
	(31, 4, 'Bone Graft Big / ការបន្ថែមឆ្អឹងសិប្បនិម្មិតទំហំធំ', 250, 100, 1),
	(32, 4, 'Membrane Collagen Small / បន្ទះកោសិកាបណ្ដុះឆ្អឹងទំហំតូច', 150, 100, 1),
	(33, 4, 'Membrane Collagen Big / បន្ទះកោសិកាបណ្ដុះឆ្អឹងទំហំធំ', 200, 60, 1),
	(34, 4, 'A-Prf Small / ការបន្សាំកោសិកាបណ្ដុះឆ្អឹងក្រោយការវះកាត់ទំហំតូច', 75, 50, 1),
	(35, 4, 'A-Prf Big / ការបន្សាំកោសិកាបណ្ដុះឆ្អឹងក្រោយការវះកាត់ទំហំធំ', 150, 50, 1),
	(36, 4, 'Sinus Lift Crestal Approach / ការវះកាត់លើកប្រអប់ខ្យល់សម្រាប់ការដាំបង្គោលធ្មេញក្នុងឆ្អឹង (តាមរណ្ដៅធ្មេញ)', 250, 50, 1),
	(37, 4, 'Sinus Lift Lateral Window / ការវះកាត់លើកប្រអប់ខ្យល់សម្រាប់ការដាំបង្គោលធ្មេញក្នុងឆ្អឹង (តាមឆ្អឹងថ្គាម)', 450, 50, 1),
	(38, 4, 'Autogenous Block Graft / ការវះកាត់បន្ថែមឆ្អឹង', 450, 50, 1),
	(39, 6, 'Temporary Tooth (Same Day) / ធ្មេញបណ្ដោះអាសន្ន', 5, 100, 1),
	(40, 3, 'Filling Baby Tooth / ការប៉ះធ្មេញព្រៃសម្រាប់កុមារ', 15, 25, 1),
	(41, 3, 'Filling Class 1,2,3,4,5 Small Cavity / ការប៉ះធ្មេញដោយកាំរស្មី', 15, 25, 1),
	(42, 3, 'Filling Class 1,2,3,4,5 Deep Cavity / ការប៉ះធ្មេញដោយកាំរស្មី', 25, 25, 1),
	(43, 4, 'Extraction Anterior & Premolar Teeth / ការដកធ្មេញមុខ ឬធ្មេញថ្គាមតូច', 25, 25, 1),
	(44, 4, 'Extraction Molar Teeth / ការដកធ្មេញថ្គាមធំ', 35, 25, 1),
	(45, 4, 'Extraction Primary Teeth / ការដកធ្មេញព្រៃសម្រាប់កុមារ', 15, 25, 1),
	(46, 5, 'Scaling and Polishing / ការសំអាតធ្មេញធម្មតា', 15, 25, 1),
	(47, 5, 'Scaling and Polishing Deep / ការសំអាតធ្មេញកំបោរច្រើន', 30, 25, 1),
	(48, 3, 'Root Canal Anterior/ការព្យាបាលធ្មេញមុខ', 40, 10, 1),
	(49, 3, 'Root Canal Molar/ការព្យាបាលធ្មេញធ្គាម', 50, 10, 1),
	(51, 3, 'Root Canal Molar/ការព្យាបាលធ្មេញធ្គាមតូច', 45, 10, 1),
	(52, 1, 'Wraparound Retainer/ឧបករណ៍ទប់ធ្មេញប្រភេទ Wraparound', 100, 100, 1);
/*!40000 ALTER TABLE `tbl_treatment_service` ENABLE KEYS */;

-- Dumping structure for table khemalin_dental_clinic.tbl_user_log
CREATE TABLE IF NOT EXISTS `tbl_user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `log_status` int(1) NOT NULL,
  `lang` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- Dumping data for table khemalin_dental_clinic.tbl_user_log: ~33 rows (approximately)
DELETE FROM `tbl_user_log`;
/*!40000 ALTER TABLE `tbl_user_log` DISABLE KEYS */;
INSERT INTO `tbl_user_log` (`id`, `timestamp`, `user_id`, `log_status`, `lang`) VALUES
	(1, '2025-04-29 16:20:09', 1, 1, 1),
	(2, '2025-05-02 12:42:44', 1, 1, 1),
	(3, '2025-05-02 12:44:02', 1, 1, 1),
	(4, '2025-05-02 17:12:56', 1, 1, 1),
	(5, '2025-05-02 17:18:03', 1, 1, 1),
	(6, '2025-05-02 17:21:41', 1, 1, 1),
	(7, '2025-05-02 17:23:24', 1, 1, 1),
	(8, '2025-05-02 17:29:42', 1, 1, 1),
	(9, '2025-05-02 17:42:41', 1, 1, 1),
	(10, '2025-05-02 17:56:26', 1, 1, 1),
	(11, '2025-05-02 18:00:01', 1, 1, 1),
	(12, '2025-05-02 18:02:20', 1, 1, 1),
	(13, '2025-05-02 18:09:13', 1, 1, 1),
	(14, '2025-05-02 18:11:12', 1, 1, 1),
	(15, '2025-05-02 22:44:38', 1, 1, 1),
	(16, '2025-05-02 22:47:52', 1, 1, 1),
	(17, '2025-05-02 22:51:44', 1, 1, 1),
	(18, '2025-05-03 11:15:57', 1, 1, 1),
	(19, '2025-05-03 12:19:22', 1, 1, 1),
	(20, '2025-05-03 12:21:01', 1, 1, 1),
	(21, '2025-05-03 15:06:08', 1, 1, 1),
	(22, '2025-05-04 11:49:13', 1, 1, 1),
	(23, '2025-05-04 11:52:26', 1, 1, 1),
	(24, '2025-05-04 16:15:04', 1, 1, 1),
	(25, '2025-05-05 09:05:53', 1, 1, 1),
	(26, '2025-05-06 09:16:30', 1, 1, 1),
	(27, '2025-05-07 12:41:43', 1, 1, 1),
	(28, '2025-05-07 22:03:00', 1, 1, 1),
	(29, '2025-05-08 08:43:43', 1, 1, 1),
	(30, '2025-05-08 14:09:47', 1, 1, 1),
	(31, '2025-05-08 14:11:00', 1, 1, 1),
	(32, '2025-05-08 14:16:09', 1, 1, 1),
	(33, '2025-05-08 21:06:49', 1, 1, 1);
/*!40000 ALTER TABLE `tbl_user_log` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
