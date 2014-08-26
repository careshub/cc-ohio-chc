-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2014 at 07:11 PM
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
-- Table structure for table `wp_aha_assessment_questions`
--

CREATE TABLE IF NOT EXISTS `wp_aha_assessment_questions` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `page` tinyint(4) DEFAULT NULL,
  `QID` tinytext,
  `type` tinytext,
  `label` text,
  `loop_schools` tinyint(1) DEFAULT NULL,
  `follows_up` tinytext,
  `summary_section` tinytext,
  `summary_label` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `wp_aha_assessment_questions`
--

INSERT INTO `wp_aha_assessment_questions` (`id`, `page`, `QID`, `type`, `label`, `loop_schools`, `follows_up`, `summary_section`, `summary_label`) VALUES
(1, 0, '1.2.2.1', 'number', 'If your community has a local tobacco excise tax, what is the tax rate? If none, enter 0.', 0, '0', 'comm_tobacco_2', NULL),
(2, 0, '2.1.4.1.1', 'radio', 'In school district %%district_name%%, do schools meet our PE requirements?', 1, '0', 'school_phys_1', 'Your state %%response%% not preempt local communities from adopting their own clean indoor air laws.'),
(3, 0, '2.2.2.1', 'radio', 'Does the state provide promotion, incentives, technical assistance or other resources to schools to encourage shared use?', 0, '0', 'school_phys_2', NULL),
(4, 0, '2.2.2.2', 'textarea', 'Please describe:', 0, '2.2.2.1', NULL, NULL),
(5, 0, '2.2.4.1', 'radio', 'Given the current political/policy environment, where can this local board most likely help drive impact relative to shared use policies?', 0, '0', NULL, NULL),
(6, 0, '2.2.5.1', 'radio', 'In school district %%district_name%%, is there a district-wide policy and/or guidance in place for shared use of school facilities?', 1, '0', NULL, NULL),
(7, 0, '2.2.5.1.1', 'radio', 'What rationale was provided for not having a district-wide shared use policy?', 1, '2.2.5.1', NULL, NULL),
(8, 0, '2.2.5.1.1.1', 'text', 'If other, please describe:', 1, '2.2.5.1.1', NULL, NULL),
(9, 0, '2.2.5.1.3', 'text', 'If available, please provide a URL for the district shared use policy: ', 1, '2.2.5.1', NULL, NULL),
(10, 2, '2.3.2.1', 'radio', 'Is there a state, regional or local complete streets policy under consideration?', 0, '0', NULL, NULL),
(11, 2, '2.3.2.2', 'text', 'Who is leading this effort?', 0, '2.3.2.1', NULL, NULL),
(12, 2, '2.3.3', 'radio', 'Given the current political/policy environment, where do you envision complete streets public policy change most likely occurring/most activity taking place?', 0, '0', NULL, NULL),
(13, 0, '3.1.3.1.0', 'radio', 'In district %%district_name%%, is there a documented and publicly available district wellness policy in place?', 1, '0', NULL, NULL),
(14, 0, '3.1.3.1.1', 'radio', 'Does the policy meet the criteria related to school meals?', 1, '3.1.3.1.0', NULL, NULL),
(15, 0, '3.1.3.1.2', 'radio', 'Does the policy meet the criteria related to smart snacks?', 1, '3.1.3.1.0', NULL, NULL),
(16, 0, '3.1.3.1.3', 'radio', 'Does the policy meet the criteria related to before/after school offering?', 1, '3.1.3.1.0', NULL, NULL),
(17, 0, '3.1.3.1.4', 'text', 'Please provide the URL to the district''s wellness policy. ', 1, '3.1.3.1.0', NULL, NULL),
(18, 0, '3.1.4', 'checkboxes', 'Would you recommend that the local board take action in any of these areas?', 0, '0', NULL, NULL),
(19, 0, '3.2.1.1', 'radio', 'Is district %%district_name%% compliant with the School Meals Nutrition regulations?  ', 1, '0', NULL, NULL),
(20, 0, '3.2.2', 'radio', 'Are there impactful opportunities for the local board to help school districts implement School Meals Nutrition regulations?', 0, '0', NULL, NULL),
(21, 3, '3.3.3.1', 'radio', 'Are there local vending and/or service policies in place?', 0, '0', NULL, NULL),
(22, 3, '3.3.3.2', 'textarea', 'Please list which cities or counties.', 0, '3.3.3.1', NULL, NULL),
(23, 3, '3.3.4', 'radio', 'Given the current political/policy environment, where do you envision food and beverage vending and/or procurement service policy change most likely occurring/most activity taking place?', 0, '0', NULL, NULL),
(24, 4, '3.5.2', 'radio', 'Is your state or community pursuing an appropriation to establish or supplement a Healthy Food Financing Initiative program?', 0, '0', NULL, NULL),
(25, 4, '3.5.4', 'radio', 'Given the current political/policy environment, where do you envision Healthy Food Financing public policy change most likely occurring/most activity taking place?', 0, '0', NULL, NULL),
(26, 5, '4.1.4', 'radio', 'Would you recommend the local board adopt this as a priority issue?', 0, '0', NULL, NULL),
(27, 6, '5.1.4.1', 'radio', 'For %%district_name%%, is CPR training meeting AHA criteria a graduation requirement?', 1, '0', NULL, NULL),
(28, 7, '7.1.1', 'radio', 'Do you have all your event chairs for FY 14-15?', 0, '0', NULL, NULL),
(29, 7, '7.1.1.1', 'textarea', 'List unfilled event chairs', 0, '7.1.1', NULL, NULL),
(30, 7, '7.1.2', 'radio', 'Do you have all your event chairs for FY 15-16?', 0, '0', NULL, NULL),
(31, 7, '7.1.2.1', 'textarea', 'List unfilled event chairs', 0, '7.1.2', NULL, NULL),
(32, 7, '7.1.3', 'radio', 'Do you have all your event chairs for FY 16-17?', 0, '0', NULL, NULL),
(33, 7, '7.1.3.1', 'textarea', 'List unfilled event chairs', 0, '7.1.3', NULL, NULL),
(34, 7, '7.1.4.1', 'radio', 'In 2014-2015 have the Event Chairs given at the Top 2 levels for Go Red?', 0, '0', NULL, NULL),
(35, 7, '7.1.4.2', 'radio', 'In 2014-2015 have the Event Chairs given at the Top 2 levels for Heart Ball?', 0, '0', NULL, NULL),
(36, 7, '7.1.4.3', 'radio', 'In 2014-2015 have the Event Chairs given at the Top 2 levels for Heart Walk?', 0, '0', NULL, NULL),
(37, 8, '8.1.2', 'radio', 'Do you have all the industries represented? (Accounting, Banking, Energy, Cable, Healthcare, Media, Lawyers, Manufacturing, Real Estate, etc.)', 0, '0', NULL, NULL),
(38, 8, '8.1.5', 'number', 'How many ELT members haven''t given a corporate gift?', 0, '0', NULL, NULL),
(39, 10, '9.4', 'textarea', 'Which companies in your community place a focus on corporate social responsibility?', 0, '0', NULL, NULL),
(40, 11, '11.1.1', 'radio', 'Do we currently have Superintendents’ support within %%district_name%% covered by this board? ', 1, '0', NULL, NULL),
(41, 11, '11.1.2', 'number', 'How much does %%district_name%% raise?', 1, '0', NULL, NULL),
(42, 12, '12.1.2', 'number', 'How many $100k donors do we have in the pipeline?', 0, '0', NULL, NULL),
(43, 13, '12.2.1', 'number', 'What percentage of board members are currently a Cor Vitae member?', 0, '0', NULL, NULL),
(44, 13, '12.2.2', 'number', 'How many Cor Vitae members are in your market?', 0, '0', NULL, NULL),
(45, 13, '12.2.3', 'textarea', 'How are you retaining your Cor Vitae members?', 0, '0', NULL, NULL),
(46, 14, '13.1.2', 'textarea', 'How are you acknowledging these donors?', 0, '0', NULL, NULL),
(47, 14, '13.1.3', 'radio', 'Do you have a current list of stewardship events in your market?', 0, '0', NULL, NULL),
(48, 14, '13.1.6', 'radio', 'Are you following a cultivation plan?', 0, '0', NULL, NULL),
(49, 15, '14.1.1', 'radio', 'Does your board have knowledge of the Paul Dudley White Legacy Society program?', 0, '0', NULL, NULL),
(50, 15, '14.1.2', 'radio', 'Does your market have a Paul Dudley White Legacy Society Champion connected to the board and/or a board member?', 0, '0', NULL, NULL),
(51, 15, '14.1.3', 'number', 'What percentage of board members are currently Paul Dudley White Legacy Society?', 0, '0', NULL, NULL),
(52, 0, '1', NULL, NULL, 0, NULL, 'community_tobacco_1', '<strong>Is this a priority at the state level?</strong>\r\n	<ul>\r\n		<li>Are their state legislators from this community who are key targets that we need to influence for supporting a state-wide campaign?</li>\r\n		<li>Do the board members or other AHA volunteers have relationships with these key targets and legislators?</li>\r\n		<li>Do the board members have relationships with key opinion leaders (key business leaders, members of the media, etc.) with the community to help support this effort?</li>\r\n		<li>What’s the current level of grassroots activity in the community to support this effort?</li>\r\n	</ul>'),
(53, NULL, '1', NULL, NULL, 0, NULL, 'care_factors_1', '<strong>Does the community have capacity to take on this issue?</strong>\r\n	<ul>\r\n		<li>Is there an active campaign around this issue in the community?</li>\r\n		<li>What potential and existing coalition partners are in place?</li>\r\n		<li>What is the current political climate?</li>\r\n		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?</li>\r\n		<li>Is there any external funding available to do the work?</li>\r\n		<li>Are there state legislators from this community that we need to engage for the statewide campaign?</li>\r\n		<li>What is the Grassroots capacity of the board to engage in the opportunity?</li>\r\n	</ul>'),
(55, 0, '2', NULL, NULL, 0, NULL, 'community_tobacco_1', '<strong>Does the community have capacity to take on this issue?</strong>\r\n	<ul>\r\n		<li>What potential coalition partners (such as ACS/ALA, local hospitals, medical association, etc.) community partners (neighborhood groups, PTO, church groups, etc.) are in place?  </li>\r\n		<li>What is the current political climate?</li>\r\n		<li>What is the likelihood of the current council support for clean indoor air law?  </li>\r\n		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?</li>\r\n		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants, etc.)</li>\r\n	</ul>'),
(56, NULL, '1', NULL, NULL, 0, NULL, 'care_acute_1', '<ul>\r\n<li>Based upon the list of the most penalized hospitals in your community, are there board members that can help open the door to provide AHA quality improvement solutions for these hospitals?</li>\r\n<li>Are there additional hospitals that the market has prioritized for which board members can help open the door for AHA’s quality programs?</li>\r\n<li>Are there other healthcare quality improvement gaps that the Board can help address?</li></ul>');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
