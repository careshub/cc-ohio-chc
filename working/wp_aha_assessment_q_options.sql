# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.14)
# Database: commonsdev
# Generation Time: 2014-09-02 18:13:42 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table wp_aha_assessment_q_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wp_aha_assessment_q_options`;

CREATE TABLE `wp_aha_assessment_q_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `qid` tinytext,
  `value` tinytext,
  `label` text,
  `followup_id` tinytext,
  `summary_value` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `wp_aha_assessment_q_options` WRITE;
/*!40000 ALTER TABLE `wp_aha_assessment_q_options` DISABLE KEYS */;

INSERT INTO `wp_aha_assessment_q_options` (`id`, `qid`, `value`, `label`, `followup_id`, `summary_value`)
VALUES
	(1,'2.1.4.1.1','1','Yes','0',''),
	(2,'2.1.4.1.1','0','No','0',''),
	(3,'2.1.4.1.1','','Don\'t Know','0',''),
	(4,'2.1.4.1.2','1','Yes','0','0'),
	(5,'2.1.4.1.2','0','No','0','0'),
	(6,'2.1.4.1.2','','Don\'t Know','0',''),
	(7,'2.1.4.1.3','1','Yes','0','0'),
	(8,'2.1.4.1.3','0','No','0','0'),
	(9,'2.1.4.1.3','','Don\'t Know','0',''),
	(10,'2.2.2.1','1','Yes','2.2.2.2','0'),
	(11,'2.2.2.1','0','No','0','0'),
	(12,'2.2.4.1','state','At the state level','0','0'),
	(13,'2.2.4.1','local','At the local level','0','0'),
	(14,'2.2.4.1','state and local','At the state and local level','0','0'),
	(15,'2.2.4.1','neither','Not a viable issue at this time','0','0'),
	(16,'2.2.5.1','no','No','2.2.5.1.1','0'),
	(17,'2.2.5.1','limited','Yes - limited use for certain partner organizations ( Scouts, Girl Scouts, etc.)','2.2.5.1.3','0'),
	(18,'2.2.5.1','broad','Yes - broader community use (community recreational use of school gymnasiums, track & field, etc.)','2.2.5.1.3','0'),
	(19,'2.2.5.1','other','Yes - other','2.2.5.1.3','0'),
	(20,'2.2.5.1','unknown','Don\'t Know','0',''),
	(21,'2.2.5.1.1','liability','Concerns about liability','0','0'),
	(22,'2.2.5.1.1','property damage','Concerns about property damage','0','0'),
	(23,'2.2.5.1.1','crime','Concerns about crime','0','0'),
	(24,'2.2.5.1.1','costs','Concerns about costs','0','0'),
	(25,'2.2.5.1.1','other','Other','2.2.5.1.1.1','0'),
	(26,'2.2.5.1.1','unknown','Don\'t Know','0',''),
	(27,'2.3.1.1','meets guidelines','Yes, the policy exceeds AHA guidelines.','0',''),
	(28,'2.3.1.1','below guidelines','Yes, but the policy does not meet AHA guidelines.','0',''),
	(29,'2.3.1.1','no','No','0',''),
	(30,'2.3.2.1','1','Yes','2.3.2.2','0'),
	(31,'2.3.2.1','0','No','0','0'),
	(32,'2.3.3','state','At the state level','0','0'),
	(33,'2.3.3','local','At the local level','0','0'),
	(34,'2.3.3','state and local','At the state and local level','0','0'),
	(35,'2.3.3','neither','Not a viable issue at this time','0','0'),
	(36,'3.1.3.1.0','1','Yes','3.1.3.1.x','0'),
	(37,'3.1.3.1.0','0','No','0','0'),
	(38,'3.1.3.1.0','','Don\'t Know','0',''),
	(39,'3.1.3.1.1','1','Yes','0','0'),
	(40,'3.1.3.1.1','0','No','0','0'),
	(41,'3.1.3.1.1','','Don\'t Know','0',''),
	(42,'3.1.3.1.2','1','Yes','0','0'),
	(43,'3.1.3.1.2','0','No','0','0'),
	(44,'3.1.3.1.2','','Don\'t Know','0',''),
	(45,'3.1.3.1.3','1','Yes','0','0'),
	(46,'3.1.3.1.3','0','No','0','0'),
	(47,'3.1.3.1.3','','Don\'t Know','0',''),
	(48,'3.1.4','strengthen','Yes - Publish/strengthen the district wellness policy','0','0'),
	(49,'3.1.4','open door','Yes – Open a door for the Alliance for a Healthier Generation ','0','0'),
	(50,'3.1.4','after school','Strengthen competitive foods policy by applying nutrition standards to after-school activities','0','0'),
	(51,'3.1.4','celebrations','Strengthen competitive foods policy by addressing celebrations and fundraisers','0','0'),
	(52,'3.1.4','tbd','TBD - More info needed','0','0'),
	(53,'3.2.1.1','1','Yes','0','0'),
	(54,'3.2.1.1','0','No','0','0'),
	(55,'3.2.2','1','Yes','0','Yes'),
	(56,'3.2.2','0','No','0','0'),
	(57,'3.3.3.1','yes - exceed AHA standards','Yes, vending and/or service policies that meet or exceed AHA standards.','3.3.3.2','0'),
	(58,'3.3.3.1','yes - below AHA standards','Yes, vending and/or service policies but below AHA standards.','3.3.3.2','0'),
	(59,'3.3.3.1','no','No','0','0'),
	(60,'3.3.4','state','At the state level','0','0'),
	(61,'3.3.4','local','At the local level','0','0'),
	(62,'3.3.4','state and local','At the state and local level','0','0'),
	(63,'3.3.4','neither','Not a viable issue at this time','0','0'),
	(64,'3.5.2','1','Yes','0','0'),
	(65,'3.5.2','0','No','0','0'),
	(66,'3.5.4','state','At the state level','0','0'),
	(67,'3.5.4','local','At the local level','0','0'),
	(68,'3.5.4','state and local','At the state and local level','0','0'),
	(69,'3.5.4','neither','Not a viable issue at this time','0','0'),
	(70,'4.1.4','yes - Medicaid expansion','Yes – Medicaid expansion at the state level','0','0'),
	(71,'4.1.4','yes - USPSTF','Yes – USPSTF at the state level','0','0'),
	(72,'4.1.4','no','Not a viable issue at this time','0','0'),
	(73,'5.1.4.1','1','Yes','0','0'),
	(74,'5.1.4.1','0','No','0','0'),
	(75,'7.1.1','1','Yes','0','0'),
	(76,'7.1.1','0','No','7.1.1.1','0'),
	(77,'7.1.2','1','Yes','0','0'),
	(78,'7.1.2','0','No','7.1.2.1','0'),
	(79,'7.1.3','1','Yes','0','0'),
	(80,'7.1.3','0','No','7.1.3.1','0'),
	(81,'7.1.4.1','1','Yes','0','0'),
	(82,'7.1.4.1','0','No','0','0'),
	(83,'7.1.4.2','1','Yes','0','0'),
	(84,'7.1.4.2','0','No','0','0'),
	(85,'7.1.4.3','1','Yes','0','0'),
	(86,'7.1.4.3','0','No','0','0'),
	(87,'8.1.2','1','Yes','0','0'),
	(88,'8.1.2','0','No','0','0'),
	(89,'11.1.1','1','Yes','0','0'),
	(90,'11.1.1','0','No','0','0'),
	(91,'13.1.3','1','Yes','0','0'),
	(92,'13.1.3','0','No','0','0'),
	(93,'13.1.6','1','Yes','0','0'),
	(94,'13.1.6','0','No','0','0'),
	(95,'14.1.1','1','Yes','0','0'),
	(96,'14.1.1','0','No','0','0'),
	(97,'14.1.2','1','Yes','0','0'),
	(98,'14.1.2','0','No','0','0');

/*!40000 ALTER TABLE `wp_aha_assessment_q_options` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
