/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

DROP TABLE IF EXISTS `automation`;
CREATE TABLE IF NOT EXISTS `automation` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL DEFAULT '0',
  `last_run` datetime NOT NULL,
  `last_run_1` date NOT NULL,
  `last_run_2` date NOT NULL,
  `last_run_3` date NOT NULL,
  `runtime_1` varchar(5) NOT NULL,
  `runtime_2` varchar(5) NOT NULL,
  `runtime_3` varchar(5) NOT NULL,
  `purge_items` int(2) NOT NULL DEFAULT '0',
  `purge_categories` int(2) NOT NULL DEFAULT '0',
  `purge_groups` int(2) NOT NULL DEFAULT '0',
  `purge_departments` int(2) NOT NULL DEFAULT '0',
  `purge_customers` int(2) NOT NULL DEFAULT '0',
  `purge_salespersons` int(2) NOT NULL DEFAULT '0',
  `purge_currencies` int(2) NOT NULL DEFAULT '0',
  `purge_locations` int(2) NOT NULL DEFAULT '0',
  `purge_addresses` int(2) NOT NULL DEFAULT '0',
  `purge_item_images` int(2) NOT NULL,
  `stock_status` int(2) NOT NULL DEFAULT '0',
  `stock_last_run` datetime NOT NULL,
  `stock_last_run_1` date NOT NULL,
  `stock_last_run_2` date NOT NULL,
  `stock_last_run_3` date NOT NULL,
  `stock_runtime_1` varchar(5) NOT NULL,
  `stock_runtime_2` varchar(5) NOT NULL,
  `stock_runtime_3` varchar(5) NOT NULL,
  `history_prices_status` int(2) NOT NULL,
  `history_prices_last_run` datetime NOT NULL,
  `history_prices_last_run_1` date NOT NULL,
  `history_prices_runtime_1` varchar(5) NOT NULL,
  `history_prices_mode` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DELETE FROM `automation`;
/*!40000 ALTER TABLE `automation` DISABLE KEYS */;
INSERT INTO `automation` (`id`, `status`, `last_run`, `last_run_1`, `last_run_2`, `last_run_3`, `runtime_1`, `runtime_2`, `runtime_3`, `purge_items`, `purge_categories`, `purge_groups`, `purge_departments`, `purge_customers`, `purge_salespersons`, `purge_currencies`, `purge_locations`, `purge_addresses`, `purge_item_images`, `stock_status`, `stock_last_run`, `stock_last_run_1`, `stock_last_run_2`, `stock_last_run_3`, `stock_runtime_1`, `stock_runtime_2`, `stock_runtime_3`, `history_prices_status`, `history_prices_last_run`, `history_prices_last_run_1`, `history_prices_runtime_1`, `history_prices_mode`) VALUES
	(1, 0, '2016-01-01 00:00:00', '2016-01-01', '2016-01-01', '2016-01-01', '-1', '-1', '-1', 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, '2016-01-01 00:00:00', '2016-01-01', '2016-01-01', '2016-01-01', '-1', '-1', '-1', 0, '2016-01-01 00:00:00', '2016-01-01', '-1', '');
/*!40000 ALTER TABLE `automation` ENABLE KEYS */;

DROP TABLE IF EXISTS `banner`;
CREATE TABLE IF NOT EXISTS `banner` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `path` varchar(200) NOT NULL,
  `caption` mediumtext NOT NULL,
  `link` mediumtext NOT NULL,
  `effect` varchar(200) NOT NULL,
  `order` int(10) NOT NULL,
  `type` varchar(200) NOT NULL,
  `status` int(10) NOT NULL,
  `remarks` text NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DELETE FROM `banner`;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
INSERT INTO `banner` (`id`, `path`, `caption`, `link`, `effect`, `order`, `type`, `status`, `remarks`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'banner1.png', '', '', '', 0, 'Login Screen', 1, '', 1, '2016-04-22 09:42:07', 0, '0000-00-00 00:00:00'),
	(2, 'banner2.png', '', '', '', 0, 'Login Screen', 1, '', 1, '2016-04-22 09:42:19', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_additional_field`;
CREATE TABLE IF NOT EXISTS `sys_additional_field` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sys_module_id` int(10) NOT NULL,
  `module_uid` varchar(150) NOT NULL,
  `cf_type` varchar(150) NOT NULL DEFAULT '',
  `cf_status` tinyint(1) NOT NULL DEFAULT '1',
  `cf_label` varchar(250) NOT NULL,
  `cf_code` varchar(250) NOT NULL DEFAULT '',
  `cf_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cf type` (`cf_type`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

DELETE FROM `sys_additional_field`;
/*!40000 ALTER TABLE `sys_additional_field` DISABLE KEYS */;
INSERT INTO `sys_additional_field` (`id`, `sys_module_id`, `module_uid`, `cf_type`, `cf_status`, `cf_label`, `cf_code`, `cf_mandatory`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 51, 'customer', 'dropbox', 1, 'Area', 'area', 0, 1, '2014-02-11 17:42:25', 1, '2014-02-17 17:25:47'),
	(3, 51, 'customer', 'dropbox', 1, 'Sales Potential', 'sales_potential', 0, 1, '2014-02-11 22:18:19', 8, '2014-02-12 01:12:42'),
	(2, 51, 'customer', 'dropbox', 1, 'Business Type', 'business', 0, 1, '2014-02-11 17:46:56', 8, '2014-02-11 22:49:09'),
	(4, 60, 'contacts', 'dropbox', 1, 'Salutation', 'salutation', 0, 1, '2014-02-11 17:46:56', 8, '2014-02-11 22:49:09'),
	(11, 51, 'customer', 'dropbox', 1, 'Relationship', 'relationship', 0, 1, '2014-05-28 17:42:25', 1, '2014-05-28 17:25:47'),
	(12, 390, 'service_desk.slas', 'dropbox', 1, 'Type', 'type', 0, 1, '2015-02-27 00:00:00', 0, '0000-00-00 00:00:00'),
	(13, 390, 'service_desk.slas', 'dropbox', 1, 'Impact', 'impact', 0, 1, '2015-03-12 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_additional_field` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_additional_field_content`;
CREATE TABLE IF NOT EXISTS `sys_additional_field_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cf_id` int(10) NOT NULL,
  `cf_content_label` varchar(250) NOT NULL DEFAULT '',
  `cf_content_value` varchar(250) NOT NULL DEFAULT '',
  `cf_content_order` int(10) NOT NULL DEFAULT '0',
  `cf_remark` varchar(150) NOT NULL DEFAULT '',
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

DELETE FROM `sys_additional_field_content`;
/*!40000 ALTER TABLE `sys_additional_field_content` DISABLE KEYS */;
INSERT INTO `sys_additional_field_content` (`id`, `cf_id`, `cf_content_label`, `cf_content_value`, `cf_content_order`, `cf_remark`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(19, 2, 'bt2', 'bt2', 0, '', 8, '2014-02-11 23:28:15', 0, '0000-00-00 00:00:00'),
	(18, 2, 'bt1', 'bt1', 0, '', 8, '2014-02-11 23:28:10', 0, '0000-00-00 00:00:00'),
	(31, 1, 'area51', 'area51', 0, '', 1, '2014-02-18 16:12:00', 1, '2014-02-18 16:12:05'),
	(20, 2, 'bt3', 'bt3', 0, '', 8, '2014-02-11 23:28:20', 0, '0000-00-00 00:00:00'),
	(21, 2, 'bt4', 'bt4', 0, '', 8, '2014-02-11 23:28:25', 0, '0000-00-00 00:00:00'),
	(22, 3, 'sp1', '1000', 0, '', 8, '2014-02-11 23:28:42', 8, '2014-02-11 23:41:53'),
	(23, 3, 'sp2', '2000', 0, '', 8, '2014-02-11 23:28:48', 8, '2014-02-11 23:41:58'),
	(24, 3, 'sp3', '3000', 0, '', 8, '2014-02-11 23:28:53', 8, '2014-02-11 23:42:04'),
	(25, 3, 'sp4', '4000', 0, '', 8, '2014-02-11 23:28:56', 8, '2014-02-11 23:42:09'),
	(27, 1, 'area1', 'area1', 0, '', 8, '2014-02-11 23:34:30', 0, '0000-00-00 00:00:00'),
	(28, 1, 'area2', 'area2', 0, '', 8, '2014-02-11 23:34:34', 0, '0000-00-00 00:00:00'),
	(29, 1, 'area3', 'area3', 0, '', 8, '2014-02-11 23:34:38', 0, '0000-00-00 00:00:00'),
	(30, 1, 'area4', 'area4', 0, '', 8, '2014-02-11 23:34:42', 8, '2014-02-11 23:34:49'),
	(32, 4, 'Mr.', 'Mr.', 1, '', 1, '2014-02-24 11:00:08', 0, '0000-00-00 00:00:00'),
	(33, 4, 'Ms.', 'Ms.', 2, '', 1, '2014-02-24 11:00:20', 0, '0000-00-00 00:00:00'),
	(34, 4, 'Mrs.', 'Mrs.', 3, '', 1, '2014-02-24 11:00:24', 0, '0000-00-00 00:00:00'),
	(35, 4, 'Dr.', 'Dr.', 4, '', 1, '2014-02-24 11:00:30', 0, '0000-00-00 00:00:00'),
	(36, 4, 'Prof.', 'Prof.', 5, '', 1, '2014-02-24 11:00:38', 0, '0000-00-00 00:00:00'),
	(37, 26, 'PCB', 'PCB', 1, '', 1, '2015-02-27 00:00:00', 1, '0000-00-00 00:00:00'),
	(38, 26, 'WRF', 'WRF', 1, '', 1, '2015-02-27 00:00:00', 1, '0000-00-00 00:00:00'),
	(39, 26, 'BHG', 'BHG', 1, '', 1, '2015-02-27 00:00:00', 1, '0000-00-00 00:00:00'),
	(40, 26, 'DET', 'DET', 1, '', 1, '2015-02-27 00:00:00', 1, '0000-00-00 00:00:00'),
	(41, 25, '1', '1', 1, '', 1, '2015-03-12 00:00:00', 0, '0000-00-00 00:00:00'),
	(42, 25, '2', '2', 1, '', 1, '2015-03-12 00:00:00', 0, '0000-00-00 00:00:00'),
	(43, 25, '3', '3', 1, '', 1, '2015-03-12 00:00:00', 0, '0000-00-00 00:00:00'),
	(44, 25, '4', '4', 1, '', 1, '2015-03-12 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_additional_field_content` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_alerts`;
CREATE TABLE IF NOT EXISTS `sys_alerts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `table` varchar(250) NOT NULL,
  `header` varchar(250) NOT NULL,
  `target` text NOT NULL,
  `content` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `type` enum('pin','reminder','warning','question') NOT NULL DEFAULT 'pin',
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DELETE FROM `sys_alerts`;
/*!40000 ALTER TABLE `sys_alerts` DISABLE KEYS */;
INSERT INTO `sys_alerts` (`id`, `table`, `header`, `target`, `content`, `start_date`, `end_date`, `type`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'customers', 'Customer', '234', '<p>Blacklisted customer!</p>\r\n', '2016-04-12 00:00:00', '2016-04-19 00:00:00', 'pin', 1, '2016-04-12 14:16:16', 0, '0000-00-00 00:00:00'),
	(2, 'items', 'Item Alert', '17208,17291', '<p>Out of stock in HQ</p>\r\n', '2016-04-19 00:00:00', '2016-04-26 00:00:00', 'pin', 1, '2016-04-19 14:46:16', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_alerts` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_audit_trails`;
CREATE TABLE IF NOT EXISTS `sys_audit_trails` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `type` enum('general','login','logout','view','insert','delete','update','insert join') NOT NULL DEFAULT 'general',
  `module` varchar(250) NOT NULL,
  `json_before` text NOT NULL,
  `json_after` text NOT NULL,
  `extra` text NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Type` (`type`),
  KEY `Module` (`module`),
  FULLTEXT KEY `Json Before` (`json_before`),
  FULLTEXT KEY `Json After` (`json_after`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DELETE FROM `sys_audit_trails`;
/*!40000 ALTER TABLE `sys_audit_trails` DISABLE KEYS */;
INSERT INTO `sys_audit_trails` (`id`, `type`, `module`, `json_before`, `json_after`, `extra`, `created_by`, `created_date`) VALUES
	(1, 'logout', '', '[]', '[]', '{"session":"450efbf048fa6b8b7e22eb98c157f991"}', 1, '2017-05-10 21:05:54'),
	(2, 'login', '', '[]', '[]', '{"session":"450efbf048fa6b8b7e22eb98c157f991","user_agent":"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0","ip_address":"172.17.0.1"}', 1, '2017-05-10 22:44:21'),
	(3, 'logout', '', '[]', '[]', '{"session":"450efbf048fa6b8b7e22eb98c157f991"}', 1, '2017-05-10 22:44:48'),
	(4, 'login', '', '[]', '[]', '{"session":"450efbf048fa6b8b7e22eb98c157f991","user_agent":"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0","ip_address":"172.17.0.1"}', 1, '2017-05-10 22:49:17'),
	(5, 'logout', '', '[]', '[]', '{"session":"450efbf048fa6b8b7e22eb98c157f991"}', 1, '2017-05-10 22:54:07'),
	(6, 'login', '', '[]', '[]', '{"session":"450efbf048fa6b8b7e22eb98c157f991","user_agent":"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0","ip_address":"172.17.0.1"}', 1, '2017-05-10 22:54:26'),
	(7, 'logout', '', '[]', '[]', '{"session":"450efbf048fa6b8b7e22eb98c157f991"}', 1, '2017-05-10 22:54:46');
/*!40000 ALTER TABLE `sys_audit_trails` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_country`;
CREATE TABLE IF NOT EXISTS `sys_country` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `iso` varchar(250) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Name` (`name`),
  KEY `Iso` (`iso`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

DELETE FROM `sys_country`;
/*!40000 ALTER TABLE `sys_country` DISABLE KEYS */;
INSERT INTO `sys_country` (`id`, `name`, `iso`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'Malaysia', 'MY', 1, '2013-08-26 13:05:02', 0, '0000-00-00 00:00:00'),
	(2, 'Indonesia', 'ID', 1, '2013-08-26 13:05:17', 0, '0000-00-00 00:00:00'),
	(3, 'Singapore', 'SG', 1, '2013-08-26 13:05:39', 0, '0000-00-00 00:00:00'),
	(4, 'United States', 'US', 1, '2013-08-26 13:06:36', 0, '0000-00-00 00:00:00'),
	(5, 'United Kingdom', 'GB', 1, '2013-08-26 13:07:14', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_country` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_currency`;
CREATE TABLE IF NOT EXISTS `sys_currency` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) NOT NULL,
  `symbol` varchar(250) NOT NULL,
  `text` varchar(250) NOT NULL,
  `conversion` decimal(10,4) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Code` (`code`),
  KEY `Symbol` (`symbol`),
  KEY `Text` (`text`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

DELETE FROM `sys_currency`;
/*!40000 ALTER TABLE `sys_currency` DISABLE KEYS */;
INSERT INTO `sys_currency` (`id`, `code`, `symbol`, `text`, `conversion`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(17, 'USD', 'US DOLLAR', 'US DOLLAR', 3.6000, 0, '2014-11-27 12:08:43', 0, '0000-00-00 00:00:00'),
	(18, 'RM', 'RM', 'Malaysia Ringgit', 0.0000, 1, '2016-02-29 11:24:44', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_currency` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_custom_field`;
CREATE TABLE IF NOT EXISTS `sys_custom_field` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sys_module_id` int(10) NOT NULL,
  `module_uid` varchar(150) NOT NULL DEFAULT '',
  `cf_section_id` int(10) NOT NULL,
  `cf_position` varchar(150) NOT NULL DEFAULT '',
  `cf_type` varchar(150) NOT NULL DEFAULT '',
  `cf_status` tinyint(1) NOT NULL DEFAULT '1',
  `cf_label` varchar(250) NOT NULL DEFAULT '',
  `cf_code` varchar(250) NOT NULL DEFAULT '',
  `cf_tooltip` varchar(250) NOT NULL DEFAULT '',
  `cf_order` int(10) NOT NULL DEFAULT '0',
  `cf_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cf title` (`cf_label`),
  KEY `cf type` (`cf_type`),
  KEY `cf position` (`cf_position`),
  KEY `cf tooltip` (`cf_tooltip`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DELETE FROM `sys_custom_field`;
/*!40000 ALTER TABLE `sys_custom_field` DISABLE KEYS */;
INSERT INTO `sys_custom_field` (`id`, `sys_module_id`, `module_uid`, `cf_section_id`, `cf_position`, `cf_type`, `cf_status`, `cf_label`, `cf_code`, `cf_tooltip`, `cf_order`, `cf_mandatory`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 60, 'contacts', 6, 'right', 'dropdown', 1, 'Relationship Level', '1_relationship_level', '', 1, 0, 1, '2013-11-28 17:03:48', 1, '2014-01-06 10:30:44'),
	(2, 131, 'project_management.projects', 9, 'right', 'dropdown', 1, 'Sample Submitted?', '2_sample_submitted', '', 1, 0, 1, '2013-11-28 17:18:20', 1, '2013-12-10 16:45:40'),
	(3, 51, 'customer', 1, 'right', 'dropdown', 1, 'Relationship Level', '3_relationship_level', '', 1, 0, 1, '2013-11-28 17:03:48', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_custom_field` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_custom_field_content`;
CREATE TABLE IF NOT EXISTS `sys_custom_field_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cf_id` int(10) NOT NULL,
  `cf_content_label` varchar(250) NOT NULL DEFAULT '',
  `cf_content_value` varchar(250) NOT NULL DEFAULT '',
  `cf_content_order` int(10) NOT NULL DEFAULT '0',
  `cf_remark` varchar(150) NOT NULL DEFAULT '',
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

DELETE FROM `sys_custom_field_content`;
/*!40000 ALTER TABLE `sys_custom_field_content` DISABLE KEYS */;
INSERT INTO `sys_custom_field_content` (`id`, `cf_id`, `cf_content_label`, `cf_content_value`, `cf_content_order`, `cf_remark`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 1, 'Supportive', 'Supportive', 0, '', 1, '2013-11-28 17:04:18', 0, '0000-00-00 00:00:00'),
	(2, 1, 'Moderate', 'Moderate', 0, '', 1, '2013-11-28 17:04:32', 0, '0000-00-00 00:00:00'),
	(3, 1, 'Not Supportive', 'NotSupportive', 0, '', 1, '2013-11-28 17:04:47', 0, '0000-00-00 00:00:00'),
	(4, 2, 'Yes', 'yes', 0, '', 1, '2013-11-28 17:18:35', 0, '0000-00-00 00:00:00'),
	(5, 2, 'No', 'no', 0, '', 1, '2013-11-28 17:18:42', 0, '0000-00-00 00:00:00'),
	(6, 3, 'Supportive', 'Supportive', 0, '', 1, '2013-11-28 17:04:18', 0, '0000-00-00 00:00:00'),
	(7, 3, 'Moderate', 'Moderate', 0, '', 1, '2013-11-28 17:04:32', 0, '0000-00-00 00:00:00'),
	(8, 3, 'Not Supportive', 'NotSupportive', 0, '', 1, '2013-11-28 17:04:47', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_custom_field_content` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_custom_field_data`;
CREATE TABLE IF NOT EXISTS `sys_custom_field_data` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cf_id` int(10) NOT NULL,
  `module_data_id` int(10) NOT NULL,
  `cf_data` text,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  `Test` text,
  `ABC` text,
  `TTT` text,
  `BCS` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

DELETE FROM `sys_custom_field_data`;
/*!40000 ALTER TABLE `sys_custom_field_data` DISABLE KEYS */;
INSERT INTO `sys_custom_field_data` (`id`, `cf_id`, `module_data_id`, `cf_data`, `created_by`, `created_date`, `modified_by`, `modified_date`, `Test`, `ABC`, `TTT`, `BCS`) VALUES
	(1, 2, 1, '', 1, '2014-01-22 17:27:29', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(2, 3, 1, '', 1, '2014-02-14 15:57:56', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(3, 3, 501, '', 1, '2014-02-27 16:51:40', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(4, 3, 507, '', 1, '2014-03-03 17:53:19', 1, '2014-03-03 17:53:32', NULL, NULL, NULL, NULL),
	(5, 3, 3, '', 1, '2014-03-06 17:42:51', 1, '2014-03-06 17:43:02', NULL, NULL, NULL, NULL),
	(6, 2, 2, '', 1, '2014-03-11 17:03:22', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(7, 3, 16, '', 1, '2014-04-15 17:54:10', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(8, 3, 5334, '', 1, '2014-04-16 10:35:43', 1, '2014-04-16 10:39:38', NULL, NULL, NULL, NULL),
	(9, 3, 17, '', 1, '2014-04-16 11:07:40', 1, '2014-07-16 16:02:42', NULL, NULL, NULL, NULL),
	(10, 3, 5422, '', 8, '2014-07-25 16:21:02', 1, '2014-10-29 11:26:06', NULL, NULL, NULL, NULL),
	(11, 2, 1, '', 1, '2014-10-16 14:48:16', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(12, 2, 2, '', 1, '2014-10-16 14:48:47', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(13, 3, 9136, '', 1, '2015-05-12 16:11:02', 1, '2015-05-12 18:54:10', NULL, NULL, NULL, NULL),
	(14, 3, 5290, '', 1, '2016-04-04 14:49:47', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
	(15, 3, 234, '', 1, '2016-04-12 14:19:00', 1, '2016-04-12 14:20:29', NULL, NULL, NULL, NULL),
	(16, 1, 1, '', 8, '2016-07-01 13:45:32', 8, '2016-07-01 13:48:56', NULL, NULL, NULL, NULL),
	(17, 1, 2, '', 8, '2016-07-01 13:45:47', 0, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `sys_custom_field_data` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_custom_field_section`;
CREATE TABLE IF NOT EXISTS `sys_custom_field_section` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_id` int(10) NOT NULL,
  `module_uid` varchar(150) NOT NULL DEFAULT '',
  `section_name` varchar(250) NOT NULL,
  `section_num` int(10) NOT NULL,
  `section_column` int(10) NOT NULL DEFAULT '0',
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Form Name` (`section_name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

DELETE FROM `sys_custom_field_section`;
/*!40000 ALTER TABLE `sys_custom_field_section` DISABLE KEYS */;
INSERT INTO `sys_custom_field_section` (`id`, `module_id`, `module_uid`, `section_name`, `section_num`, `section_column`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 51, 'customer', 'Customer Information', 1, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(2, 51, 'customer', 'Billing Details', 2, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(3, 51, 'customer', 'Delivery Details', 3, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(4, 51, 'customer', 'Credit Details', 4, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(5, 51, 'customer', 'Additional Details', 5, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(6, 60, 'contacts', 'Contact Information', 1, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(7, 60, 'contacts', 'Address Information', 2, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(8, 60, 'contacts', 'Additional Information', 3, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(9, 131, 'project_management.projects', 'Project Information', 1, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00'),
	(10, 131, 'project_management.projects', 'Additional Information', 2, 0, 1, '2013-11-11 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_custom_field_section` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_event_tracker`;
CREATE TABLE IF NOT EXISTS `sys_event_tracker` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `session` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `url` text NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Session` (`session`),
  KEY `Title` (`title`),
  FULLTEXT KEY `url` (`url`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

DELETE FROM `sys_event_tracker`;
/*!40000 ALTER TABLE `sys_event_tracker` DISABLE KEYS */;
INSERT INTO `sys_event_tracker` (`id`, `session`, `title`, `url`, `created_by`, `created_date`) VALUES
	(13, '8gri5mi3fpbite2c99crekdqd6', 'Dashboard', 'http%3A%2F%2Flocalhost%2Fdashboard', 1, '2017-05-10 19:52:19');
/*!40000 ALTER TABLE `sys_event_tracker` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_grids_settings`;
CREATE TABLE IF NOT EXISTS `sys_grids_settings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `grid_id` varchar(200) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `settings` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `sys_grids_settings`;
/*!40000 ALTER TABLE `sys_grids_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_grids_settings` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_image_upload`;
CREATE TABLE IF NOT EXISTS `sys_image_upload` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent` int(10) NOT NULL,
  `caption` varchar(250) NOT NULL,
  `item_order` int(11) NOT NULL,
  `path` text NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `sys_image_upload`;
/*!40000 ALTER TABLE `sys_image_upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_image_upload` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_login_attempt`;
CREATE TABLE IF NOT EXISTS `sys_login_attempt` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(250) NOT NULL,
  `attempt` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DELETE FROM `sys_login_attempt`;
/*!40000 ALTER TABLE `sys_login_attempt` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_login_attempt` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_mailer_settings`;
CREATE TABLE IF NOT EXISTS `sys_mailer_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `host` varchar(250) NOT NULL,
  `port` varchar(250) NOT NULL,
  `auth` tinyint(1) NOT NULL DEFAULT '1',
  `user` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `default_sender` varchar(250) NOT NULL,
  `default_sender_mail` varchar(250) NOT NULL,
  `default_reply` varchar(250) NOT NULL,
  `default_reply_mail` varchar(250) NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DELETE FROM `sys_mailer_settings`;
/*!40000 ALTER TABLE `sys_mailer_settings` DISABLE KEYS */;
INSERT INTO `sys_mailer_settings` (`id`, `host`, `port`, `auth`, `user`, `pass`, `default_sender`, `default_sender_mail`, `default_reply`, `default_reply_mail`, `modified_by`, `modified_date`) VALUES
	(1, 'touchsales.net', '465', 1, 'noreply@touchsales.net', 'eZzx5CQ;}aim', 'TouchSales Local', 'noreply@touchsales.net', '', '', 1, '2016-04-11 09:23:38');
/*!40000 ALTER TABLE `sys_mailer_settings` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_mailer_templates`;
CREATE TABLE IF NOT EXISTS `sys_mailer_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) NOT NULL,
  `name` varchar(250) NOT NULL,
  `sender` varchar(250) NOT NULL DEFAULT '[DEFAULT]',
  `sender_mail` varchar(250) NOT NULL DEFAULT '[DEFAULT]',
  `reply` varchar(250) NOT NULL DEFAULT '[DEFAULT]',
  `reply_mail` varchar(250) NOT NULL DEFAULT '[DEFAULT]',
  `subject` varchar(250) NOT NULL,
  `bcc` text NOT NULL,
  `note` text NOT NULL,
  `content` text NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

DELETE FROM `sys_mailer_templates`;
/*!40000 ALTER TABLE `sys_mailer_templates` DISABLE KEYS */;
INSERT INTO `sys_mailer_templates` (`id`, `code`, `name`, `sender`, `sender_mail`, `reply`, `reply_mail`, `subject`, `bcc`, `note`, `content`, `modified_by`, `modified_date`) VALUES
	(1, 'oz.activation', 'Oz Account Activation', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', 'Oz Account Activation', '', '', '<style type="text/css">.mailer-wrapper{position: relative; font-family: sans-serif, arial;}\n.mailer-header{\n	background: #004983;\n	text-align: center;\n	height: 25x;\n	font-size: 16px;\n	font-weight: bold;\n	color: #ffffff;\n	padding: 4px 6px 4px 4px;\n	width: 100%;\n}\n.mailer-content{\n	min-height: 300px;\n	height: auto;\n	padding: 10px 0;\n	font-size: 12px;\n}\n.mailer-footer{\n	position: absolute;\n	bottom: 0;\n	font-size: 10px;\n	font-weight: normal;\n}\n.mailer-center{\n	min-width: 600px;\n	width: auto;\n	max-width: 800px;\n	text-align: left;\n	margin: auto;\n	position: relative;\n}\n.mailer-right{\n	float: right; position: absolute; top: 0; right: 0; text-align: right;"\n}\n.mailer-link{\n	color: #ffffff;\n}\n.mailer-link:hover{\n	color: #41C8F5;\n}\n</style>\n<div class="mailer-wrapper">\n<div class="mailer-header">\n<div class="mailer-center"><span class="mailer-right">Account Activation </span> <span> OZ / PolarisNet Sales </span></div>\n</div>\n\n<div class="mailer-content">\n<div class="mailer-center mailer-body"><span style="font-weight: bold; font-size: 14px;">Hello [user_name],</span><br />\n<br />\n<span>Your account is successfully created. Here is your registered username [username] as your login details.<br />\nBefore you can start exploring our site, please activate your account first by simply click on the following link:<br />\n<br />\n<span style="border: 1px solid #bfbfbf; padding: 5px;"><a href="#" target="_blank">Activate My Account</a></span><br />\n<br />\nIf the above link does not appear to be clickable, please copy this link [activation_link] and paste it on your web browser. </span></div>\n\n<div style="clear: both; height: 20px;">&nbsp;</div>\n</div>\n\n<div class="mailer-header mailer-footer">\n<div class="mailer-center"><span class="mailer-right">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\nThis email message is auto generated, do not reply this email.<br />\nShould you have any enquiry, please contact us at support@oz.polarisnet.my </span></div>\n</div>\n</div>\n', 1, '2013-10-31 11:49:33'),
	(2, 'touchsales.initialise', 'TouchSales Initialisation Settings', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', 'TouchSales Initialisation Setting', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\r\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Touchsales Initialisation </span><span>OZ / PolarisNet Sales </span></div>\r\n</div>\r\n\r\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Hello [target_name],</span><br />\r\n<br />\r\n<span>This email is to notify you that you have a device that has been registered with TouchSales.<br />\r\nTo begin, please download the TouchSales mobile app by going to the Apple App Store and search for &quot;TouchSales&quot; or click on this link on your iPad:</span></div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span>- [ios_link]</span> (iOS)</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span>After you have installed the app, please input the data below to initialise your device</span>:</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\r\n<table>\r\n	<tbody>\r\n		<tr>\r\n			<td>Server Address</td>\r\n			<td>:</td>\r\n			<td>[server_address]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Initialisation Key</td>\r\n			<td>:</td>\r\n			<td>[init_key]</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">Please do note that the initialisation key can be used only once and this procees might take up to a couple of minutes.</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">If you face any problems during the process, please contact us at support@polarisnet.com.my.</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">Thank you,</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">Polaris TouchSales</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><br />\r\n&nbsp;</div>\r\n\r\n<div style="clear: both; height: 20px;">&nbsp;</div>\r\n</div>\r\n\r\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\r\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\r\nThis email message is auto generated, do not reply this email.<br />\r\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\r\n</div>\r\n</div>\r\n', 1, '2014-05-06 12:45:22'),
	(3, 'oz.reset.bo', 'Oz Reset Password', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', 'Oz Reset Password', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Reset Password </span> <span> OZ / PolarisNet Sales </span></div>\n</div>\n\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Hello [target_username] ([target_name]),</span><br>\n<br>\n<span>You have requested to reset your password on [reset_date]. Please click the below link in order to reset your password:<br>\n<br>\n<span style="border: 1px solid #bfbfbf; padding: 5px;"><a href="[reset_link]" target="_blank">Reset My Password</a></span><br>\n<br>\nIf the above link does not appear to be clickable, please copy this link [reset_link] and paste it on your web browser. </span></div>\n\n<div style="clear: both; height: 20px;">&nbsp;</div>\n</div>\n\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" target="_blank" style="color: #ffffff;">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br>\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br>\nThis email message is auto generated, do not reply this email.<br>\nShould you have any enquiry, please contact us at support@oz.polarisnet.my </span></div>\n</div>\n</div>\n', 1, '2013-10-31 11:49:33'),
	(4, 'touchsales.initialise.success', 'TouchSales Initialisation Success', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', 'TouchSales Initialisation Success', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\r\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Touchsales Initialisation </span><span>OZ / PolarisNet Sales </span></div>\r\n</div>\r\n\r\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Congratulation [target_name],</span></div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">You have successfully initialised TouchSales on your device. Below are the details that you will require to access TouchSales<span>:</span></div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\r\n<table>\r\n	<tbody>\r\n		<tr>\r\n			<td>Username</td>\r\n			<td>:</td>\r\n			<td>[local_username]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Password</td>\r\n			<td>:</td>\r\n			<td>[local_password]</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><strong>Friendly reminder:</strong> Please remember to synchronise your device data before using it =)\r\n\r\n<p>Thank you,<br />\r\nPolaris TouchSales</p>\r\n</div>\r\n</div>\r\n\r\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\r\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\r\nThis email message is auto generated, do not reply this email.<br />\r\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\r\n</div>\r\n</div>\r\n', 1, '2014-05-06 12:48:46'),
	(5, 'touchsales.so.sync.success', 'TouchSales Sales Order Sync Success', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', 'TouchSales Sales Order Sync Success', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Touchsales Initialisation </span><span>OZ / PolarisNet Sales </span></div>\n</div>\n\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Dear [target_name],</span></div>\n\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\n\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">Your sales order [order_no] has been successfully synchronize with our server.</div>\n\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\n<p>Thank you,<br />\nPolaris TouchSales</p>\n</div>\n\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\nThis email message is auto generated, do not reply this email.<br />\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\n</div>\n</div>\n', 1, '2014-07-08 23:59:59'),
	(6, 'touchsales.so.approve.success', 'TouchSales Sales Order Approve Success', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', 'TouchSales Sales Order Approve Success', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\r\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Touchsales Initialisation </span><span>OZ / PolarisNet Sales </span></div>\r\n</div>\r\n\r\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Dear [target_name],</span></div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">Your sales order [order_no] has been approved by our internal staff.</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\r\n<p>Thank you,<br />\r\nPolaris TouchSales</p>\r\n</div>\r\n\r\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\r\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\r\nThis email message is auto generated, do not reply this email.<br />\r\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\r\n</div>\r\n</div>', 1, '2014-07-08 23:59:59'),
	(7, 'oz.hr.leave.request', 'OZ HR Leave Request', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[leave_type] request from [leave_start_date] to [leave_end_date]', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\r\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Leave Request</span><span> / OZHR</span></div>\r\n</div>\r\n\r\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Hello [employee_name],</span></div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">You have requested [leave_type] which start from [leave_start_date] to [leave_end_date] with reason as following (if any):<br />\r\n[leave_reason]<br />\r\n&nbsp;\r\n<hr />This request has been send to any eligible party for approval process. The process will takes up to 5 working days.<br />\r\nIf this is an emergency request, you can to try to contact with any eligible party directly to speed up the approval process.</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\r\n<p>Thank you,<br />\r\nOZHR</p>\r\n</div>\r\n\r\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\r\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\r\nThis email message is auto generated, do not reply this email.<br />\r\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\r\n</div>\r\n</div>\r\n</div>\r\n', 1, '2015-08-04 16:45:22'),
	(8, 'oz.hr.leave.approval', 'OZ HR Leave Approval', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[employee_name]\'s [leave_type] request from [leave_start_date] to [leave_end_date]', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\r\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Leave Approval</span><span> / OZHR</span></div>\r\n</div>\r\n\r\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Hello [supervisor_name],</span></div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">[employee_name] has requested [leave_type] which start from [leave_start_date] to [leave_end_date] with reason as following (if any):<br />\r\n[leave_reason]<br />\r\n&nbsp;\r\n<hr />Please login to OZHR portal for approval process.</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\r\n<p>Thank you,<br />\r\nOZHR</p>\r\n</div>\r\n\r\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\r\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\r\nThis email message is auto generated, do not reply this email.<br />\r\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\r\n</div>\r\n</div>\r\n</div>\r\n', 1, '2015-08-04 18:01:41'),
	(9, 'oz.hr.leave.approval.result', 'OZ HR Leave Approval Result', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '([approval_status]) [employee_name]\'s [leave_type] request from [leave_start_date] to [leave_end_date]', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Leave Approval</span><span> / OZHR</span></div>\n</div>\n\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Hello [dyn_target_name],</span></div>\n\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\n\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">This email is to notify you that [dyn_employee_name] [leave_type] which start from [leave_start_date] to [leave_end_date] has been [approval_status] by [dyn_reviewer].</div>\n\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\n<p>Thank you,<br />\nOZHR</p>\n</div>\n\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\nThis email message is auto generated, do not reply this email.<br />\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\n</div>\n</div>', 1, '2014-07-08 23:59:59'),
	(10, 'touchsales.cash_sales.approve.success', 'TouchSales Cash Sales Approve Success', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', '[DEFAULT]', 'TouchSales Cash Sales Approve Success', '', '', '<div class="mailer-wrapper" style="position: relative;font-family: sans-serif, arial;">\r\n<div class="mailer-header" style="background: #004983;text-align: center;height: 25x;font-size: 16px;font-weight: bold;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Touchsales Initialisation </span><span>OZ / PolarisNet Sales </span></div>\r\n</div>\r\n\r\n<div class="mailer-content" style="min-height: 300px;height: auto;padding: 10px 0;font-size: 12px;">\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span style="font-weight: bold; font-size: 14px;">Dear [target_name],</span></div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">&nbsp;</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">Your sales order [order_no] has been approved by our internal staff.</div>\r\n\r\n<div class="mailer-center mailer-body" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;">\r\n<p>Thank you,<br />\r\nPolaris TouchSales</p>\r\n</div>\r\n\r\n<div class="mailer-header mailer-footer" style="background: #004983;text-align: center;height: 25x;font-size: 10px;font-weight: normal;color: #ffffff;padding: 4px 6px 4px 4px;width: 100%;position: absolute;bottom: 0;">\r\n<div class="mailer-center" style="min-width: 600px;width: auto;max-width: 800px;text-align: left;margin: auto;position: relative;"><span class="mailer-right" style="float: right;position: absolute;top: 0;right: 0;text-align: right;&quot;: ;">Copyright &copy; <a class="mailer-link" href="http://polarisnet.com.my/" style="color: #ffffff;" target="_blank">Polaris Net Sdn Bhd</a> (719142-W). All rights reserved.<br />\r\nPowered by Oz Framework </span> <span> This email is sent to [target_mail]<br />\r\nThis email message is auto generated, do not reply this email.<br />\r\nShould you have any enquiry, please contact us at support@polarisnet.com.my</span></div>\r\n</div>\r\n</div>', 1, '2015-08-04 16:44:42');
/*!40000 ALTER TABLE `sys_mailer_templates` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_module`;
CREATE TABLE IF NOT EXISTS `sys_module` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(150) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `module_display` varchar(250) NOT NULL,
  `module_dir` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `header` tinyint(1) NOT NULL DEFAULT '0',
  `footer` tinyint(1) NOT NULL DEFAULT '0',
  `sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `parent_uid` varchar(150) NOT NULL,
  `real_parent` varchar(250) NOT NULL,
  `module_level` int(5) NOT NULL DEFAULT '0',
  `record_permission` int(5) NOT NULL DEFAULT '0',
  `item_order` int(10) NOT NULL,
  `display_link` tinyint(1) NOT NULL DEFAULT '0',
  `tile_pics` text NOT NULL,
  `display_tile` tinyint(1) NOT NULL DEFAULT '1',
  `tooltip_text` text NOT NULL,
  `display_custom_field` tinyint(1) NOT NULL DEFAULT '0',
  `display_system_field` tinyint(1) NOT NULL DEFAULT '0',
  `secure_mode` enum('none','fo','bo','pubbo') NOT NULL DEFAULT 'none',
  `protected` tinyint(1) NOT NULL,
  `installed_by` int(10) NOT NULL,
  `installed_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`uid`,`parent_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=609 DEFAULT CHARSET=utf8;

DELETE FROM `sys_module`;
/*!40000 ALTER TABLE `sys_module` DISABLE KEYS */;
INSERT INTO `sys_module` (`id`, `uid`, `module_name`, `module_display`, `module_dir`, `status`, `header`, `footer`, `sidebar`, `parent_uid`, `real_parent`, `module_level`, `record_permission`, `item_order`, `display_link`, `tile_pics`, `display_tile`, `tooltip_text`, `display_custom_field`, `display_system_field`, `secure_mode`, `protected`, `installed_by`, `installed_date`) VALUES
	(1, 'oz.dashboard', 'Dashboard', 'Dashboard', '/oz.dashboard', 1, 0, 0, 0, '', '', 0, 0, 1, 1, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(2, 'oz.system', 'System', 'System', '/oz.system', 1, 1, 0, 0, '', '', 1, 0, 8, 0, '/settings.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(3, 'oz.login.bo', 'Login BO', 'Login', '/oz.login/bo', 1, 0, 0, 0, '', '', 0, 0, 0, 0, '', 0, '', 0, 0, 'none', 1, 1, '2013-06-24 00:00:00'),
	(4, 'oz.login.fo', 'Login FO', 'Login', '/oz.login/fo', 0, 0, 0, 0, '', '', 0, 0, 0, 0, '', 0, '', 0, 0, 'none', 1, 1, '2013-06-24 00:00:00'),
	(5, 'oz.system.settings', 'Settings', 'Settings', '/oz.system', 1, 1, 0, 1, 'oz.system', 'oz.system', 2, 0, 3, 1, '/settings2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(6, 'oz.system.user_management', 'User Management', 'User Management', '/oz.system', 1, 1, 0, 1, 'oz.system', 'oz.system', 2, 0, 1, 1, '/collaborator.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(7, 'oz.system.user_management.users', 'Users', 'Users', '/oz.system/user_management', 1, 1, 0, 1, 'oz.system.user_management', 'oz.system.user_management', 0, 0, 1, 1, '/collaborator.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(8, 'oz.system.user_management.usergroups', 'User Groups', 'User Groups', '/oz.system/user_management', 0, 1, 0, 1, 'oz.system.user_management', 'oz.system.user_management', 0, 0, 2, 1, '/conference.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(9, 'oz.system.settings.mailer', 'Mailer', 'Mailer', '/oz.system/settings/mailer', 1, 0, 0, 1, 'oz.system.settings', 'oz.system.settings', 0, 0, 2, 1, '/message_outline.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(10, 'oz.system.settings.modules', 'Modules', 'Modules', '/oz.system/settings', 0, 1, 0, 1, 'oz.system.settings', 'oz.system.settings', 0, 0, 4, 1, '/puzzle.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(11, 'oz.system.settings.site', 'Site', 'Site', '/oz.system/settings/site', 1, 1, 0, 1, 'oz.system.settings', 'oz.system.settings', 0, 0, 1, 1, '/domain.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(12, 'oz.system.logs', 'Logs', 'Logs', '/oz.system/logs', 1, 1, 0, 1, 'oz.system', 'oz.system', 2, 0, 2, 1, '/logs.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(13, 'oz.system.logs.audit', 'Audit Trails', 'Audit Trails', '/oz.system/logs', 1, 1, 0, 1, 'oz.system.logs', 'oz.system.logs', 0, 0, 1, 1, '/list.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(14, 'oz.system.logs.error', 'Error Logs', 'Error Logs', '/oz.system/logs', 1, 1, 0, 1, 'oz.system.logs', 'oz.system.logs', 0, 0, 2, 1, '/error.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(15, 'oz.system.settings.country', 'Country', 'Country', '/oz.system/settings/country', 1, 0, 0, 1, 'oz.system.settings.site', 'oz.system.settings.site', 0, 0, 3, 1, '/globe.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(16, 'oz.system.user_management.users.list', 'User List', 'User List', '/oz.system/user_management', 1, 0, 0, 1, 'oz.system.user_management.users', 'oz.system.user_management.users', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(17, 'oz.system.user_management.users.new', 'New User', 'New User', '/oz.system/user_management', 1, 0, 0, 1, 'oz.system.user_management.users', 'oz.system.user_management.users', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(18, 'oz.system.user_management.users.view', 'View User', 'View User', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.users', 'oz.system.user_management.users', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(19, 'oz.system.user_management.users.edit', 'Edit User', 'Edit User', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.users', 'oz.system.user_management.users', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(20, 'oz.system.user_management.users.delete', 'Delete User', 'Delete User', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.users', 'oz.system.user_management.users', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(66, 'oz.system.settings.country.list', 'Country List', 'Country List', '/oz.system/settings/country', 1, 0, 0, 1, 'oz.system.settings.country', 'oz.system.settings.country', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(21, 'oz.system.logs.audit.view', 'View Audit Trails', 'View Audit Trails', '/oz.system/logs', 1, 0, 0, 0, 'oz.system.logs.audit', 'oz.system.logs.audit', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(22, 'oz.system.user_management.usergroups.list', 'User Groups List', 'User Groups List', '/oz.system/user_management', 1, 0, 0, 1, 'oz.system.user_management.usergroups', 'oz.system.user_management.usergroups', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(23, 'oz.system.user_management.usergroups.new', 'New User Group', 'New User Group', '/oz.system/user_management', 1, 0, 0, 1, 'oz.system.user_management.usergroups', 'oz.system.user_management.usergroups', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(24, 'oz.system.user_management.usergroups.edit', 'Edit User Groups', 'Edit User Groups', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.usergroups', 'oz.system.user_management.usergroups', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(25, 'oz.system.user_management.usergroups.delete', 'Delete User Groups', 'Delete User Groups', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.usergroups', 'oz.system.user_management.usergroups', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(26, 'oz.system.user_management.usergroups.view', 'View User Group', 'View User Group', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.usergroups', 'oz.system.user_management.usergroups', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(46, 'oz.system.settings.currency', 'Currency', 'Currency', '/oz.system/settings/currency', 1, 0, 0, 1, 'oz.system.settings.site', 'oz.system.settings.site', 0, 0, 2, 1, '/bank.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(47, 'oz.system.settings.currency.list', 'Currency List', 'Currency List', '/oz.system/settings/currency', 1, 0, 0, 1, 'oz.system.settings.currency', 'oz.system.settings.currency', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(48, 'oz.system.settings.currency.view', 'View Currency', 'View Currency', '/oz.system/settings/currency', 1, 0, 0, 0, 'oz.system.settings.currency', 'oz.system.settings.currency', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(49, 'oz.system.settings.currency.edit', 'Edit Currency', 'Edit Currency', '/oz.system/settings/currency', 1, 0, 0, 0, 'oz.system.settings.currency', 'oz.system.settings.currency', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(50, 'oz.system.settings.currency.new', 'New  Currency', 'New Currency', '/oz.system/settings/currency', 1, 0, 0, 1, 'oz.system.settings.currency', 'oz.system.settings.currency', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(54, 'oz.system.settings.currency.delete', 'Delete Currency', 'Delete Currency', '/oz.system/settings/currency', 1, 0, 0, 0, 'oz.system.settings.currency', 'oz.system.settings.currency', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(67, 'oz.system.settings.country.view', 'View Country', 'View Country', '/oz.system/settings/country', 1, 0, 0, 0, 'oz.system.settings.country', 'oz.system.settings.country', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(68, 'oz.system.settings.country.edit', 'Edit Country', 'Edit Country', '/oz.system/settings/country', 1, 0, 0, 0, 'oz.system.settings.country', 'oz.system.settings.country', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(69, 'oz.system.settings.country.new', 'New  Country', 'New Country', '/oz.system/settings/country', 1, 0, 0, 1, 'oz.system.settings.country', 'oz.system.settings.country', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(70, 'oz.system.settings.country.delete', 'Delete Country', 'Delete Country', '/oz.system/settings/country', 1, 0, 0, 0, 'oz.system.settings.country', 'oz.system.settings.country', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(173, 'oz.system.settings.mailer.template', 'Templates', 'Templates', '/oz.system/settings/mailer', 1, 0, 0, 1, 'oz.system.settings.mailer', 'oz.system.settings.mailer', 0, 0, 1, 1, '/bookmark.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(174, 'oz.system.settings.mailer.settings', 'Mailer Settings', 'Mailer Settings', '/oz.system/settings/mailer', 1, 0, 0, 1, 'oz.system.settings.mailer', 'oz.system.settings.mailer', 0, 0, 2, 1, '/settings.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(175, 'oz.system.user_management.users.resetpassword', 'Reset Password', 'Reset Password', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.users', 'oz.system.user_management.users', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(176, 'oz.system.user_management.users.changepassword', 'Change Password', 'Change Password', '/oz.system/user_management', 1, 0, 0, 0, 'oz.system.user_management.users', 'oz.system.user_management.users', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(177, 'oz.lostpass.bo', 'Account Recovery BO', 'Account Recovery', '/oz.lostpass/bo', 1, 0, 0, 0, '', '', 0, 0, 0, 0, '', 0, '', 0, 0, 'none', 1, 1, '2013-06-24 00:00:00'),
	(178, 'oz.lostpass.fo', 'Account Recovery FO', 'Account Recovery', '/oz.lostpass/fo', 0, 0, 0, 0, '', '', 0, 0, 0, 0, '', 0, '', 0, 0, 'none', 1, 1, '2013-06-24 00:00:00'),
	(179, 'oz.reset.bo', 'Reset Password BO', 'Reset Password', '/oz.reset/bo', 1, 0, 0, 0, '', '', 0, 0, 0, 0, '', 0, '', 0, 0, 'none', 1, 1, '2013-06-24 00:00:00'),
	(180, 'oz.reset.fo', 'Reset Password FO', 'Reset Password', '/oz.reset/fo', 0, 0, 0, 0, '', '', 0, 0, 0, 0, '', 0, '', 0, 0, 'none', 1, 1, '2013-06-24 00:00:00'),
	(191, 'oz.system.settings.custom_field', 'Custom Fields', 'Custom Fields', '/oz.system/settings/custom_field', 0, 1, 0, 0, 'oz.system.settings', 'oz.system.settings', 0, 0, 7, 1, '/redeem.png', 1, '', 0, 0, 'bo', 1, 1, '2013-10-02 00:00:00'),
	(192, 'oz.system.settings.custom_field.list', 'Custom Fields List', 'Custom Fields List', '/oz.system/settings/custom_field', 1, 0, 0, 1, 'oz.system.settings.custom_field', 'oz.system.settings.custom_field', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(193, 'oz.system.settings.custom_field.view', 'View Custom Field', 'View Custom  Field', '/oz.system/settings/custom_field', 1, 0, 0, 0, 'oz.system.settings.custom_field', 'oz.system.settings.custom_field', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(194, 'oz.system.settings.custom_field.new', 'New Custom Field', 'New Custom Field', '/oz.system/settings/custom_field', 1, 0, 0, 1, 'oz.system.settings.custom_field', 'oz.system.settings.custom_field', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(195, 'oz.system.settings.custom_field.edit', 'Edit Custom Field', 'Edit Custom Field', '/oz.system/settings/custom_field', 1, 0, 0, 0, 'oz.system.settings.custom_field', 'oz.system.settings.custom_field', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(196, 'oz.system.settings.custom_field.delete', 'Delete Custom Field', 'Delete Custom Field', '/oz.system/settings/custom_field', 1, 0, 0, 0, 'oz.system.settings.custom_field', 'oz.system.settings.custom_field', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(202, 'oz.system.settings.system_field', 'System Fields', 'System Fields', '/oz.system/settings/system_field', 0, 1, 0, 0, 'oz.system.settings', 'oz.system.settings', 0, 0, 6, 1, '/redeem.png', 1, '', 0, 0, 'bo', 1, 1, '2013-10-02 00:00:00'),
	(203, 'oz.system.settings.system_field.list', 'System Fields List', 'System Fields List', '/oz.system/settings/system_field', 1, 0, 0, 1, 'oz.system.settings.system_field', 'oz.system.settings.system_field', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(204, 'oz.system.settings.system_field.view', 'View System Field', 'View System Field', '/oz.system/settings/system_field', 1, 0, 0, 0, 'oz.system.settings.system_field', 'oz.system.settings.system_field', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(206, 'oz.system.settings.system_field.edit', 'Edit System Field', 'Edit System Field', '/oz.system/settings/system_field', 1, 0, 0, 0, 'oz.system.settings.system_field', 'oz.system.settings.system_field', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(207, 'oz.system.settings.system_field.delete', 'Delete System Field', 'Delete System Field', '/oz.system/settings/system_field', 1, 0, 0, 0, 'oz.system.settings.system_field', 'oz.system.settings.system_field', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(251, 'oz.message', 'Message', 'Message', '/oz.message', 1, 0, 0, 0, '', '', 0, 0, 6, 0, '/message.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(252, 'oz.system.settings.alerts.new', 'New Alert', 'New Alert', '/oz.system/settings/alerts', 1, 0, 0, 1, 'oz.system.settings.alerts', 'oz.system.settings.alerts', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(253, 'oz.system.settings.alerts.edit', 'Edit Alert', 'Edit Alert', '/oz.system/settings/alerts', 1, 0, 0, 0, 'oz.system.settings.alerts', 'oz.system.settings.alerts', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(254, 'oz.system.settings.alerts.view', 'View Alert', 'View Alert', '/oz.system/settings/alerts', 1, 0, 0, 0, 'oz.system.settings.alerts', 'oz.system.settings.alerts', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(255, 'oz.system.settings.alerts.list', 'Alert List', 'Alert List', '/oz.system/settings/alerts', 1, 0, 0, 1, 'oz.system.settings.alerts', 'oz.system.settings.alerts', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(256, 'oz.system.settings.alerts', 'Alerts', 'Alerts', '/oz.system/settings/alerts', 0, 1, 0, 1, 'oz.system.settings', 'oz.system.settings', 0, 0, 8, 1, '/bell.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(257, 'oz.system.settings.site.banner', 'Banner', 'Banner', '/oz.system/settings/site/banner', 1, 0, 0, 0, 'oz.system.settings.site', 'oz.system.settings.site', 0, 0, 1, 1, '/banner.png', 1, '', 0, 0, 'bo', 1, 1, '2014-04-15 00:00:00'),
	(258, 'oz.system.settings.site.banner.list', 'Banner List', 'Banner List', '/oz.system/settings/site/banner', 1, 0, 0, 1, 'oz.system.settings.site.banner', 'oz.system.settings.site.banner', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2014-04-15 00:00:00'),
	(259, 'oz.system.settings.site.banner.view', 'View Banner', 'View Banner', '/oz.system/settings/site/banner', 1, 0, 0, 0, 'oz.system.settings.site.banner', 'oz.system.settings.site.banner', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2014-04-15 00:00:00'),
	(260, 'oz.system.settings.site.banner.new', 'New Banner', 'New Banner', '/oz.system/settings/site/banner', 1, 0, 0, 1, 'oz.system.settings.site.banner', 'oz.system.settings.site.banner', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2014-04-15 00:00:00'),
	(261, 'oz.system.settings.site.banner.edit', 'Edit Banner', 'Edit Banner', '/oz.system/settings/site/banner', 1, 0, 0, 0, 'oz.system.settings.site.banner', 'oz.system.settings.site.banner', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2014-04-15 00:00:00'),
	(262, 'oz.system.settings.site.banner.delete', 'Delete Banner', 'Delete Banner', '/oz.system/settings/site/banner', 1, 0, 0, 0, 'oz.system.settings.site.banner', 'oz.system.settings.site.banner', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2014-04-15 00:00:00'),
	(275, 'oz.system.settings.profile', 'Profile', 'Profile', '/oz.system/settings/profile', 1, 0, 0, 1, 'oz.system.settings', 'oz.system.settings', 0, 0, 1, 1, '/collaborator.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(276, 'oz.system.settings.profile.edit', 'Edit Profile', 'Edit Profile', '/oz.system/settings/profile', 1, 0, 0, 1, 'oz.system.settings.profile', 'oz.system.settings.profile', 0, 0, 1, 1, '/contact.png', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(277, 'oz.system.settings.profile.changepassword', 'Change Password', 'Change Password', '/oz.system/settings/profile', 1, 0, 0, 1, 'oz.system.settings.profile', 'oz.system.settings.profile', 0, 0, 2, 1, '', 1, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(278, 'oz.system.settings.site.settings', 'Settings', 'Settings', '/oz.system/settings/site/settings', 1, 0, 0, 0, 'oz.system.settings.site', 'oz.system.settings.site', 0, 0, 1, 1, '/admin_tools.png', 1, '', 0, 0, 'bo', 1, 1, '2014-04-15 00:00:00'),
	(288, 'oz.system.settings.search_management', 'Search Management', 'Search Management', '/oz.system/settings/search_management', 0, 1, 0, 1, 'oz.system.settings', 'oz.system.settings', 0, 0, 5, 1, '/search.png', 1, '', 0, 0, 'bo', 1, 1, '2014-07-31 00:00:00'),
	(289, 'oz.system.settings.search_management.list', 'Search Management List', 'Search Management List', '/oz.system/settings/search_management', 1, 0, 0, 1, 'oz.system.settings.search_management', 'oz.system.settings.search_management', 0, 0, 1, 1, '/list_2.png', 1, '', 0, 0, 'bo', 1, 1, '2014-07-31 00:00:00'),
	(290, 'oz.system.settings.search_management.new', 'New Search Management', 'New Search Management', '/oz.system/settings/search_management', 1, 0, 0, 1, 'oz.system.settings.search_management', 'oz.system.settings.search_management', 0, 0, 2, 1, '/plus.png', 1, '', 0, 0, 'bo', 1, 1, '2014-07-31 00:00:00'),
	(291, 'oz.system.settings.search_management.view', 'View Search Management', 'View Search Management', '/oz.system/settings/search_management', 1, 0, 0, 0, 'oz.system.settings.search_management', 'oz.system.settings.search_management', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2014-07-31 00:00:00'),
	(292, 'oz.system.settings.search_management.edit', 'Edit Search Management', 'Edit Search Management', '/oz.system/settings/search_management', 1, 0, 0, 0, 'oz.system.settings.search_management', 'oz.system.settings.search_management', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2013-06-24 00:00:00'),
	(293, 'oz.system.settings.search_management.delete', 'Delete Search Management', 'Delete Search Management', '/oz.system/settings/search_management', 1, 0, 0, 0, 'oz.system.settings.search_management', 'oz.system.settings.search_management', 0, 0, 1, 0, '', 0, '', 0, 0, 'bo', 1, 1, '2014-07-31 00:00:00'),
	(584, 'oz.system.settings.update', 'System Update', 'System Update', '/oz.system/settings/update', 1, 1, 0, 1, 'oz.system.settings', 'oz.system.settings', 0, 0, 10, 1, '/upgrade.png', 1, '', 0, 0, 'bo', 1, 1, '2016-08-02 00:00:00');
/*!40000 ALTER TABLE `sys_module` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_privileges`;
CREATE TABLE IF NOT EXISTS `sys_privileges` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_uid` varchar(250) NOT NULL,
  `group_6` tinyint(1) NOT NULL DEFAULT '1',
  `group_5` tinyint(1) NOT NULL DEFAULT '1',
  `group_2` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `Module ID` (`module_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=464 DEFAULT CHARSET=utf8;

DELETE FROM `sys_privileges`;
/*!40000 ALTER TABLE `sys_privileges` DISABLE KEYS */;
INSERT INTO `sys_privileges` (`id`, `module_uid`, `group_6`, `group_5`, `group_2`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(88, 'oz.dashboard', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(89, 'oz.login.bo', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(90, 'oz.login.fo', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(91, 'oz.lostpass.bo', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(92, 'oz.lostpass.fo', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(93, 'oz.message', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(94, 'oz.reset.bo', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(95, 'oz.reset.fo', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(96, 'oz.system', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(97, 'oz.system.logs', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(98, 'oz.system.logs.audit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(99, 'oz.system.logs.audit.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(100, 'oz.system.logs.error', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(101, 'oz.system.settings', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(121, 'oz.system.settings.alerts', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(122, 'oz.system.settings.alerts.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(123, 'oz.system.settings.alerts.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(124, 'oz.system.settings.alerts.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(125, 'oz.system.settings.alerts.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(126, 'oz.system.settings.country', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(127, 'oz.system.settings.country.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(128, 'oz.system.settings.country.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(129, 'oz.system.settings.country.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(130, 'oz.system.settings.country.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(131, 'oz.system.settings.country.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(132, 'oz.system.settings.currency', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(133, 'oz.system.settings.currency.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(134, 'oz.system.settings.currency.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(135, 'oz.system.settings.currency.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(136, 'oz.system.settings.currency.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(137, 'oz.system.settings.currency.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(138, 'oz.system.settings.custom_field', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(139, 'oz.system.settings.custom_field.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(140, 'oz.system.settings.custom_field.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(141, 'oz.system.settings.custom_field.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(142, 'oz.system.settings.custom_field.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(143, 'oz.system.settings.custom_field.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(178, 'oz.system.settings.mailer', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(179, 'oz.system.settings.mailer.settings', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(180, 'oz.system.settings.mailer.template', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(181, 'oz.system.settings.modules', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(182, 'oz.system.settings.profile', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(183, 'oz.system.settings.profile.changepassword', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(184, 'oz.system.settings.profile.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(185, 'oz.system.settings.search_management', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(186, 'oz.system.settings.search_management.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(187, 'oz.system.settings.search_management.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(188, 'oz.system.settings.search_management.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(189, 'oz.system.settings.search_management.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(190, 'oz.system.settings.search_management.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(191, 'oz.system.settings.site', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(192, 'oz.system.settings.site.banner', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(193, 'oz.system.settings.site.banner.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(194, 'oz.system.settings.site.banner.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(195, 'oz.system.settings.site.banner.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(196, 'oz.system.settings.site.banner.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(197, 'oz.system.settings.site.banner.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(198, 'oz.system.settings.site.settings', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(199, 'oz.system.settings.system_field', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(200, 'oz.system.settings.system_field.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(201, 'oz.system.settings.system_field.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(202, 'oz.system.settings.system_field.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(203, 'oz.system.settings.system_field.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(204, 'oz.system.user_management', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(205, 'oz.system.user_management.usergroups', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(206, 'oz.system.user_management.usergroups.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(207, 'oz.system.user_management.usergroups.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(208, 'oz.system.user_management.usergroups.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(209, 'oz.system.user_management.usergroups.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(210, 'oz.system.user_management.usergroups.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(211, 'oz.system.user_management.users', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(212, 'oz.system.user_management.users.changepassword', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(213, 'oz.system.user_management.users.delete', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(214, 'oz.system.user_management.users.edit', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(215, 'oz.system.user_management.users.list', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(216, 'oz.system.user_management.users.new', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(217, 'oz.system.user_management.users.resetpassword', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(218, 'oz.system.user_management.users.view', 1, 1, 1, 1, '2014-08-29 14:57:38', 0, '0000-00-00 00:00:00'),
	(441, 'oz.system.settings.update', 1, 1, 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_privileges` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_privileges_fields`;
CREATE TABLE IF NOT EXISTS `sys_privileges_fields` (
  `id` int(10) NOT NULL,
  `module_uid` varchar(250) NOT NULL,
  `field` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `access` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `Module ID` (`module_uid`),
  KEY `Module Field` (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `sys_privileges_fields`;
/*!40000 ALTER TABLE `sys_privileges_fields` DISABLE KEYS */;
INSERT INTO `sys_privileges_fields` (`id`, `module_uid`, `field`, `status`, `access`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'customer', 'Sales Person', 1, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(2, 'customer', 'Area', 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(3, 'customer', 'Business Type', 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(4, 'customer', 'Country', 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(5, 'contacts', 'Contact Owner', 1, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(6, 'contacts', 'Country', 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(7, 'project_management.projects', 'Person In Charge', 1, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(8, 'project_management.projects', 'Project Type', 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(9, 'item_management.items', 'Category', 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(10, 'item_management.items', 'Group', 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(11, 'transactions.so', 'Sales Person', 1, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_privileges_fields` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_privileges_records`;
CREATE TABLE IF NOT EXISTS `sys_privileges_records` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL,
  `module_uid` varchar(250) NOT NULL,
  `module_display` varchar(250) NOT NULL,
  `field_id` int(10) NOT NULL,
  `field` varchar(250) NOT NULL,
  `view` varchar(250) NOT NULL,
  `type` varchar(250) DEFAULT NULL,
  `created_by` int(10) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `Module ID` (`module_uid`),
  KEY `Module Field` (`field`),
  KEY `Module Type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `sys_privileges_records`;
/*!40000 ALTER TABLE `sys_privileges_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_privileges_records` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_running_number`;
CREATE TABLE IF NOT EXISTS `sys_running_number` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_uid` varchar(250) NOT NULL,
  `current` int(10) NOT NULL,
  `padding` int(10) NOT NULL,
  `prefix` varchar(250) NOT NULL,
  `suffix` varchar(250) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

DELETE FROM `sys_running_number`;
/*!40000 ALTER TABLE `sys_running_number` DISABLE KEYS */;
INSERT INTO `sys_running_number` (`id`, `module_uid`, `current`, `padding`, `prefix`, `suffix`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'transactions.so', 7, 5, 'SO', '', 1, '2013-09-18 00:00:00', 0, '0000-00-00 00:00:00'),
	(2, 'customers.cust_no', 0, 8, 'CUST', 'A', 1, '2014-02-06 00:00:00', 0, '0000-00-00 00:00:00'),
	(3, 'transactions.quotation', 3, 5, 'Q', '', 0, '2014-10-15 00:00:00', 0, '0000-00-00 00:00:00'),
	(4, 'transactions.sample', 3, 5, 'SL', '', 0, '2014-10-15 00:00:00', 0, '0000-00-00 00:00:00'),
	(5, 'projects.project_no', 0, 6, 'PRO', 'A', 1, '2014-12-30 00:00:00', 0, '0000-00-00 00:00:00'),
	(6, 'vendor.vend_no', 0, 6, 'VD', 'A', 1, '2014-12-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(7, 'contracts.contract_no', 0, 6, 'CTR', 'A', 1, '2015-01-14 00:00:00', 0, '0000-00-00 00:00:00'),
	(8, 'transactions.cn', 5, 5, 'CN', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(9, 'transactions.collection', 14, 5, 'CL', '', 0, '2015-04-16 00:00:00', 0, '0000-00-00 00:00:00'),
	(10, 'transactions.cash_sales', 10, 5, 'CS', '', 0, '2015-04-16 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_running_number` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_search`;
CREATE TABLE IF NOT EXISTS `sys_search` (
  `id` int(10) NOT NULL,
  `module_uid` varchar(150) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `db` varchar(250) NOT NULL,
  `global_area` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(10) NOT NULL DEFAULT '0',
  `desc_field` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `sys_search`;
/*!40000 ALTER TABLE `sys_search` DISABLE KEYS */;
INSERT INTO `sys_search` (`id`, `module_uid`, `module_name`, `db`, `global_area`, `order`, `desc_field`, `status`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'customer.view', 'Customer', 'customers', 1, 2, 'name', 1, 1, '2014-07-31 00:00:00', 0, '2014-08-06 16:56:09'),
	(2, 'contacts.view', 'Contacts', 'contacts_view', 1, 1, 'first_name', 1, 1, '2014-07-31 00:00:00', 0, '2014-08-06 16:45:37'),
	(3, 'salesperson.view', 'Sales Person', 'salesperson', 1, 3, 'name', 1, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(4, 'project_management.projects.view', 'Projects', 'projects_view', 1, 4, 'project_name', 1, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(5, 'project_management.stage.view', 'Project Stage', 'projects_stage', 1, 5, 'project_stage', 0, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(6, 'project_management.type.view', 'Project Type', 'projects_type', 1, 6, 'project_type', 0, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(7, 'item_management.items.view', 'Items', 'items', 1, 0, 'name', 1, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(8, 'item_management.category.view', 'Item Categories', 'items_category', 1, 8, 'category', 0, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(9, 'item_management.groups.view', 'Item Groups', 'items_groups', 1, 9, 'group', 0, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(10, 'item_management.departments.view', 'Item Departments', 'items_departments', 1, 10, 'department', 0, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(11, 'item_management.misc.view', 'Item Miscellaneous', 'items_misc', 1, 11, 'name', 0, 1, '2014-07-31 00:00:00', 0, '0000-00-00 00:00:00'),
	(12, 'vendor.view', 'Vendor', 'vendors', 1, 12, 'name', 1, 1, '2015-01-28 00:00:00', 0, '0000-00-00 00:00:00'),
	(13, 'project_management.contracts_customer.view', 'Contracts (Customers)', 'contracts', 1, 13, 'name', 1, 1, '2015-01-28 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_search` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_search_field`;
CREATE TABLE IF NOT EXISTS `sys_search_field` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `search_id` int(10) NOT NULL,
  `field` varchar(250) NOT NULL,
  `field_name` varchar(250) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

DELETE FROM `sys_search_field`;
/*!40000 ALTER TABLE `sys_search_field` DISABLE KEYS */;
INSERT INTO `sys_search_field` (`id`, `search_id`, `field`, `field_name`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(6, 1, 'name', 'Customer Name', 1, '2014-08-18 10:32:04', 0, '0000-00-00 00:00:00'),
	(2, 2, 'first_name', 'First Name', 1, '2014-08-18 10:22:42', 0, '0000-00-00 00:00:00'),
	(3, 2, 'last_name', 'Last Name', 1, '2014-08-18 10:22:54', 0, '0000-00-00 00:00:00'),
	(4, 2, 'customer_id', 'Customer Name', 1, '2014-08-18 10:31:09', 0, '0000-00-00 00:00:00'),
	(5, 2, 'agent_id', 'Sales Person Name', 1, '2014-08-18 10:31:16', 0, '0000-00-00 00:00:00'),
	(7, 1, 'cust_no', 'Customer No', 1, '2014-08-18 10:32:09', 0, '0000-00-00 00:00:00'),
	(8, 7, 'item_no_1', 'Item No. 1', 1, '2014-08-18 10:33:01', 0, '0000-00-00 00:00:00'),
	(9, 7, 'item_no_2', 'Item No. 2', 1, '2014-08-18 10:33:08', 0, '0000-00-00 00:00:00'),
	(10, 7, 'name', 'Item Name', 1, '2014-08-18 10:34:29', 0, '0000-00-00 00:00:00'),
	(11, 4, 'project_name', 'Project Name', 1, '2014-08-18 10:35:16', 0, '0000-00-00 00:00:00'),
	(12, 3, 'code', 'Sales Person Code', 1, '2014-08-18 10:35:48', 0, '0000-00-00 00:00:00'),
	(13, 3, 'username', 'User Account', 1, '2014-08-18 10:35:54', 0, '0000-00-00 00:00:00'),
	(14, 3, 'name', 'Sales Person Name', 1, '2014-08-18 10:36:00', 0, '0000-00-00 00:00:00'),
	(15, 3, 'email', 'Email Address', 1, '2014-08-18 10:36:43', 0, '0000-00-00 00:00:00'),
	(16, 12, 'name', 'Vendor Name', 1, '2015-01-28 00:00:00', 0, '0000-00-00 00:00:00'),
	(17, 13, 'name', 'Contracts (Customers) Name', 1, '2015-01-28 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_search_field` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_security_question`;
CREATE TABLE IF NOT EXISTS `sys_security_question` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `Question` (`question`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

DELETE FROM `sys_security_question`;
/*!40000 ALTER TABLE `sys_security_question` DISABLE KEYS */;
INSERT INTO `sys_security_question` (`id`, `question`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'What is your pet name?', 1, '2013-08-01 00:00:00', 0, '0000-00-00 00:00:00'),
	(2, 'What is the name of your favorite childhood friend?', 1, '2013-08-01 00:00:00', 0, '0000-00-00 00:00:00'),
	(3, 'What was your childhood nickname?', 1, '2013-08-01 00:00:00', 0, '0000-00-00 00:00:00'),
	(4, 'What is the name of the company of your first job?', 1, '2013-08-01 00:00:00', 0, '0000-00-00 00:00:00'),
	(5, 'What time of the day were you born?', 1, '2013-08-01 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_security_question` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_seo`;
CREATE TABLE IF NOT EXISTS `sys_seo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_uid` varchar(150) NOT NULL,
  `seo_name` varchar(250) NOT NULL,
  `seo_url` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`module_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=544 DEFAULT CHARSET=utf8;

DELETE FROM `sys_seo`;
/*!40000 ALTER TABLE `sys_seo` DISABLE KEYS */;
INSERT INTO `sys_seo` (`id`, `module_uid`, `seo_name`, `seo_url`) VALUES
	(1, 'oz.dashboard', 'Home', ''),
	(2, 'oz.system', 'System', '/system'),
	(3, 'oz.login.bo', 'Login BO', '/login'),
	(4, 'oz.login.fo', 'Login FO', '/login/fo'),
	(5, 'oz.system.settings', 'System Settings', '/system/settings'),
	(6, 'oz.system.user_management', 'System Users', '/system/user_management'),
	(7, 'oz.system.user_management.users', 'Users', '/system/user_management/users'),
	(8, 'oz.system.user_management.usergroups', 'User Groups', '/system/user_management/usergroups'),
	(9, 'oz.system.settings.mailer', 'Mailer', '/system/settings/mailer'),
	(10, 'oz.system.settings.modules', 'Modules', '/system/settings/modules'),
	(11, 'oz.system.settings.site', 'Site', '/system/settings/site'),
	(12, 'oz.system.logs', 'Logs', '/system/logs'),
	(13, 'oz.system.logs.audit', 'Audit Trails', '/system/logs/audit'),
	(14, 'oz.system.logs.error', 'Error Logs', '/system/logs/error'),
	(15, 'oz.system.settings.country', 'Country', '/system/settings/country'),
	(16, 'oz.system.user_management.users.list', 'User List', '/system/user_management/users/list'),
	(17, 'oz.system.user_management.users.new', 'New User', '/system/user_management/users/new'),
	(18, 'oz.system.user_management.users.view', 'View User', '/system/user_management/users/view'),
	(19, 'oz.system.user_management.users.edit', 'Edit User', '/system/user_management/users/edit'),
	(58, 'oz.system.settings.country.list', 'Country List', '/system/settings/country/list'),
	(20, 'oz.system.logs.audit.view', 'View Audit Trails', '/system/logs/audit/view'),
	(21, 'oz.system.user_management.usergroups.list', 'User Group List', '/system/user_management/usergroups/list'),
	(22, 'oz.system.user_management.usergroups.new', 'New User Group', '/system/user_management/usergroups/new'),
	(23, 'oz.system.user_management.usergroups.view', 'View User Group', '/system/user_management/usergroups/view'),
	(24, 'oz.system.user_management.usergroups.edit', 'Edit User Group', '/system/user_management/usergroups/edit'),
	(41, 'oz.system.settings.currency', 'Currency', '/system/settings/currency'),
	(42, 'oz.system.settings.currency.list', 'Currency List', '/system/settings/currency/list'),
	(43, 'oz.system.settings.currency.new', 'New Currency', '/system/settings/currency/new'),
	(44, 'oz.system.settings.currency.view', 'View Currency', '/system/settings/currency/view'),
	(45, 'oz.system.settings.currency.edit', 'Edit Currency', '/system/settings/currency/edit'),
	(59, 'oz.system.settings.country.new', 'New Country', '/system/settings/country/new'),
	(60, 'oz.system.settings.country.view', 'View Country', '/system/settings/country/view'),
	(61, 'oz.system.settings.country.edit', 'Edit Country', '/system/settings/country/edit'),
	(130, 'oz.system.settings.activitity_management', 'Activity Management', '/system/settings/activitity_management'),
	(131, 'oz.system.settings.activitity_management.activities', 'Activities', '/system/settings/activitity_management/activities'),
	(132, 'oz.system.settings.activitity_management.activities.list', 'Activity List', '/system/settings/activitity_management/activities/list'),
	(133, 'oz.system.settings.activitity_management.activities.new', 'New Activity', '/system/settings/activitity_management/activities/new'),
	(134, 'oz.system.settings.activitity_management.activities.view', 'View Activity', '/system/settings/activitity_management/activities/view'),
	(135, 'oz.system.settings.activitity_management.activities.edit', 'Edit Activity', '/system/settings/activitity_management/activities/edit'),
	(141, 'oz.system.settings.activitity_management.communication_type', 'Communication Type', '/system/settings/activitity_management/communication_type'),
	(142, 'oz.system.settings.activitity_management.communication_type.list', 'Communication Type List', '/system/settings/activitity_management/communication_type/list'),
	(143, 'oz.system.settings.activitity_management.communication_type.new', 'New Communication Type', '/system/settings/activitity_management/communication_type/new'),
	(144, 'oz.system.settings.activitity_management.communication_type.view', 'View Communication Type', '/system/settings/activitity_management/communication_type/view'),
	(145, 'oz.system.settings.activitity_management.communication_type.edit', 'Edit Communication Type', '/system/settings/activitity_management/communication_type/edit'),
	(146, 'oz.system.settings.mailer.template', 'Mailer Template', '/system/settings/mailer/template'),
	(147, 'oz.system.settings.mailer.settings', 'Mailer Settings', '/system/settings/mailer/config'),
	(148, 'oz.system.user_management.users.resetpassword', 'Reset Password', '/system/user_management/users/resetpassword'),
	(149, 'oz.system.user_management.users.changepassword', 'Change Password', '/system/user_management/users/changepassword'),
	(150, 'oz.lostpass.bo', 'Account Recovery BO', '/lostpass'),
	(151, 'oz.lostpass.fo', 'Account Recovery FO', '/lostpass/fo'),
	(152, 'oz.reset.bo', 'Reset Password BO', '/reset'),
	(153, 'oz.reset.fo', 'Reset Password FO', '/reset/fo'),
	(162, 'oz.system.settings.custom_field', 'Custom Fields', '/system/settings/custom_field'),
	(163, 'oz.system.settings.custom_field.list', 'Custom Fields List', '/system/settings/custom_field/list'),
	(164, 'oz.system.settings.custom_field.new', 'New Custom Field', '/system/settings/custom_field/new'),
	(165, 'oz.system.settings.custom_field.view', 'View Custom Field', '/system/settings/custom_field/view'),
	(166, 'oz.system.settings.custom_field.edit', 'Edit Custom Field', '/system/settings/custom_field/edit'),
	(171, 'oz.system.settings.system_field', 'System Fields', '/system/settings/system_field'),
	(172, 'oz.system.settings.system_field.list', 'System Fields List', '/system/settings/system_field/list'),
	(173, 'oz.system.settings.system_field.new', 'New System Field', '/system/settings/system_field/new'),
	(174, 'oz.system.settings.system_field.view', 'View System Field', '/system/settings/system_field/view'),
	(175, 'oz.system.settings.system_field.edit', 'Edit System Field', '/system/settings/system_field/edit'),
	(215, 'oz.message', 'Message', '/message'),
	(216, 'oz.system.settings.alerts', 'Alerts', '/system/settings/alerts'),
	(217, 'oz.system.settings.alerts.new', 'New Alert', '/system/settings/alerts/new'),
	(218, 'oz.system.settings.alerts.view', 'View Alert', '/system/settings/alerts/view'),
	(219, 'oz.system.settings.alerts.edit', 'Edit Alert', '/system/settings/alerts/edit'),
	(220, 'oz.system.settings.alerts.list', 'Alerts List', '/system/settings/alerts/list'),
	(221, 'oz.system.settings.site.banner', 'Banner', '/system/settings/site/banner'),
	(222, 'oz.system.settings.site.banner.new', 'New Banner', '/system/settings/site/banner/new'),
	(223, 'oz.system.settings.site.banner.view', 'View Banner', '/system/settings/site/banner/view'),
	(224, 'oz.system.settings.site.banner.list', 'List Banner', '/system/settings/site/banner/list'),
	(237, 'oz.system.settings.profile', 'Profile', '/system/settings/profile'),
	(238, 'oz.system.settings.profile.edit', 'Edit Profile', '/system/settings/profile/edit'),
	(239, 'oz.system.settings.profile.changepassword', 'Change Password', '/system/settings/profile/changepassword'),
	(240, 'oz.system.settings.site.settings', 'Settings', '/system/settings/site/settings'),
	(247, 'oz.system.settings.site.banner.edit', 'Edit Banner', '/system/settings/site/banner/edit'),
	(251, 'oz.system.settings.search_management', 'Search Management', '/system/settings/search_management'),
	(252, 'oz.system.settings.search_management.list', 'Search Management List', '/system/settings/search_management/list'),
	(253, 'oz.system.settings.search_management.new', 'New Search Management', '/system/settings/search_management/new'),
	(254, 'oz.system.settings.search_management.view', 'View Search Management', '/system/settings/search_management/view'),
	(255, 'oz.system.settings.search_management.edit', 'Edit Search Management', '/system/settings/search_management/edit'),
	(518, 'oz.system.settings.update', 'Update', '/system/settings/update');
/*!40000 ALTER TABLE `sys_seo` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_session`;
CREATE TABLE IF NOT EXISTS `sys_session` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `session` varchar(250) NOT NULL,
  `ip_address` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `User ID` (`user_id`),
  KEY `Session` (`session`),
  KEY `IP` (`ip_address`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

DELETE FROM `sys_session`;
/*!40000 ALTER TABLE `sys_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_session` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_settings`;
CREATE TABLE IF NOT EXISTS `sys_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `theme_fo` varchar(250) NOT NULL,
  `theme_bo` varchar(250) NOT NULL,
  `language` varchar(250) NOT NULL,
  `polaris_cdn` tinyint(1) NOT NULL DEFAULT '0',
  `first_time_login` tinyint(1) NOT NULL DEFAULT '0',
  `is_maintenance` tinyint(1) NOT NULL,
  `maintenance_ip` mediumtext NOT NULL,
  `max_login_attempt` int(10) NOT NULL,
  `max_login_lockdown` int(10) NOT NULL,
  `enable_chat` tinyint(1) NOT NULL DEFAULT '1',
  `debug_mode` tinyint(1) NOT NULL DEFAULT '0',
  `default_currency_id` int(10) NOT NULL,
  `default_tax_id` int(10) NOT NULL,
  `def_pricedecimal` int(5) NOT NULL,
  `special_pricedecimal` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DELETE FROM `sys_settings`;
/*!40000 ALTER TABLE `sys_settings` DISABLE KEYS */;
INSERT INTO `sys_settings` (`id`, `theme_fo`, `theme_bo`, `language`, `polaris_cdn`, `first_time_login`, `is_maintenance`, `maintenance_ip`, `max_login_attempt`, `max_login_lockdown`, `enable_chat`, `debug_mode`, `default_currency_id`, `default_tax_id`, `def_pricedecimal`, `special_pricedecimal`) VALUES
	(1, 'default', 'default', 'en', 0, 0, 0, '', 5, 5, 0, 0, 0, 0, 2, 4);
/*!40000 ALTER TABLE `sys_settings` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_updater`;
CREATE TABLE IF NOT EXISTS `sys_updater` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `version` decimal(8,2) NOT NULL,
  `updater_server` text NOT NULL,
  `updater_option` varchar(50) NOT NULL,
  `updater_status` int(2) NOT NULL,
  `updater_mode` int(11) NOT NULL,
  `last_run` datetime NOT NULL,
  `last_backup_db` datetime NOT NULL,
  `last_backup_script` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DELETE FROM `sys_updater`;
/*!40000 ALTER TABLE `sys_updater` DISABLE KEYS */;
INSERT INTO `sys_updater` (`id`, `version`, `updater_server`, `updater_option`, `updater_status`, `updater_mode`, `last_run`, `last_backup_db`, `last_backup_script`) VALUES
	(1, 1.00, 'https://console.touchsales.net/updater', 'ifes', 0, 0, '0000-00-00 00:00:00', '2017-05-05 21:10:43', '2017-05-05 21:11:26');
/*!40000 ALTER TABLE `sys_updater` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_usergroups`;
CREATE TABLE IF NOT EXISTS `sys_usergroups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(250) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Group Name` (`group_name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

DELETE FROM `sys_usergroups`;
/*!40000 ALTER TABLE `sys_usergroups` DISABLE KEYS */;
INSERT INTO `sys_usergroups` (`id`, `group_name`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(-1, 'Developer', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
	(1, 'Admin', 0, '0000-00-00 00:00:00', 1, '2015-07-01 11:42:15'),
	(2, 'User', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sys_usergroups` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `salt` varchar(250) NOT NULL,
  `uid` varchar(250) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `access` enum('fo','bo','both') NOT NULL DEFAULT 'fo',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `lang_id` int(10) NOT NULL DEFAULT '1',
  `sec_question` text NOT NULL,
  `sec_id` int(10) NOT NULL,
  `sec_answer` text NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `First Name` (`first_name`),
  KEY `Last Name` (`last_name`),
  KEY `Email` (`email`),
  KEY `Mobile` (`phone`),
  KEY `Username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

DELETE FROM `sys_users`;
/*!40000 ALTER TABLE `sys_users` DISABLE KEYS */;
INSERT INTO `sys_users` (`id`, `username`, `password`, `salt`, `uid`, `first_name`, `last_name`, `email`, `phone`, `access`, `status`, `lang_id`, `sec_question`, `sec_id`, `sec_answer`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 'admin', '80ef8cd0a6ca56b75234ef4e264ebecefdeadd5a98789a26ff669eea9082524c', 'psoOTUV1wNG3ePK ', 'xe1yihemzgjjrl37nxhn9euvunihp8', 'Polaris', 'Admin', 'ferdie.putrawan@polarisnet.com.my', '', 'both', 1, 1, 'Math', 0, 'YRZ60kPajcgnU6xRKWNF4QD61aiITkB5wcTOQgb6KAc=', 0, '0000-00-00 00:00:00', 1, '2014-02-20 16:02:39'),
	(8, 'developer', 'ed9929a48d5be40b3204560f6563b4bb9fe5f19327943672006b1904145a954c', 'M79FDlGqk1DVP2r', 'hhbpcmmybwrezsbetofmnahdxxv2ec', 'Developer', 'Polaris', 'ferdie_int@yahoo.com', '', 'bo', 1, 1, '', 1, 'xz7vXp6DEjBdF8giWdEhpKVvzqE6kwsMqAjaIm3qMc8=', 1, '2013-11-20 09:23:01', 1, '2016-11-11 10:44:32');
/*!40000 ALTER TABLE `sys_users` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_users_groups`;
CREATE TABLE IF NOT EXISTS `sys_users_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(10) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `User ID` (`user_id`),
  KEY `Group ID` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

DELETE FROM `sys_users_groups`;
/*!40000 ALTER TABLE `sys_users_groups` DISABLE KEYS */;
INSERT INTO `sys_users_groups` (`id`, `user_id`, `group_id`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
	(1, 1, 1, 0, '0000-00-00 00:00:00', 1, '2014-02-20 15:03:25'),
	(9, 8, 1, 1, '2013-11-20 09:23:01', 1, '2013-11-20 09:24:37');
/*!40000 ALTER TABLE `sys_users_groups` ENABLE KEYS */;

DROP TABLE IF EXISTS `sys_users_reset`;
CREATE TABLE IF NOT EXISTS `sys_users_reset` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `reset_token` varchar(40) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `sys_users_reset`;
/*!40000 ALTER TABLE `sys_users_reset` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_users_reset` ENABLE KEYS */;

DROP TABLE IF EXISTS `websocket_population`;
CREATE TABLE IF NOT EXISTS `websocket_population` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `username` varchar(250) NOT NULL,
  `display_name` varchar(500) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ip` varchar(250) NOT NULL,
  `resource_id` int(10) NOT NULL,
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `websocket_population`;
/*!40000 ALTER TABLE `websocket_population` DISABLE KEYS */;
/*!40000 ALTER TABLE `websocket_population` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
