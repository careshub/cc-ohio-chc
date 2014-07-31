# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.14)
# Database: commonsdev
# Generation Time: 2014-07-31 20:00:38 +0000
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `wp_aha_assessment_q_options` WRITE;
/*!40000 ALTER TABLE `wp_aha_assessment_q_options` DISABLE KEYS */;

INSERT INTO `wp_aha_assessment_q_options` (`id`, `qid`, `value`, `label`, `followup_id`)
VALUES
	(1,'2.1.4.1.1','1','Yes','0'),
	(2,'2.1.4.1.1','0','No','0'),
	(3,'2.1.4.1.2','1','Yes','0'),
	(4,'2.1.4.1.2','0','No','0'),
	(5,'2.1.4.1.3','1','Yes','0'),
	(6,'2.1.4.1.3','0','No','0'),
	(7,'2.2.2.1','1','Yes','2.2.2.2'),
	(8,'2.2.2.1','0','No','0'),
	(9,'2.2.4.1','state','At the state level','0'),
	(10,'2.2.4.1','local','At the local level','0'),
	(11,'2.2.4.1','state and local','At the state and local level','0'),
	(12,'2.2.4.1','neither','Not a viable issue at this time','0'),
	(13,'2.2.5.1','no','No','2.2.5.1.1'),
	(14,'2.2.5.1','limited','Yes - limited use for certain partner organizations (Boy Scouts, Girl Scouts, etc.)','2.2.5.1.3'),
	(15,'2.2.5.1','broad','Yes - broader community use (community recreational use of school gymnasiums, track & field, etc.)','2.2.5.1.3'),
	(16,'2.2.5.1','other','Yes - other','2.2.5.1.3'),
	(17,'2.2.5.1.1','liability','Concerns about liability','0'),
	(18,'2.2.5.1.1','property damage','Concerns about property damage','0'),
	(19,'2.2.5.1.1','crime','Concerns about crime','0'),
	(20,'2.2.5.1.1','costs','Concerns about costs','0'),
	(21,'2.2.5.1.1','other','Other','2.2.5.1.1.1'),
	(22,'2.3.2.1','1','Yes','2.3.2.2'),
	(23,'2.3.2.1','0','No','0'),
	(24,'2.3.3','state','At the state level','0'),
	(25,'2.3.3','local','At the local level','0'),
	(26,'2.3.3','state and local','At the state and local level','0'),
	(27,'2.3.3','neither','Not a viable issue at this time','0'),
	(28,'3.1.3.1.0','1','Yes','3.1.3.1.1, 3.1.3.1.2, 3.1.3.1.3, 3.1.3.1.4'),
	(29,'3.1.3.1.0','0','No','0'),
	(30,'3.1.3.1.1','1','Yes','0'),
	(31,'3.1.3.1.1','0','No','0'),
	(32,'3.1.3.1.2','1','Yes','0'),
	(33,'3.1.3.1.2','0','No','0'),
	(34,'3.1.3.1.3','1','Yes','0'),
	(35,'3.1.3.1.3','0','No','0'),
	(36,'3.1.4','strengthen','Yes - Publish/strengthen the district wellness policy','0');

/*!40000 ALTER TABLE `wp_aha_assessment_q_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wp_aha_assessment_questions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wp_aha_assessment_questions`;

CREATE TABLE `wp_aha_assessment_questions` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `page` tinyint(4) DEFAULT NULL,
  `QID` tinytext,
  `type` tinytext,
  `label` text,
  `loop_schools` tinyint(1) DEFAULT NULL,
  `follows_up` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `wp_aha_assessment_questions` WRITE;
/*!40000 ALTER TABLE `wp_aha_assessment_questions` DISABLE KEYS */;

INSERT INTO `wp_aha_assessment_questions` (`id`, `page`, `QID`, `type`, `label`, `loop_schools`, `follows_up`)
VALUES
	(1,2,'1.2.2.1','text','If your community has a local tobacco excise tax, what is the tax rate? If none, enter 0.',0,'0'),
	(2,3,'2.1.4.1.1','radio','In school district %%district_name%%, do schools meet our PE requirements?',1,'0'),
	(3,4,'2.2.2.1','radio','Does the state provide promotion, incentives, technical assistance or other resources to schools to encourage shared use?',0,'0'),
	(4,4,'2.2.2.2','textarea','Please describe:',0,'2.2.2.1'),
	(5,4,'2.2.4.1','radio','Given the current political/policy environment, where can this local board most likely help drive impact relative to shared use policies?',0,'0'),
	(6,5,'2.2.5.1','radio','In school district %%district_name%%, is there a district-wide policy and/or guidance in place for shared use of school facilities?',1,'0'),
	(7,5,'2.2.5.1.1','radio','What rationale was provided for not having a district-wide shared use policy?',1,'2.2.5.1'),
	(8,5,'2.2.5.1.1.1','text','If other, please describe:',1,'2.2.5.1.2'),
	(9,5,'2.2.5.1.3','text','If available, please provide a URL for the district shared use policy: ',1,'2.2.5.1'),
	(10,6,'2.3.2.1','radio','Is there a state, regional or local complete streets policy under consideration?',0,'0'),
	(11,6,'2.3.2.2','text','Who is leading this effort?',0,'2.3.2.1'),
	(12,6,'2.3.3','radio','Given the current political/policy environment, where do you envision complete streets public policy change most likely occurring/most activity taking place?',0,'0'),
	(13,7,'3.1.3.1.0','radio','In district %%district_name%%, is there a documented and publicly available district wellness policy in place?',1,'0'),
	(14,7,'3.1.3.1.1','radio','Does the policy meet the criteria related to school meals?',1,'3.1.3.1.0'),
	(15,7,'3.1.3.1.2','radio','Does the policy meet the criteria related to smart snacks?',1,'3.1.3.1.0'),
	(16,7,'3.1.3.1.3','radio','Does the policy meet the criteria related to before/after school offering?',1,'3.1.3.1.0'),
	(17,7,'3.1.3.1.4','text','Please provide the URL to the district\'s wellness policy. ',1,'3.1.3.1.0'),
	(18,7,'3.1.4','checkboxes','Would you recommend that the local board take action in any of these areas?',0,'0');

/*!40000 ALTER TABLE `wp_aha_assessment_questions` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
