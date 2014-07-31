-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2014 at 12:42 AM
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `wp_aha_assessment_q_options`
--

INSERT INTO `wp_aha_assessment_q_options` (`id`, `qid`, `value`, `label`, `followup_id`) VALUES
(1, '2.1.4.1.1', '1', 'Yes', '0'),
(2, '2.1.4.1.1', '0', 'No', '0'),
(3, '2.1.4.1.2', '1', 'Yes', '0'),
(4, '2.1.4.1.2', '0', 'No', '0'),
(5, '2.1.4.1.3', '1', 'Yes', '0'),
(6, '2.1.4.1.3', '0', 'No', '0'),
(7, '2.2.2.1', '1', 'Yes', '2.2.2.2'),
(8, '2.2.2.1', '0', 'No', '0'),
(9, '2.2.4.1', 'state', 'At the state level', '0'),
(10, '2.2.4.1', 'local', 'At the local level', '0'),
(11, '2.2.4.1', 'state and local', 'At the state and local level', '0'),
(12, '2.2.4.1', 'neither', 'Not a viable issue at this time', '0'),
(13, '2.2.5.1', 'no', 'No', '2.2.5.1.1'),
(14, '2.2.5.1', 'limited', 'Yes - limited use for certain partner organizations (Boy Scouts, Girl Scouts, etc.)', '2.2.5.1.3'),
(15, '2.2.5.1', 'broad', 'Yes - broader community use (community recreational use of school gymnasiums, track & field, etc.)', '2.2.5.1.3'),
(16, '2.2.5.1', 'other', 'Yes - other', '2.2.5.1.3'),
(17, '2.2.5.1.1', 'liability', 'Concerns about liability', '0'),
(18, '2.2.5.1.1', 'property damage', 'Concerns about property damage', '0'),
(19, '2.2.5.1.1', 'crime', 'Concerns about crime', '0'),
(20, '2.2.5.1.1', 'costs', 'Concerns about costs', '0'),
(21, '2.2.5.1.1', 'other', 'Other', '2.2.5.1.1.1'),
(22, '2.3.2.1', '1', 'Yes', '2.3.2.2'),
(23, '2.3.2.1', '0', 'No', '0'),
(24, '2.3.3', 'state', 'At the state level', '0'),
(25, '2.3.3', 'local', 'At the local level', '0'),
(26, '2.3.3', 'state and local', 'At the state and local level', '0'),
(27, '2.3.3', 'neither', 'Not a viable issue at this time', '0'),
(28, '3.1.3.1.0', '1', 'Yes', '3.1.3.1.1, 3.1.3.1.2, 3.1.3.1.3, 3.1.3.1.4'),
(29, '3.1.3.1.0', '0', 'No', '0'),
(30, '3.1.3.1.1', '1', 'Yes', '0'),
(31, '3.1.3.1.1', '0', 'No', '0'),
(32, '3.1.3.1.2', '1', 'Yes', '0'),
(33, '3.1.3.1.2', '0', 'No', '0'),
(34, '3.1.3.1.3', '1', 'Yes', '0'),
(35, '3.1.3.1.3', '0', 'No', '0'),
(36, '3.1.4', 'strengthen', 'Yes - Publish/strengthen the district wellness policy', '0'),
(37, '3.1.4', 'Yes publish', 'Yes – Publish / strengthen the district wellness policy', NULL),
(38, '3.1.4', 'Yes Open a door', 'Yes – Open a door for the Alliance for a Healthier Generation ', NULL),
(39, '3.1.4', 'TBD', 'TBD – more info needed', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
