-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2014 at 12:15 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ccmembers`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_aha_assessment_q_options`
--

CREATE TABLE IF NOT EXISTS `wp_aha_assessment_q_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `qid` tinytext,
  `value` tinytext,
  `label` text,
  `followup_id` tinytext,
  `summary_value` tinytext NOT NULL,
  `summary_label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `wp_aha_assessment_q_options`
--

INSERT INTO `wp_aha_assessment_q_options` (`id`, `qid`, `value`, `label`, `followup_id`, `summary_value`, `summary_label`) VALUES
(1, '2.1.4.1.1', '1', 'Yes', '0', '0', ''),
(2, '2.1.4.1.1', '0', 'No', '0', '0', ''),
(3, '2.1.4.1.2', '1', 'Yes', '0', '0', ''),
(4, '2.1.4.1.2', '0', 'No', '0', '0', ''),
(5, '2.1.4.1.3', '1', 'Yes', '0', '0', ''),
(6, '2.1.4.1.3', '0', 'No', '0', '0', ''),
(7, '2.2.2.1', '1', 'Yes', '2.2.2.2', '0', ''),
(8, '2.2.2.1', '0', 'No', '0', '0', ''),
(9, '2.2.4.1', 'state', 'At the state level', '0', '0', ''),
(10, '2.2.4.1', 'local', 'At the local level', '0', '0', ''),
(11, '2.2.4.1', 'state and local', 'At the state and local level', '0', '0', ''),
(12, '2.2.4.1', 'neither', 'Not a viable issue at this time', '0', '0', ''),
(13, '2.2.5.1', 'no', 'No', '2.2.5.1.1', '0', ''),
(14, '2.2.5.1', 'limited', 'Yes - limited use for certain partner organizations (Boy Scouts, Girl Scouts, etc.)', '2.2.5.1.3', '0', ''),
(15, '2.2.5.1', 'broad', 'Yes - broader community use (community recreational use of school gymnasiums, track & field, etc.)', '2.2.5.1.3', '0', ''),
(16, '2.2.5.1', 'other', 'Yes - other', '2.2.5.1.3', '0', ''),
(17, '2.2.5.1.1', 'liability', 'Concerns about liability', '0', '0', ''),
(18, '2.2.5.1.1', 'property damage', 'Concerns about property damage', '0', '0', ''),
(19, '2.2.5.1.1', 'crime', 'Concerns about crime', '0', '0', ''),
(20, '2.2.5.1.1', 'costs', 'Concerns about costs', '0', '0', ''),
(21, '2.2.5.1.1', 'other', 'Other', '2.2.5.1.1.1', '0', ''),
(22, '2.3.2.1', '1', 'Yes', '2.3.2.2', '0', ''),
(23, '2.3.2.1', '0', 'No', '0', '0', ''),
(24, '2.3.3', 'state', 'At the state level', '0', '0', ''),
(25, '2.3.3', 'local', 'At the local level', '0', '0', ''),
(26, '2.3.3', 'state and local', 'At the state and local level', '0', '0', ''),
(27, '2.3.3', 'neither', 'Not a viable issue at this time', '0', '0', ''),
(28, '3.1.3.1.0', '1', 'Yes', '3.1.3.1.x', '0', ''),
(29, '3.1.3.1.0', '0', 'No', '0', '0', ''),
(30, '3.1.3.1.1', '1', 'Yes', '0', '0', ''),
(31, '3.1.3.1.1', '0', 'No', '0', '0', ''),
(32, '3.1.3.1.2', '1', 'Yes', '0', '0', ''),
(33, '3.1.3.1.2', '0', 'No', '0', '0', ''),
(34, '3.1.3.1.3', '1', 'Yes', '0', '0', ''),
(35, '3.1.3.1.3', '0', 'No', '0', '0', ''),
(36, '3.1.4', 'strengthen', 'Yes - Publish/strengthen the district wellness policy', '0', '0', ''),
(37, '3.1.4', 'open door', 'Yes – Open a door for the Alliance for a Healthier Generation ', '0', '0', ''),
(38, '3.1.4', 'after school', 'Strengthen competitive foods policy by applying nutrition standards to after-school activities', '0', '0', ''),
(39, '3.1.4', 'celebrations', 'Strengthen competitive foods policy by addressing celebrations and fundraisers', '0', '0', ''),
(40, '3.1.4', 'tbd', 'TBD - More info needed', '0', '0', ''),
(41, '3.2.1.1', '1', 'Yes', '0', '0', ''),
(43, '3.2.1.1', '0', 'No', '0', '0', ''),
(44, '3.2.2', '1', 'Yes', '0', 'Yes', 'Summary label for 3.2.2'),
(45, '3.2.2', '0', 'No', '0', '0', ''),
(46, '3.3.3.1', 'yes - exceed AHA standards', 'Yes, vending and/or service policies that meet or exceed AHA standards.', '3.3.3.2', '0', ''),
(47, '3.3.3.1', 'yes - below AHA standards', 'Yes, vending and/or service policies but below AHA standards.', '3.3.3.2', '0', ''),
(48, '3.3.3.1', 'no', 'No', '0', '0', ''),
(49, '3.3.4', 'state', 'At the state level', '0', '0', ''),
(50, '3.3.4', 'local', 'At the local level', '0', '0', ''),
(51, '3.3.4', 'state and local', 'At the state and local level', '0', '0', ''),
(52, '3.3.4', 'neither', 'Not a viable issue at this time', '0', '0', ''),
(53, '3.5.2', '1', 'Yes', '0', '0', ''),
(54, '3.5.2', '0', 'No', '0', '0', ''),
(55, '3.5.4', 'state', 'At the state level', '0', '0', ''),
(56, '3.5.4', 'local', 'At the local level', '0', '0', ''),
(57, '3.5.4', 'state and local', 'At the state and local level', '0', '0', ''),
(58, '3.5.4', 'neither', 'Not a viable issue at this time', '0', '0', ''),
(59, '4.1.4', 'yes - Medicaid expansion', 'Yes – Medicaid expansion at the state level', '0', '0', ''),
(60, '4.1.4', 'yes - USPSTF', 'Yes – USPSTF at the state level', '0', '0', ''),
(61, '4.1.4', 'no', 'Not a viable issue at this time', '0', '0', ''),
(62, '5.1.4.1', '1', 'Yes', '0', '0', ''),
(63, '5.1.4.1', '0', 'No', '0', '0', ''),
(64, '7.1.1', '1', 'Yes', '0', '0', ''),
(65, '7.1.1', '0', 'No', '7.1.2', '0', ''),
(66, '7.1.2.1', '1', 'Yes', '0', '0', ''),
(67, '7.1.2.1', '0', 'No', '0', '0', ''),
(68, '7.1.2.2', '1', 'Yes', '0', '0', ''),
(69, '7.1.2.2', '0', 'No', '0', '0', ''),
(70, '7.1.2.3', '1', 'Yes', '0', '0', ''),
(71, '7.1.2.3', '0', 'No', '0', '0', ''),
(72, '8.1.2', '1', 'Yes', '0', '0', ''),
(73, '8.1.2', '0', 'No', '0', '0', ''),
(74, '11.1.1', '1', 'Yes', '0', '0', ''),
(75, '11.1.1', '0', 'No', '0', '0', ''),
(76, '13.1.3', '1', 'Yes', '0', '0', ''),
(77, '13.1.3', '0', 'No', '0', '0', ''),
(78, '13.1.6', '1', 'Yes', '0', '0', ''),
(79, '13.1.6', '0', 'No', '0', '0', ''),
(80, '14.1.1', '1', 'Yes', '0', '0', ''),
(81, '14.1.1', '0', 'No', '0', '0', ''),
(82, '14.1.2', '1', 'Yes', '0', '0', ''),
(83, '14.1.2', '0', 'No', '0', '0', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
