-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2017 at 04:37 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bucn_rims`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `account_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `password` varchar(65) NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `account_status` varchar(100) NOT NULL,
  `personnel_id` bigint(20) NOT NULL,
  `datecreated` datetime(6) NOT NULL,
  PRIMARY KEY (`account_id`),
  KEY `personnel_id` (`personnel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `password`, `account_type`, `account_status`, `personnel_id`, `datecreated`) VALUES
(3, '302d93e47758a5b0b901d6c86b520443', 'System Administrator', 'activated', 18, '2016-08-29 17:01:01.000000'),
(35, '368e1242f2d09b404f33d492405105ff', 'End User', 'activated', 54, '2017-01-18 09:23:18.000000'),
(34, '368e1242f2d09b404f33d492405105ff', 'End User', 'activated', 53, '2017-01-17 19:16:00.000000'),
(32, '368e1242f2d09b404f33d492405105ff', 'Administrator', 'activated', 51, '2017-01-17 17:18:49.000000'),
(31, '368e1242f2d09b404f33d492405105ff', 'End User', 'activated', 50, '2017-01-17 14:30:46.000000'),
(30, '368e1242f2d09b404f33d492405105ff', 'System Administrator', 'activated', 49, '2017-01-17 14:04:13.000000'),
(29, '368e1242f2d09b404f33d492405105ff', 'End User', 'activated', 48, '2017-01-07 13:51:41.000000'),
(28, '368e1242f2d09b404f33d492405105ff', 'System Administrator', 'activated', 47, '2017-01-07 13:46:39.000000'),
(27, '368e1242f2d09b404f33d492405105ff', 'Administrator', 'activated', 46, '2016-11-07 20:44:44.000000'),
(23, '368e1242f2d09b404f33d492405105ff', 'Administrator', 'activated', 42, '2016-10-04 15:02:28.000000'),
(22, '368e1242f2d09b404f33d492405105ff', 'End User', 'activated', 41, '2016-10-03 23:19:16.000000');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cartnum` varchar(100) NOT NULL,
  `cartdate` date NOT NULL,
  `personnel_id` bigint(20) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_line`
--

CREATE TABLE IF NOT EXISTS `cart_line` (
  `cart_line_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) NOT NULL,
  `su_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `requesting_quantity` int(11) NOT NULL,
  `approved_quantity` int(11) NOT NULL,
  `requestor` bigint(20) NOT NULL,
  PRIMARY KEY (`cart_line_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_status`
--

CREATE TABLE IF NOT EXISTS `cart_status` (
  `cart_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) NOT NULL,
  `cart_status_name` varchar(100) NOT NULL,
  PRIMARY KEY (`cart_status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(1000) NOT NULL,
  `category_type` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_type`) VALUES
(17, 'Common Janitorial Supplies', 'Category for Supplies'),
(16, 'Common Office Devices', 'Category for Supplies'),
(14, 'Common Electrical Supplies', 'Category for Supplies'),
(15, 'Common Computer Supplies', 'Category for Supplies'),
(13, 'Common Office Supplies', 'Category for Supplies'),
(18, 'Common Office Equipment', 'Category for Equipment'),
(19, 'Office Equipment and Accesories', 'Category for Equipment'),
(20, 'Common Computer Equipment', 'Category for Equipment'),
(21, 'Office Appliances', 'Category for Equipment'),
(22, 'Office Devices', 'Category for Equipment');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(45) NOT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `dept_name`) VALUES
(1, 'Dean''s Office'),
(2, 'Cashier''s Office'),
(3, 'Registrar Office'),
(4, 'Hospital Room'),
(5, 'Library'),
(6, 'Supply Office'),
(7, 'BAC Office'),
(8, 'Faculty Office'),
(9, 'Research Office'),
(10, 'Extension Office'),
(11, 'Admin Office'),
(12, 'Accounting Office'),
(13, 'Budget Office'),
(14, 'Production Office'),
(15, 'Guidance Office'),
(16, 'Sports Club Office'),
(17, 'CSC Office'),
(18, 'Other Office');

-- --------------------------------------------------------

--
-- Table structure for table `eqp_disposal`
--

CREATE TABLE IF NOT EXISTS `eqp_disposal` (
  `eqpd_id` int(11) NOT NULL AUTO_INCREMENT,
  `dispdate` date NOT NULL,
  `dispnum` varchar(100) NOT NULL,
  `dispstatus` varchar(1000) NOT NULL,
  `disp_chairman` varchar(1000) NOT NULL,
  `disp_memberA` varchar(1000) NOT NULL,
  `disp_memberB` varchar(1000) NOT NULL,
  `disp_memberC` varchar(1000) NOT NULL,
  `disp_coa` varchar(1000) NOT NULL,
  PRIMARY KEY (`eqpd_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `eqp_disposal_items`
--

CREATE TABLE IF NOT EXISTS `eqp_disposal_items` (
  `eqpditem_id` int(11) NOT NULL AUTO_INCREMENT,
  `eqpd_id` int(11) NOT NULL,
  `eqp_id` bigint(20) NOT NULL,
  PRIMARY KEY (`eqpditem_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `eqp_history`
--

CREATE TABLE IF NOT EXISTS `eqp_history` (
  `eqphistory_id` int(11) NOT NULL AUTO_INCREMENT,
  `eqp_id` bigint(20) NOT NULL,
  `receivedBy` bigint(20) NOT NULL,
  `historydate` date NOT NULL,
  `icspar` varchar(5) NOT NULL,
  `icspar_id` int(11) NOT NULL,
  `remarks` varchar(1000) NOT NULL,
  PRIMARY KEY (`eqphistory_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=121 ;

-- --------------------------------------------------------

--
-- Table structure for table `eqp_ics`
--

CREATE TABLE IF NOT EXISTS `eqp_ics` (
  `ics_id` int(11) NOT NULL AUTO_INCREMENT,
  `icsdate` date NOT NULL,
  `icsnum` varchar(100) NOT NULL,
  `receivedBy` bigint(20) NOT NULL,
  `receivedFrom` bigint(20) NOT NULL,
  `est_useful_life` varchar(1000) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_cost` float NOT NULL,
  `date_acquired` date NOT NULL,
  `pr_id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  `iar_id` int(11) NOT NULL,
  `supplier_id` bigint(20) NOT NULL,
  PRIMARY KEY (`ics_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `eqp_par`
--

CREATE TABLE IF NOT EXISTS `eqp_par` (
  `par_id` int(11) NOT NULL AUTO_INCREMENT,
  `pardate` date NOT NULL,
  `parnum` varchar(100) NOT NULL,
  `date_acquired` date NOT NULL,
  `total_cost` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `receivedBy` bigint(20) NOT NULL,
  `receivedFrom` bigint(20) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  `supplier_id` bigint(20) NOT NULL,
  PRIMARY KEY (`par_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `eqp_pm_items`
--

CREATE TABLE IF NOT EXISTS `eqp_pm_items` (
  `pmitems_id` int(11) NOT NULL AUTO_INCREMENT,
  `eqp_pm_id` int(11) NOT NULL,
  `eqp_id` bigint(20) NOT NULL,
  `findings` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`pmitems_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `eqp_preventive_maintenance`
--

CREATE TABLE IF NOT EXISTS `eqp_preventive_maintenance` (
  `eqp_pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `pmDate` date NOT NULL,
  `pmNum` varchar(100) NOT NULL,
  `pmSched` date NOT NULL,
  `pmDateDone` date NOT NULL,
  `pmStatus` varchar(100) NOT NULL,
  `pmRepairer` varchar(100) NOT NULL,
  `pmCompany` varchar(200) NOT NULL,
  `pmAddress` text NOT NULL,
  `pmContact` varchar(15) NOT NULL,
  `pmRequestedBy` bigint(20) NOT NULL,
  PRIMARY KEY (`eqp_pm_id`),
  KEY `pm_sched_id` (`pmSched`),
  KEY `pm_status_id` (`pmStatus`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `eqp_turnover`
--

CREATE TABLE IF NOT EXISTS `eqp_turnover` (
  `to_id` int(11) NOT NULL AUTO_INCREMENT,
  `tonum` varchar(100) NOT NULL,
  `toDate` date NOT NULL,
  `toFrom` varchar(100) NOT NULL,
  `toTo` varchar(100) NOT NULL,
  `date_acquired` date NOT NULL,
  PRIMARY KEY (`to_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE IF NOT EXISTS `equipments` (
  `eqp_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eqpnum` varchar(100) NOT NULL,
  `eqpdate` date NOT NULL,
  `prop_num` varchar(100) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_unit_id` int(11) NOT NULL,
  `brand` varchar(1000) NOT NULL,
  `description` text NOT NULL COMMENT 'set equipment description like model number, etc.',
  `serialnum` varchar(50) NOT NULL,
  `unit_value` float NOT NULL,
  `est_useful_life` varchar(1000) NOT NULL,
  `icspar` varchar(21) NOT NULL COMMENT 'set if equipment is ICS, PAR or TO (Turn Over)',
  `ics_par_id` int(11) NOT NULL,
  `received_by` int(20) NOT NULL COMMENT 'This sets the item issued to and transferred to.',
  `dept_id` int(11) NOT NULL,
  `date_acquired` date NOT NULL,
  `req_item_id` bigint(20) NOT NULL COMMENT 'reference in case RIS is gonna be cancel.',
  `su_id` int(11) NOT NULL,
  `remarks` varchar(100) NOT NULL COMMENT 'Working, Unserviceable, Transferred, Under Maintenance',
  `eqp_photo` varchar(65) NOT NULL,
  PRIMARY KEY (`eqp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Table structure for table `funding`
--

CREATE TABLE IF NOT EXISTS `funding` (
  `fund_id` int(11) NOT NULL AUTO_INCREMENT,
  `po_id` bigint(20) NOT NULL,
  `status` varchar(64) NOT NULL,
  `os_num` varchar(64) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `fundate` date NOT NULL,
  PRIMARY KEY (`fund_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `funding`
--

INSERT INTO `funding` (`fund_id`, `po_id`, `status`, `os_num`, `amount`, `type`, `fundate`) VALUES
(73, 86, 'funded', 'MOOE-164-2017-02-4', '1197.95', '164', '2017-02-22'),
(72, 85, 'funded', 'MOOE-101-2017-02-3', '380.25', '101', '2017-02-22'),
(71, 84, 'funded', 'MOOE-101-2017-02-2', '1563.75', '101', '2017-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `inspection`
--

CREATE TABLE IF NOT EXISTS `inspection` (
  `inspection_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `inspection_date` date NOT NULL,
  `status` varchar(64) NOT NULL,
  `po_id` bigint(20) NOT NULL,
  `personnel_id` bigint(20) NOT NULL,
  PRIMARY KEY (`inspection_id`),
  KEY `personnel_id` (`personnel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `inspection`
--

INSERT INTO `inspection` (`inspection_id`, `inspection_date`, `status`, `po_id`, `personnel_id`) VALUES
(50, '2017-02-22', 'Inspected', 85, 46);

-- --------------------------------------------------------

--
-- Table structure for table `inspect_accept_report`
--

CREATE TABLE IF NOT EXISTS `inspect_accept_report` (
  `iar_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `po_id` bigint(20) NOT NULL,
  `iarnumber` varchar(100) NOT NULL,
  `iardate` date NOT NULL,
  `invoice_num` varchar(100) NOT NULL,
  `invoice_date` date NOT NULL,
  `inspection_id` bigint(20) NOT NULL,
  `personnel_id` bigint(20) NOT NULL,
  PRIMARY KEY (`iar_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(10000) NOT NULL,
  `item_type` varchar(60) NOT NULL,
  `item_unit_id` int(11) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `criticalimit` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_type`, `item_unit_id`, `category_id`, `criticalimit`) VALUES
(105, 'Air Freshener', 'Supply', 0, 13, 0),
(104, 'Acetate', 'Supply', 0, 13, 0),
(106, 'Printer', 'Equipment', 0, 18, 0),
(107, 'Clip', 'Supply', 0, 13, 0),
(108, 'Paper', 'Supply', 0, 13, 0),
(109, 'Air Conditioner', 'Equipment', 0, 21, 0),
(110, 'Ballpen', 'Supply', 0, 13, 0),
(111, 'Scotch Tape', 'Supply', 0, 13, 0),
(112, 'Pencil', 'Supply', 0, 13, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_unit`
--

CREATE TABLE IF NOT EXISTS `item_unit` (
  `item_unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_unit_name` varchar(65) NOT NULL,
  PRIMARY KEY (`item_unit_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `item_unit`
--

INSERT INTO `item_unit` (`item_unit_id`, `item_unit_name`) VALUES
(41, 'Pair/s'),
(40, 'Bottle/s'),
(39, 'Roll/s'),
(38, 'Can/s'),
(37, 'Dozen/s'),
(36, 'Box/es'),
(35, 'Set/s'),
(34, 'Pack/s'),
(33, 'Kilo/s'),
(32, 'Unit/s'),
(31, 'Ream/s'),
(42, 'Pad/s'),
(30, 'Piece/s'),
(43, 'Tube/s'),
(44, 'Jar/s'),
(45, 'Cartridge/s'),
(46, 'Each');

-- --------------------------------------------------------

--
-- Table structure for table `more_desc`
--

CREATE TABLE IF NOT EXISTS `more_desc` (
  `md_id` int(11) NOT NULL AUTO_INCREMENT,
  `munit_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`md_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `more_desc`
--

INSERT INTO `more_desc` (`md_id`, `munit_id`, `description`, `price`) VALUES
(1, 42, 'Short Bond Paper Hard Copy', 183.52),
(2, 42, 'Long Bond Paper', 211.6),
(5, 45, 'Short Bond Paper', 58),
(6, 44, 'Short Bond Paper', 1.01),
(12, 54, 'HBW Black', 10.5),
(11, 53, 'Brand Scotch Tape', 4.5),
(13, 55, 'Faber Castel', 45.75);

-- --------------------------------------------------------

--
-- Table structure for table `more_units`
--

CREATE TABLE IF NOT EXISTS `more_units` (
  `munit_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `item_unit_id` int(11) NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`munit_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `more_units`
--

INSERT INTO `more_units` (`munit_id`, `item_id`, `item_unit_id`, `price`) VALUES
(55, 110, 36, 0),
(45, 108, 31, 0),
(43, 109, 32, 0),
(44, 108, 30, 0),
(41, 107, 46, 0),
(40, 107, 36, 0),
(48, 106, 32, 0),
(38, 105, 38, 0),
(37, 104, 39, 0),
(54, 110, 30, 0),
(53, 111, 30, 0),
(56, 112, 30, 0),
(57, 112, 36, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `data` text NOT NULL,
  `time` datetime(6) NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`note_id`, `data`, `time`) VALUES
(11, 'Hey!', '2017-02-01 01:26:23.000000'),
(12, 'Hellp', '2017-02-09 12:02:40.000000');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE IF NOT EXISTS `personnel` (
  `personnel_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `personnel_lname` varchar(64) NOT NULL,
  `personnel_fname` varchar(64) NOT NULL,
  `personnel_mname` varchar(64) NOT NULL,
  `personnel_bday` date NOT NULL,
  `personnel_bplace` varchar(100) NOT NULL,
  `personnel_civilstatus` varchar(15) NOT NULL,
  `personnel_sex` varchar(6) NOT NULL,
  `personnel_address` varchar(65) NOT NULL,
  `personnel_email` varchar(30) NOT NULL,
  `personnel_contact_no` varchar(15) NOT NULL,
  `personnel_photo` varchar(65) NOT NULL,
  `personnel_primary_education` varchar(100) NOT NULL,
  `personnel_pe_year` varchar(5) NOT NULL,
  `personnel_secondary_education` varchar(100) NOT NULL,
  `personnel_se_year` varchar(5) NOT NULL,
  `personnel_tertiary_education` varchar(100) NOT NULL,
  `personnel_bachelor_degree` varchar(100) NOT NULL,
  `personnel_te_year` varchar(5) NOT NULL,
  `personnel_graduate_school` varchar(100) NOT NULL,
  `personnel_masters_degree` varchar(100) NOT NULL,
  `personnel_gs_year` varchar(5) NOT NULL,
  `personnel_empid` varchar(20) NOT NULL,
  PRIMARY KEY (`personnel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`personnel_id`, `personnel_lname`, `personnel_fname`, `personnel_mname`, `personnel_bday`, `personnel_bplace`, `personnel_civilstatus`, `personnel_sex`, `personnel_address`, `personnel_email`, `personnel_contact_no`, `personnel_photo`, `personnel_primary_education`, `personnel_pe_year`, `personnel_secondary_education`, `personnel_se_year`, `personnel_tertiary_education`, `personnel_bachelor_degree`, `personnel_te_year`, `personnel_graduate_school`, `personnel_masters_degree`, `personnel_gs_year`, `personnel_empid`) VALUES
(18, 'Aguallo', 'Joy', 'Banzuela', '1996-05-10', 'Daraga, Albay', 'Single', 'Male', 'Aeroville, University Homes, Sagpon, Albay', 'lowe.ashdroid@gmail.com', '+639199067095', 'displaypic/1472461261.gif', 'EM''s Barrio Elementary School', '2008', 'Daraga National High School', '2012', 'Bicol University College of Science', 'Bachelor of Science in Information Technology', '', '', '', '', '2012-08341'),
(53, 'Tonga', 'Ricardo', 'Nunez', '1989-05-16', 'Albay', 'Single', 'Male', 'Sagpon, Albay', '', '', 'displaypic/1484651760.png', 'Raneses Elementary School', '1997', '', '', '', '', '', '', '', '', '2012-355346'),
(54, 'User', 'End', '', '1978-08-15', 'Daraga', 'Married', 'Male', 'F. Imperial Strret, Legazpi City', 'e.celedonio@gmail.com', '09196785647', 'displaypic/1484702599.png', 'Bicol University Elementary School', '1987', 'Bicol University High School', '1995', '', '', '', '', '', '', '1190023'),
(51, 'Pantoja', 'Marlyn', '', '1964-08-13', 'Albay', 'Single', 'Female', 'Legazpi City', '', '', 'displaypic/user.png', 'Bicol University Elementary School', '1972', 'Bicol University High School', '1986', 'Bicol University  CBEM', 'Bachelor of Science in Accounting', '1996', '', '', '', '2012-56942'),
(41, 'Lobete', 'Brigida', 'Springael', '1995-12-23', 'Daraga, Albay', 'Single', 'Female', 'BaÃ±ag, Daraga, Albay', 'maricar.aringo@bicol-u.edu.ph', '09194495687', 'displaypic/user.png', 'Binitayan Elementary School', '2008', 'Daraga National High School', '2012', 'Bicol University College of Science', 'Bachelor of Science in Information Technology', '', '', '', '', '2012-08884'),
(42, 'Aparaci', 'Gina', 'Middle', '2016-10-20', 'Daraga, Albay', 'Married', 'Female', 'Daraga, Albay', 'sample@error.com', '09982196661', 'displaypic/user.png', 'EM''s Barrio Elementary School', '2008', '', '', '', '', '', '', '', '', '2012-678543'),
(50, 'Gutierrez', 'Farah', 'Merciales', '1970-08-28', 'Daraga, Albay', 'Single', 'Female', 'Rizal St., Sagpon, daraga, Albay', 'farah.gutierrez@gmail.com', '09274187145', 'displaypic/user.png', 'Bicol University Pilot Elementary School', '1983', 'Bicol University School of Arts and Trades', '1987', 'Aquinas University', 'Bachelor of Science in Nursing', '1992', '', '', '', '2016-001-483'),
(46, 'Buitre', 'Artemio', 'Something', '2016-11-08', 'Albay', 'Single', 'Male', 'Albay', 'dfgdsg@fdsgwr.com', '45647457', 'displaypic/user.png', 'EM''s Barrio Elementary School', '2008', 'Daraga National High School', '2012', '', '', '', '', '', '', '2012-1234'),
(47, 'Mediona', 'Ruby', 'Mendioro', '1996-02-13', 'Albay', 'Single', 'Female', 'CI Mabuhay Village Pandan Daraga Albay', 'jessica.cimanes@bicol-u.edu.ph', '09197167155', 'displaypic/user.png', 'Busay Daraga Albay', '2008', 'Daraga National High School', '2012', 'Bicol University  College of Science', 'BS Information Technology', '2017', '', '', '', '2012-09783'),
(48, 'Moyo', 'Lace', 'Yap', '1985-10-27', 'Pilar sorsogon', 'Single', 'Female', '#057, Cabangan, Camalig, Albay', 'lacemiming@gmail.com', '0977542661', 'displaypic/user.png', 'Sunshine Learning Center', '1998', 'St.Agnes Academy', '2002', 'Aquinas University', 'BS Library Information Science', '2014', '', '', '', '2010-004-6'),
(49, 'Aragon', 'Delia', 'Relleta', '1957-01-19', 'Leg.City', 'Married', 'Female', 'F.Imperial St.Relo.Site,Bitano,Leg.City', 'draragon@gmail.com', '09179008713', 'displaypic/1484633053.jpg', 'Buraguis Elem. School', '1965', 'Divine Word High School', '1970', 'Divine Word College', 'Bachelor of Commerce major Banking & Finance', '1978', 'BU Graduate School', 'Master in Management', '1991', '1190005');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_position`
--

CREATE TABLE IF NOT EXISTS `personnel_position` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `position_name` varchar(100) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `personnel_position`
--

INSERT INTO `personnel_position` (`position_id`, `position_name`) VALUES
(1, 'Dean'),
(5, 'Assistant Dean'),
(6, 'Job Order'),
(7, 'OIC Dean'),
(8, 'Administrative Assistant'),
(21, 'Supply Officer'),
(17, 'Janitor'),
(24, 'Librarian'),
(18, 'Budget Officer'),
(20, 'Inspection Officer'),
(22, 'Administrative Aide'),
(23, 'BAC Officer'),
(25, 'On-Job Training'),
(26, 'Guidance Counselor');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_work_info`
--

CREATE TABLE IF NOT EXISTS `personnel_work_info` (
  `pwi_id` int(11) NOT NULL AUTO_INCREMENT,
  `personnel_id` bigint(20) NOT NULL,
  `position_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `dept_id` int(11) NOT NULL,
  PRIMARY KEY (`pwi_id`),
  KEY `personnel_id` (`personnel_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `personnel_work_info`
--

INSERT INTO `personnel_work_info` (`pwi_id`, `personnel_id`, `position_id`, `status`, `type`, `dept_id`) VALUES
(16, 18, 6, 'Non - Teaching', 'On Job Training', 6),
(49, 53, 8, 'Non - Teaching', 'Job Order', 10),
(50, 54, 22, 'Non - Teaching', 'Permanent', 3),
(47, 51, 18, 'Non - Teaching', 'Permanent', 13),
(46, 50, 22, 'Non - Teaching', 'Permanent', 4),
(45, 49, 21, 'Non - Teaching', 'Permanent', 6),
(44, 48, 6, 'Non - Teaching', 'Job Order', 5),
(43, 47, 1, 'Non - Teaching', 'Permanent', 1),
(42, 46, 20, 'Teaching', 'Permanent', 8),
(38, 42, 23, 'Teaching', 'Full Time', 7),
(37, 41, 26, 'Teaching', 'Full Time', 15);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE IF NOT EXISTS `purchase_order` (
  `po_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ponumber` varchar(100) NOT NULL,
  `supplier_id` bigint(20) NOT NULL,
  `podate` date NOT NULL,
  `modepayment` varchar(100) NOT NULL,
  `delivery_place` varchar(100) NOT NULL,
  `delivery_date` date NOT NULL,
  `orig_deliverydate` date NOT NULL,
  `delivery_term` int(11) NOT NULL,
  `orig_deliveryterm` int(11) NOT NULL,
  `ext_delterm` int(11) NOT NULL,
  `payment_term` varchar(100) NOT NULL,
  `allitem_nums` float NOT NULL,
  `orig_allitemnums` float NOT NULL,
  `pr_id` bigint(20) NOT NULL,
  `personnel_id` bigint(20) NOT NULL,
  `IARstat` varchar(10) NOT NULL,
  `ext_reason` varchar(1000) NOT NULL,
  `ext_penalty` float NOT NULL,
  PRIMARY KEY (`po_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`po_id`, `ponumber`, `supplier_id`, `podate`, `modepayment`, `delivery_place`, `delivery_date`, `orig_deliverydate`, `delivery_term`, `orig_deliveryterm`, `ext_delterm`, `payment_term`, `allitem_nums`, `orig_allitemnums`, `pr_id`, `personnel_id`, `IARstat`, `ext_reason`, `ext_penalty`) VALUES
(84, '2017-02-1', 1, '2017-02-07', 'Check', 'BUCN', '2017-02-12', '2017-02-12', 5, 5, 0, '', 1563.75, 1563.75, 146, 42, 'Complete', '', 0),
(85, '2017-02-2', 3, '2017-02-22', 'Check', 'BUCN', '2017-02-24', '2017-02-24', 2, 2, 0, '', 380.25, 380.25, 149, 42, '', '', 0),
(86, '2017-02-3', 3, '2017-02-22', 'Check', 'BUCN', '2017-02-25', '2017-02-24', 8, 2, 6, '', 1205.14, 1197.95, 150, 42, '', 'Some justifiable reasons....', 3.59385);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request`
--

CREATE TABLE IF NOT EXISTS `purchase_request` (
  `pr_id` int(11) NOT NULL AUTO_INCREMENT,
  `office_dept` varchar(100) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `prnum` varchar(100) NOT NULL,
  `sai_no` varchar(100) NOT NULL,
  `purpose` text NOT NULL,
  `pur_type` varchar(5) NOT NULL,
  `prdate` date NOT NULL,
  `personnel_id` bigint(20) NOT NULL,
  `pwi_id` int(11) NOT NULL,
  `iar_stat` varchar(20) NOT NULL,
  `ris_stat` varchar(20) NOT NULL,
  PRIMARY KEY (`pr_id`),
  KEY `dept_id` (`dept_id`),
  KEY `personnel_id` (`personnel_id`),
  KEY `pwi_id` (`pwi_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=151 ;

--
-- Dumping data for table `purchase_request`
--

INSERT INTO `purchase_request` (`pr_id`, `office_dept`, `dept_id`, `prnum`, `sai_no`, `purpose`, `pur_type`, `prdate`, `personnel_id`, `pwi_id`, `iar_stat`, `ris_stat`) VALUES
(149, 'BUCN', 6, '2017-02-1', '', 'For Store Room Needs', 'finv', '2017-02-22', 49, 45, '', ''),
(150, 'BUCN', 6, '2017-02-2', '', 'For office needs', 'fper', '2017-02-22', 49, 45, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_status`
--

CREATE TABLE IF NOT EXISTS `purchase_request_status` (
  `prstat_id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_status` varchar(64) NOT NULL,
  `confirmdate` date NOT NULL,
  `pr_id` bigint(20) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`prstat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=96 ;

-- --------------------------------------------------------

--
-- Table structure for table `request_issue_slip`
--

CREATE TABLE IF NOT EXISTS `request_issue_slip` (
  `ris_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `risnum` varchar(100) NOT NULL,
  `risdate` date NOT NULL,
  `pr_id` int(11) NOT NULL,
  `sai_no` varchar(100) NOT NULL,
  `sai_date` date NOT NULL,
  `requestedBy` bigint(20) NOT NULL,
  `approvedBy` bigint(20) NOT NULL,
  `issuedBy` bigint(20) NOT NULL,
  `receivedBy` bigint(20) NOT NULL,
  PRIMARY KEY (`ris_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `request_items`
--

CREATE TABLE IF NOT EXISTS `request_items` (
  `req_item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) NOT NULL,
  `item_unit_id` int(11) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `quantity` int(20) NOT NULL,
  `del_quantity` int(11) NOT NULL,
  `qty_orig` int(20) NOT NULL,
  `qty_approved` int(20) NOT NULL,
  `est_unit_cost` float NOT NULL,
  `est_total_cost` float NOT NULL,
  `pr_id` bigint(20) NOT NULL,
  `datecreated` datetime(6) NOT NULL,
  `pr_status` varchar(100) NOT NULL,
  `prstat_date` date NOT NULL,
  `remarks` text NOT NULL,
  `po_id` bigint(20) NOT NULL,
  `instat` varchar(64) NOT NULL,
  `ins_remarks` varchar(1000) NOT NULL,
  `insrmk_type` int(11) NOT NULL,
  `iar_id` bigint(20) NOT NULL,
  `accstat` varchar(64) NOT NULL,
  `su_id` int(11) NOT NULL,
  `issuance` varchar(10) NOT NULL,
  `icspar` varchar(5) NOT NULL,
  `icspar_id` bigint(20) NOT NULL,
  `ris_id` bigint(20) NOT NULL,
  PRIMARY KEY (`req_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=329 ;

--
-- Dumping data for table `request_items`
--

INSERT INTO `request_items` (`req_item_id`, `item_id`, `item_unit_id`, `description`, `quantity`, `del_quantity`, `qty_orig`, `qty_approved`, `est_unit_cost`, `est_total_cost`, `pr_id`, `datecreated`, `pr_status`, `prstat_date`, `remarks`, `po_id`, `instat`, `ins_remarks`, `insrmk_type`, `iar_id`, `accstat`, `su_id`, `issuance`, `icspar`, `icspar_id`, `ris_id`) VALUES
(323, 110, 36, 'HBW Black', 3, 0, 3, 3, 50.5, 151.5, 149, '2017-02-22 15:13:09.000000', 'approved', '2017-02-22', '', 85, 'Cancelled', 'out of stock', 0, 0, '', 0, '', '', 0, 0),
(324, 110, 36, 'Faber Castel', 5, 5, 5, 5, 45.75, 228.75, 149, '2017-02-22 15:13:09.000000', 'approved', '2017-02-22', '', 85, 'Complete', 'Done', 0, 0, '', 0, '', '', 0, 0),
(325, 110, 36, 'HBW Black', 5, 0, 5, 0, 56.5, 282.5, 150, '2017-02-22 16:06:01.000000', 'disapproved', '2017-02-22', 'Sample reason', 0, '', '', 0, 0, '', 0, '', '', 0, 0),
(326, 110, 36, 'Faber Castel', 5, 0, 5, 5, 45.75, 228.75, 150, '2017-02-22 16:06:01.000000', 'approved', '2017-02-22', '', 86, '', '', 0, 0, '', 0, '', '', 0, 0),
(327, 108, 31, 'Short Bond Paper', 8, 0, 8, 8, 58.65, 469.2, 150, '2017-02-22 16:06:01.000000', 'approved', '2017-02-22', '', 86, '', '', 0, 0, '', 0, '', '', 0, 0),
(328, 108, 31, 'Long Bond Paper', 8, 0, 8, 8, 62.5, 500, 150, '2017-02-22 16:06:01.000000', 'approved', '2017-02-22', '', 86, '', '', 0, 0, '', 0, '', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `requisition_status`
--

CREATE TABLE IF NOT EXISTS `requisition_status` (
  `reqstatus_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `po_id` bigint(20) NOT NULL,
  `iar_id` bigint(20) NOT NULL,
  `status` varchar(64) NOT NULL,
  `delivery_complete` datetime NOT NULL,
  `requestor` bigint(20) NOT NULL,
  PRIMARY KEY (`reqstatus_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dumping data for table `requisition_status`
--

INSERT INTO `requisition_status` (`reqstatus_id`, `po_id`, `iar_id`, `status`, `delivery_complete`, `requestor`) VALUES
(82, 86, 0, 'ordered', '0000-00-00 00:00:00', 49),
(81, 85, 0, 'Delivery Complete', '0000-00-00 00:00:00', 49);

-- --------------------------------------------------------

--
-- Table structure for table `stock_card`
--

CREATE TABLE IF NOT EXISTS `stock_card` (
  `stockcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `su_id` int(11) NOT NULL,
  `recdate` date NOT NULL,
  `reference` varchar(100) NOT NULL,
  `qty_receipt` int(11) NOT NULL,
  `issue_qty` int(11) NOT NULL,
  `personnel_id` bigint(20) NOT NULL,
  `issue_stock_bal` int(11) NOT NULL,
  PRIMARY KEY (`stockcard_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `stock_card`
--

INSERT INTO `stock_card` (`stockcard_id`, `su_id`, `recdate`, `reference`, `qty_receipt`, `issue_qty`, `personnel_id`, `issue_stock_bal`) VALUES
(54, 16, '2017-02-07', '2017-02-5', 10, 0, 0, 10),
(50, 12, '2017-02-07', '2017-02-1', 5, 0, 0, 5),
(51, 13, '2017-02-07', '2017-02-2', 8, 0, 0, 8),
(52, 14, '2017-02-07', '2017-02-3', 30, 0, 0, 30),
(53, 15, '2017-02-07', '2017-02-4', 8, 0, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `stock_items`
--

CREATE TABLE IF NOT EXISTS `stock_items` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_no` varchar(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `item_unit_id` int(11) NOT NULL,
  `stock_type` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `unit_cost` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_point` varchar(64) NOT NULL,
  PRIMARY KEY (`stock_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Dumping data for table `stock_items`
--

INSERT INTO `stock_items` (`stock_id`, `stock_no`, `item_id`, `item_unit_id`, `stock_type`, `description`, `unit_cost`, `quantity`, `order_point`) VALUES
(70, '', 108, 0, 'Supply', 'Short Bond Paper', 0, 0, ''),
(69, '', 108, 0, 'Supply', 'Long Bond Paper', 0, 0, '8'),
(68, '', 110, 0, 'Supply', 'Faber Castel', 0, 0, ''),
(67, '', 111, 0, 'Supply', 'Brand Scotch Tape', 0, 0, ''),
(66, '', 110, 0, 'Supply', 'HBW Black', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `stock_units`
--

CREATE TABLE IF NOT EXISTS `stock_units` (
  `su_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) NOT NULL,
  `stock_no` varchar(100) NOT NULL,
  `item_unit_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`su_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `stock_units`
--

INSERT INTO `stock_units` (`su_id`, `stock_id`, `stock_no`, `item_unit_id`, `price`, `quantity`) VALUES
(15, 69, '108-69-15', 31, 58, 8),
(16, 70, '108-70-16', 31, 58, 10),
(14, 68, '110-68-14', 30, 8.5, 30),
(13, 67, '111-67-13', 30, 4.5, 8),
(12, 66, '110-66-12', 36, 45.75, 5);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `supplier_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(65) NOT NULL,
  `supplier_tin_no` bigint(20) DEFAULT NULL,
  `supplier_contact_no` varchar(15) DEFAULT NULL,
  `supplier_address` varchar(10000) NOT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `supplier_tin_no`, `supplier_contact_no`, `supplier_address`) VALUES
(1, 'Lucky Educational Store Inc.', 78965, '2147483647', 'Oro Site, Legazpi City'),
(2, 'Hong Enterprises', 9784, '2147483647', 'F. Imperial St, Legazpi City'),
(3, 'Natinal Bookstore', 98453, '2147483647', 'Pacific Mall, Legazpi City'),
(4, 'Metro Gaisano', 65748, '2147483647', 'F. Imperial St, Legazpi City'),
(5, 'LCC Department Store', 45682, '2147483647', 'Legazpi City'),
(25, 'SM Mall Legazpi', 3456, '44573623631', 'Legazpi Terminal'),
(26, 'Denver''s Computer Inc.', 56742, '4534635753', 'Legazpi City'),
(27, 'Octagon Computer Shop', 23453, '34256346363', 'Legazpi City');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
