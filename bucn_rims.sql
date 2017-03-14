-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2017 at 05:06 PM
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `password`, `account_type`, `account_status`, `personnel_id`, `datecreated`) VALUES
(36, '368e1242f2d09b404f33d492405105ff', 'System Administrator', 'activated', 55, '2017-02-22 23:42:07.000000');

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
  `category_id` bigint(20) NOT NULL,
  `criticalimit` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_unit`
--

CREATE TABLE IF NOT EXISTS `item_unit` (
  `item_unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_unit_name` varchar(65) NOT NULL,
  PRIMARY KEY (`item_unit_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`personnel_id`, `personnel_lname`, `personnel_fname`, `personnel_mname`, `personnel_bday`, `personnel_bplace`, `personnel_civilstatus`, `personnel_sex`, `personnel_address`, `personnel_email`, `personnel_contact_no`, `personnel_photo`, `personnel_primary_education`, `personnel_pe_year`, `personnel_secondary_education`, `personnel_se_year`, `personnel_tertiary_education`, `personnel_bachelor_degree`, `personnel_te_year`, `personnel_graduate_school`, `personnel_masters_degree`, `personnel_gs_year`, `personnel_empid`) VALUES
(55, 'Administrator', 'System', '', '2017-01-01', 'Albay', 'Single', 'Male', 'Albay', '', '', 'displaypic/1487778128.png', 'Administrator School', '2017', '', '', '', '', '', '', '', '', '2017-0000');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_position`
--

CREATE TABLE IF NOT EXISTS `personnel_position` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `position_name` varchar(100) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `personnel_position`
--

INSERT INTO `personnel_position` (`position_id`, `position_name`) VALUES
(32, 'Dean'),
(31, 'Inspection Officer'),
(30, 'BAC Officer'),
(29, 'Budget Officer'),
(28, 'Supply Officer'),
(27, 'Administrator');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `personnel_work_info`
--

INSERT INTO `personnel_work_info` (`pwi_id`, `personnel_id`, `position_id`, `status`, `type`, `dept_id`) VALUES
(51, 55, 28, 'Non - Teaching', 'Permanent', 18);

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

-- --------------------------------------------------------

--
-- Table structure for table `requisition_status`
--

CREATE TABLE IF NOT EXISTS `requisition_status` (
  `reqstatus_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `po_id` bigint(20) NOT NULL,
  `iar_id` bigint(20) NOT NULL,
  `status` varchar(64) NOT NULL,
  `requestor` bigint(20) NOT NULL,
  PRIMARY KEY (`reqstatus_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `stock_items`
--

CREATE TABLE IF NOT EXISTS `stock_items` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) NOT NULL,
  `stock_type` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `order_point` int(11) NOT NULL,
  PRIMARY KEY (`stock_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
