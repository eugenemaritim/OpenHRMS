-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 13, 2012 at 09:51 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `openhrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `tc_bank`
--

CREATE TABLE IF NOT EXISTS `tc_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tc_bank`
--

INSERT INTO `tc_bank` (`id`, `name`, `description`) VALUES
(10, 'Sample Bank Name', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tc_benefit`
--

CREATE TABLE IF NOT EXISTS `tc_benefit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `amount` varchar(20) NOT NULL DEFAULT '0',
  `job_group` int(5) DEFAULT NULL,
  `is_active` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tc_benefit`
--

INSERT INTO `tc_benefit` (`id`, `name`, `description`, `amount`, `job_group`, `is_active`) VALUES
(7, 'Transport Allowance', 'This is a decription of transport Allowance', '12500', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tc_company`
--

CREATE TABLE IF NOT EXISTS `tc_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `tel_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tc_company`
--

INSERT INTO `tc_company` (`id`, `company_name`, `address`, `tel_no`) VALUES
(1, 'Sample Company Ltd', 'PO BOX 1452 - 545215, Fictional Address', '+254 737 481197');

-- --------------------------------------------------------

--
-- Table structure for table `tc_core_department`
--

CREATE TABLE IF NOT EXISTS `tc_core_department` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `is_active` int(2) NOT NULL DEFAULT '0',
  `member` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dept_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tc_core_department`
--

INSERT INTO `tc_core_department` (`dept_id`, `name`, `description`, `is_active`, `member`) VALUES
(1, 'Accounts and Administration', 'Accounts and Administration department', 0, '0'),
(3, 'Sales and Marketing', 'Sales and Marketing department', 0, '0'),
(4, 'Information Technology', 'Information Technology department', 1, '0'),
(5, 'Technical Department ', 'This is a description of Technical Department ', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(100) DEFAULT NULL,
  `other_name` varchar(150) DEFAULT NULL,
  `dept_id` int(2) NOT NULL DEFAULT '0',
  `job_group` int(5) DEFAULT NULL,
  `bank_id` int(5) DEFAULT NULL,
  `bank_branch` varchar(100) DEFAULT NULL,
  `account_number` varchar(150) DEFAULT NULL,
  `job_title` int(10) DEFAULT NULL,
  `gender` varchar(7) DEFAULT NULL,
  `contact_cell` varchar(20) DEFAULT NULL,
  `address` text,
  `county` varchar(100) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `village_estate` varchar(50) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_person_no` varchar(50) DEFAULT NULL,
  `national_id_no` int(15) DEFAULT NULL,
  `passport_no` varchar(15) DEFAULT NULL,
  `date_of_employment` date DEFAULT NULL,
  `is_active` int(2) DEFAULT '0',
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `tc_core_employee`
--

INSERT INTO `tc_core_employee` (`emp_id`, `surname`, `other_name`, `dept_id`, `job_group`, `bank_id`, `bank_branch`, `account_number`, `job_title`, `gender`, `contact_cell`, `address`, `county`, `location`, `village_estate`, `contact_person`, `contact_person_no`, `national_id_no`, `passport_no`, `date_of_employment`, `is_active`) VALUES
(13, 'Other', 'A. N', 5, 4, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2012-03-13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee_benefits`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) DEFAULT NULL,
  `benefit_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `tc_core_employee_benefits`
--

INSERT INTO `tc_core_employee_benefits` (`id`, `emp_id`, `benefit_id`) VALUES
(33, 13, 7);

-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee_comments`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(5) DEFAULT NULL,
  `by_id` int(5) DEFAULT NULL,
  `comment_date` date DEFAULT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tc_core_employee_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee_deductions`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee_deductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) DEFAULT NULL,
  `deduction_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tc_core_employee_deductions`
--

INSERT INTO `tc_core_employee_deductions` (`id`, `emp_id`, `deduction_id`) VALUES
(10, 13, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee_payroll`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee_payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) DEFAULT NULL,
  `period_id` int(5) DEFAULT NULL,
  `monthly_pay` varchar(20) DEFAULT NULL,
  `paye` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `tc_core_employee_payroll`
--


-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee_payroll_benefits`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee_payroll_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) DEFAULT NULL,
  `period_id` int(10) DEFAULT NULL,
  `benefit_name` varchar(100) DEFAULT NULL,
  `benefit_amount` varchar(50) DEFAULT NULL,
  `is_tax_exempt` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `tc_core_employee_payroll_benefits`
--


-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee_payroll_deductions`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee_payroll_deductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(5) DEFAULT NULL,
  `period_id` int(5) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `deduction_name` varchar(50) DEFAULT NULL,
  `deduction_amount` varchar(50) DEFAULT NULL,
  `is_pre_tax_exempt` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `tc_core_employee_payroll_deductions`
--


-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employee_payroll_income`
--

CREATE TABLE IF NOT EXISTS `tc_core_employee_payroll_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(5) DEFAULT NULL,
  `period_id` int(5) DEFAULT NULL,
  `income_name` varchar(50) DEFAULT NULL,
  `income_amount` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_tax_exempt` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tc_core_employee_payroll_income`
--


-- --------------------------------------------------------

--
-- Table structure for table `tc_core_employment_details`
--

CREATE TABLE IF NOT EXISTS `tc_core_employment_details` (
  `emp_id` int(10) NOT NULL DEFAULT '0',
  `payment_type` varchar(20) DEFAULT NULL,
  `payment_amount` varchar(20) DEFAULT NULL,
  `std_working_hours_day` varchar(10) DEFAULT NULL,
  `std_working_hours_period` varchar(10) DEFAULT NULL,
  `time_worked` varchar(20) DEFAULT NULL,
  `pay_overtime` int(2) DEFAULT NULL,
  `overtime_rate` varchar(10) DEFAULT NULL,
  `deduct_nssf` int(2) DEFAULT NULL,
  `nssf_no` varchar(50) DEFAULT NULL,
  `nhif_no` varchar(10) NOT NULL,
  `deduct_nhif` varchar(10) DEFAULT NULL,
  `bank_id` int(5) DEFAULT NULL,
  `bank_branch` varchar(100) DEFAULT NULL,
  `account_number` varchar(150) DEFAULT NULL,
  `is_tax_deductible` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tc_core_employment_details`
--

INSERT INTO `tc_core_employment_details` (`emp_id`, `payment_type`, `payment_amount`, `std_working_hours_day`, `std_working_hours_period`, `time_worked`, `pay_overtime`, `overtime_rate`, `deduct_nssf`, `nssf_no`, `nhif_no`, `deduct_nhif`, `bank_id`, `bank_branch`, `account_number`, `is_tax_deductible`) VALUES
(13, 'salary', '41111', '447', '4475', 'standard', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tc_core_role`
--

CREATE TABLE IF NOT EXISTS `tc_core_role` (
  `role_id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` varchar(255) NOT NULL,
  `locked` tinyint(4) NOT NULL,
  `users` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tc_core_role`
--

INSERT INTO `tc_core_role` (`role_id`, `name`, `description`, `locked`, `users`) VALUES
(3, 'accounts', 'Accounts department description', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tc_core_users`
--

CREATE TABLE IF NOT EXISTS `tc_core_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `logged_in_date` datetime DEFAULT NULL,
  `is_online` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tc_core_users`
--

INSERT INTO `tc_core_users` (`id`, `role_id`, `user_name`, `password`, `full_name`, `email`, `is_active`, `created_date`, `logged_in_date`, `is_online`) VALUES
(1, 3, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Sample Admin', 'info@openhrms.net', 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tc_deduction`
--

CREATE TABLE IF NOT EXISTS `tc_deduction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `amount` varchar(20) DEFAULT NULL,
  `job_group` int(5) DEFAULT NULL,
  `is_active` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tc_deduction`
--

INSERT INTO `tc_deduction` (`id`, `name`, `description`, `amount`, `job_group`, `is_active`) VALUES
(5, 'Breakages', 'Breakages', '4450', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tc_employee_payroll_transactions`
--

CREATE TABLE IF NOT EXISTS `tc_employee_payroll_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) DEFAULT NULL,
  `pay_period` int(10) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tc_employee_payroll_transactions`
--


-- --------------------------------------------------------

--
-- Table structure for table `tc_job_group`
--

CREATE TABLE IF NOT EXISTS `tc_job_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `is_active` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tc_job_group`
--

INSERT INTO `tc_job_group` (`id`, `name`, `description`, `is_active`) VALUES
(4, 'Job Group A', 'This is a description of Job group A', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tc_job_title`
--

CREATE TABLE IF NOT EXISTS `tc_job_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `is_active` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tc_job_title`
--

INSERT INTO `tc_job_title` (`id`, `name`, `description`, `is_active`) VALUES
(1, 'Chief Information Officer', 'This is a description of the chief information officer position', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tc_pay_period`
--

CREATE TABLE IF NOT EXISTS `tc_pay_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_year` int(4) DEFAULT NULL,
  `period_month` int(2) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `closed_by` varchar(50) NOT NULL,
  `close_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `tc_pay_period`
--

INSERT INTO `tc_pay_period` (`id`, `period_year`, `period_month`, `status`, `closed_by`, `close_date`) VALUES
(16, 2012, 3, 'current', '', '0000-00-00');