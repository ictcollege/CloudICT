-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2015 at 09:14 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ictcloud`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `create_notification`(IN `iduser` INT(10), IN `idnotificationtype` INT(10), IN `idapp` INT(10), IN `idevent` INT(10), IN `userfullname` VARCHAR(100), IN `usernotificationdescription` VARCHAR(255))
BEGIN 
DECLARE notification_created INT(10) UNSIGNED DEFAULT 0;
 SET notification_created = UNIX_TIMESTAMP();
 INSERT INTO `usernotification` (
     `IdUser`,
     `IdNotificationType`,
     `IdApp`,
     `IdEvent`,
     `UserFullname`,
     `UserNotificationDescription`,
     `UserNotificationCreated`,
     `UserNotificationTimeExpires`,
     `UserNotificationStatus`)
     VALUES (
         iduser,
         idnotificationtype,
         idapp,
         idevent,
         userfullname,
         usernotificationdescription,
         notification_created,
         0,
         0);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `scheduled_notification_deletion`()
BEGIN 

	DELETE FROM `usernotification`
	WHERE `usernotification`.`UserNotificationTimeExpires` < UNIX_TIMESTAMP();
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_conf_notification_time`(IN `new_expire_time` INT(10))
BEGIN 
							
UPDATE `cloudconf`
SET `cloudconf`.`CloudConfValue` = new_expire_time
WHERE `cloudconf`.`CloudConfKey` = 'NotificationTimeExpires';

 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_notification_expire_time`(IN `IdUser` INT(10))
BEGIN 
DECLARE NtfNtfTimeExpire INT(10) UNSIGNED DEFAULT 0;
	 SET NtfNtfTimeExpire = 	(	SELECT `CloudConfValue` 
									FROM `cloudconf` 
									WHERE `cloudconf`.`CloudConfKey` = 'NotificationTimeExpires'
								);

	 UPDATE `usernotification`
	 SET `usernotification`.`UserNotificationTimeExpires`=NtfNtfTimeExpire +  `usernotification`.`UserNotificationCreated`
	 WHERE `usernotification`.`IdUser`=IdUser;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_notification_status`(
	IdUser int(10)
 )
BEGIN 
	 

	 UPDATE `usernotification`
	 SET `usernotification`.`UserNotificationStatus`=1
	 WHERE `usernotification`.`IdUser`=IdUser;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `app`
--

CREATE TABLE IF NOT EXISTS `app` (
`IdApp` int(10) unsigned NOT NULL,
  `AppName` varchar(255) NOT NULL,
  `AppLink` varchar(255) NOT NULL,
  `AppIcon` varchar(255) NOT NULL,
  `AppStatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1,2\n0: nije admin\n1: admin grupe\n2: admin',
  `AppOrder` mediumint(3) NOT NULL,
  `AppColor` varchar(250) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app`
--

INSERT INTO `app` (`IdApp`, `AppName`, `AppLink`, `AppIcon`, `AppStatus`, `AppOrder`, `AppColor`) VALUES
(2, 'Files', 'files/', 'fa-folder', 0, 0, '#e69318'),
(3, 'Users', 'admin/users/', 'fa-users', 2, 3, '#00bcf7'),
(4, 'System', 'admin/applications', 'fa-gear', 1, 4, '#ff0000');

-- --------------------------------------------------------

--
-- Table structure for table `appmenu`
--

CREATE TABLE IF NOT EXISTS `appmenu` (
`IdAppMenu` int(10) unsigned NOT NULL,
  `IdApp` int(10) unsigned NOT NULL,
  `AppMenuName` varchar(255) NOT NULL,
  `AppMenuLink` varchar(255) NOT NULL,
  `AppMenuIcon` varchar(255) NOT NULL,
  `AppMenuOrder` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appmenu`
--

INSERT INTO `appmenu` (`IdAppMenu`, `IdApp`, `AppMenuName`, `AppMenuLink`, `AppMenuIcon`, `AppMenuOrder`) VALUES
(1, 2, 'All', 'files', 'fa-files-o', 1),
(2, 2, 'Favourites', 'files/favourites', 'fa-star-o', 2),
(3, 2, 'Shared', 'files/shared_with_you', 'fa-share-alt', 3),
(4, 2, 'Shared', 'files/shared_with_others', 'fa-share', 4),
(5, 2, 'Shared', 'files/shared_by_link', 'fa-link', 5),
(6, 3, 'Users', 'admin/users', 'fa-user', 1),
(7, 3, 'Groups', 'admin/groups', 'fa-users', 2),
(8, 3, 'Privileges', 'admin/privileges', 'fa-wrench', 3),
(31, 4, 'Appications', 'admin/applications', 'fa-puzzle-piece', 1),
(41, 21, 'x', 'x', 'fa-arrow-circle-left', NULL),
(42, 21, 'y', 'y', 'fa-archive', NULL),
(43, 5, 'menu 1 ', 'link 1', 'fa-adn', NULL),
(44, 5, 'menu 2', 'link 2', 'fa-align-center', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chatmessage`
--

CREATE TABLE IF NOT EXISTS `chatmessage` (
`IdChatMessage` int(10) unsigned NOT NULL,
  `IdGroup` int(11) DEFAULT NULL,
  `IdSender` int(10) unsigned NOT NULL,
  `IdReceiver` int(10) unsigned NOT NULL,
  `ChatMessageText` text NOT NULL,
  `ChatMessageTime` int(10) unsigned NOT NULL,
  `ChatMessageSenderName` varchar(100) NOT NULL,
  `ChatMessageReceiverName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cloudconf`
--

CREATE TABLE IF NOT EXISTS `cloudconf` (
  `CloudConfKey` varchar(255) NOT NULL,
  `CloudConfValue` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cloudconf`
--

INSERT INTO `cloudconf` (`CloudConfKey`, `CloudConfValue`) VALUES
('NotificationTimeExpires', '2592000'),
('UserDiskQuota', '5368709120');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `IdUser` int(10) unsigned NOT NULL,
  `IdContactUser` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbconf`
--

CREATE TABLE IF NOT EXISTS `dbconf` (
  `DbConfKey` varchar(255) NOT NULL,
  `DbConfValue` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
`IdFile` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `IdFileType` int(10) unsigned NOT NULL,
  `IdFolder` int(10) unsigned DEFAULT NULL,
  `FileExtension` varchar(50) DEFAULT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(300) NOT NULL,
  `FileSize` int(10) unsigned NOT NULL,
  `FileCreated` int(10) unsigned NOT NULL,
  `FileLastModified` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`IdFile`, `IdUser`, `IdFileType`, `IdFolder`, `FileExtension`, `FileName`, `FilePath`, `FileSize`, `FileCreated`, `FileLastModified`) VALUES
(20, 1, 4, 17, 'png', 'maintance.png', 'c:/xampp/htdocs/cloudict/data/1/neki_folder/maintance.png', 566095, 1440699963, 1440699963),
(21, 1, 12, 17, 'docx', 'BazeIspit.docx', 'c:/xampp/htdocs/cloudict/data/1/neki_folder/bazeispit.docx', 60543, 1440699984, 1440699984),
(27, 1, 13, 22, 'zip', 'documents-export-2015-08-27.zip', 'c:/xampp/htdocs/cloudict/data/1/bla1/documents-export-2015-08-27.zip', 115840, 1440758040, 1440758040),
(36, 1, 2, 35, 'jpg', 'IMG_0002.JPG', 'c:/xampp/htdocs/cloudict/data/1/share_folder/img_0002.jpg', 4000000, 1441037782, 1441037782),
(37, 1, 2, 35, 'jpg', 'IMG_0001.JPG', 'c:/xampp/htdocs/cloudict/data/1/share_folder/img_0001.jpg', 4000000, 1441037782, 1441037782),
(38, 1, 2, 35, 'jpg', 'IMG_0003.JPG', 'c:/xampp/htdocs/cloudict/data/1/share_folder/img_0003.jpg', 3835892, 1441037784, 1441037784),
(40, 1, 1, 0, NULL, 'asdsa', 'c:/xampp/htdocs/cloudict/data/1/asdsa', 0, 1441191964, 1441191964),
(41, 1, 9, 0, 'sql', 'ictcloud.sql', 'c:/xampp/htdocs/cloudict-darko/data/1/ictcloud.sql', 24340, 1441292784, 1441292784),
(42, 1, 4, 0, 'png', 'ictcloud.png', 'c:/xampp/htdocs/cloudict-darko/data/1/ictcloud.png', 139323, 1441292785, 1441292785),
(43, 1, 6, 0, 'txt', 'UPUTSTVO.TXT', 'c:/xampp/htdocs/cloudict-darko/data/1/uputstvo.txt', 689, 1441292785, 1441292785),
(44, 2, 2, 0, 'jpg', 'IMG_20150118_235154.jpg', 'c:/xampp/htdocs/cloudict-darko/data/2/img_20150118_235154.jpg', 819034, 1441656545, 1441656545),
(45, 2, 2, 0, 'jpg', 'IMG_20150106_113642.jpg', 'c:/xampp/htdocs/cloudict-darko/data/2/img_20150106_113642.jpg', 1204138, 1441656545, 1441656545),
(46, 2, 2, 0, 'jpg', 'IMG_20150108_175124.jpg', 'c:/xampp/htdocs/cloudict-darko/data/2/img_20150108_175124.jpg', 2005503, 1441656546, 1441656546),
(47, 2, 2, 0, 'jpg', 'IMG_20150108_175044.jpg', 'c:/xampp/htdocs/cloudict-darko/data/2/img_20150108_175044.jpg', 1769732, 1441656547, 1441656547),
(48, 2, 2, 0, 'jpg', 'IMG_20150106_113715.jpg', 'c:/xampp/htdocs/cloudict-darko/data/2/img_20150106_113715.jpg', 1470936, 1441656547, 1441656547);

-- --------------------------------------------------------

--
-- Table structure for table `filetype`
--

CREATE TABLE IF NOT EXISTS `filetype` (
`IdFileType` int(10) unsigned NOT NULL,
  `FileTypeMime` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filetype`
--

INSERT INTO `filetype` (`IdFileType`, `FileTypeMime`) VALUES
(1, 'DIR'),
(2, 'image/jpeg'),
(3, 'application/pdf'),
(4, 'image/png'),
(5, ''),
(6, 'text/plain'),
(7, 'multipart/form-data; boundary=---------------------------7df14d1320278'),
(8, 'multipart/form-data; boundary=---------------------------7df2703620278'),
(9, 'application/octet-stream'),
(10, 'application/vnd.ms-excel'),
(11, 'image/gif'),
(12, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
(13, 'application/zip'),
(14, 'video/3gpp'),
(15, 'video/mp4');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
`IdGroup` int(10) unsigned NOT NULL,
  `GroupName` varchar(16) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`IdGroup`, `GroupName`) VALUES
(13, 'admins');

-- --------------------------------------------------------

--
-- Table structure for table `groupapp`
--

CREATE TABLE IF NOT EXISTS `groupapp` (
  `IdGroup` int(11) NOT NULL,
  `IdApp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `groupapp`
--

INSERT INTO `groupapp` (`IdGroup`, `IdApp`) VALUES
(13, 4),
(13, 2),
(13, 3),
(13, 28);

-- --------------------------------------------------------

--
-- Table structure for table `notificationtype`
--

CREATE TABLE IF NOT EXISTS `notificationtype` (
`IdNotificationType` int(10) unsigned NOT NULL,
  `NotificationTypeName` varchar(255) NOT NULL,
  `NotificationTypeIcon` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notificationtype`
--

INSERT INTO `notificationtype` (`IdNotificationType`, `NotificationTypeName`, `NotificationTypeIcon`) VALUES
(1, 'Information', 'fa fa-info'),
(2, 'Warning', 'fa fa-warning (alias)'),
(3, 'Message', 'fa fa-comment'),
(4, 'Reminder', 'fa fa-calendar-o'),
(9, 'New Task', 'fa fa-tasks'),
(10, 'New Group', 'fa fa-group (alias)'),
(11, 'Quota Exceeded', 'fa fa-exclamation-triangle'),
(12, 'Task Executed', 'fa fa-check-square-o'),
(13, 'Shared File', 'fa fa-share-alt'),
(14, 'New Application', 'fa fa-cloud'),
(15, 'Group Admin', 'fa fa-info');

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `IdFile` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `ShareCreated` int(10) unsigned NOT NULL,
  `ShareFullName` varchar(255) NOT NULL,
  `FilePath` varchar(300) NOT NULL,
  `SharePrivilege` tinyint(4) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `share`
--

INSERT INTO `share` (`IdFile`, `IdUser`, `ShareCreated`, `ShareFullName`, `FilePath`, `SharePrivilege`) VALUES
(24, 1, 1441036125, 'neki_folder', 'c:/xampp/htdocs/cloudict/data/2/neki_folder', 1),
(25, 2, 1440856523, 'ictcloud.sql', 'c:/xampp/htdocs/cloudict/data/1/ictcloud.sql', 2),
(32, 2, 1441035975, 'IMG_0001.JPG', 'c:/xampp/htdocs/cloudict/data/1/img_0001.jpg', 1),
(35, 2, 1441037811, 'share_folder', 'c:/xampp/htdocs/cloudict/data/1/share_folder', 1),
(39, 2, 1441038519, 'abc', 'c:/xampp/htdocs/cloudict/data/1/abc', 1),
(40, 2, 1441191975, 'asdsa', 'c:/xampp/htdocs/cloudict/data/1/asdsa', 1);

--
-- Triggers `share`
--
DELIMITER //
CREATE TRIGGER `share_notification_create_trigger` AFTER INSERT ON `share`
 FOR EACH ROW CAll create_notification
	(
		/*	Replace the following example values with your values	*/
		
		NEW.IdUser,    			/*	NEW.SomeID - ID of the user that will get notification	*/
		13,						/*	ID of the notification type from NotificationType table  (ex: 1-Information, 2-Warning...)	*/
		2,						/*	ID of the application from the App table that will handle the notification	*/
		NEW.IdFile,				/*	NEW.SomeID - ID of the file that will be handled	*/
		NEW.ShareFullName,		/*	FullName of the user who generated notification	*/
		'You have a new shared file'	/*	Description of the notification	*/
	)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
`IdTask` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `TaskName` varchar(255) NOT NULL,
  `TaskDescription` text,
  `TaskTimeCreated` int(10) unsigned NOT NULL,
  `TaskTimeToExecute` int(10) unsigned NOT NULL,
  `TaskExecuteType` tinyint(1) NOT NULL COMMENT '0: moraju svi da zavrse zadatak\n1: moze samo jedan da zavrsi zadatak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `taskuser`
--

CREATE TABLE IF NOT EXISTS `taskuser` (
  `IdTask` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `TaskUserFullname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TaskUserAssigned` int(10) unsigned NOT NULL,
  `TaskUserTimeExecuted` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `taskuser`
--
DELIMITER //
CREATE TRIGGER `task_notification_create_trigger` AFTER INSERT ON `taskuser`
 FOR EACH ROW CAll create_notification
	(
		/*	Replace the following example values with your values	*/
		
		NEW.IdUser,    			/*	NEW.SomeID - ID of the user that will get notification	*/
		9,						/*	ID of the notification type from NotificationType table  (ex: 1-Information, 2-Warning...)	*/
		5,						/*	ID of the application from the App table that will handle the notification	*/
		NEW.IdTask,				/*	NEW.SomeID - ID of the file that will be handled	*/
		NEW.TaskUserFullname,		/*	FullName of the user who generated notification	*/
		'You have new task'	/*	Description of the notification	*/
	)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`IdUser` int(10) unsigned NOT NULL,
  `IdRole` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: User\n2: grup admin\n3: admin',
  `UserName` varchar(16) NOT NULL,
  `UserPassword` char(32) NOT NULL,
  `UserFullname` varchar(100) NOT NULL,
  `UserEmail` varchar(255) NOT NULL DEFAULT '',
  `UserDiskQuota` bigint(19) unsigned NOT NULL DEFAULT '0',
  `UserDiskUsed` bigint(19) unsigned NOT NULL DEFAULT '0',
  `UserStatus` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'zatrebace za nesto (ban korisnika, disable...)',
  `UserKey` char(32) NOT NULL COMMENT 'kljuc koj se salje na mail korisniku koj je pozvan u cloud sistem',
  `UserKeyExpires` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`IdUser`, `IdRole`, `UserName`, `UserPassword`, `UserFullname`, `UserEmail`, `UserDiskQuota`, `UserDiskUsed`, `UserStatus`, `UserKey`, `UserKeyExpires`) VALUES
(1, 3, 'admin', 'e00cf25ad42683b3df678c61f42c6bda', 'Filip Radojkovic', 'admin@mail.com', 5, 0, 0, '0', 0),
(24, 1, 'Jericho', '55aa11e530a00777f4fff5e6df911685', 'Filip Radojkovic', 'filip.jericho.radojkovic@gmail.com', 5, 0, 1, '0ea8d15a44fdb14b4464599b88d0c6f9', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `IdUser` int(10) unsigned NOT NULL,
  `IdGroup` int(10) unsigned NOT NULL,
  `UserGroupStatusAdmin` tinyint(1) unsigned zerofill NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`IdUser`, `IdGroup`, `UserGroupStatusAdmin`) VALUES
(1, 13, 1),
(3, 13, 1);

--
-- Triggers `usergroup`
--
DELIMITER //
CREATE TRIGGER `usergroup_notification_create_trigger` AFTER INSERT ON `usergroup`
 FOR EACH ROW CAll create_notification
	(
		/*	Replace the following example values with your values	*/
		
		NEW.IdUser,    			/*	NEW.SomeID - ID of the user that will get notification	*/
		10,					/*	ID of the notification type from NotificationType table  (ex: 1-Information, 2-Warning...)	*/
		3,						/*	ID of the application from the App table that will handle the notification	*/
		NEW.IdGroup,				/*	NEW.SomeID - ID of the file that will be handled	*/
		'Admin',		/*	FullName of the user who generated notification	*/
		'You have been added to group'	/*	Description of the notification	*/
	)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE IF NOT EXISTS `userlog` (
`IdUserLog` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `UserLogLoggedIn` int(10) NOT NULL,
  `UserLogLoggedOut` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usernotification`
--

CREATE TABLE IF NOT EXISTS `usernotification` (
`IdUserNotification` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `IdNotificationType` int(10) unsigned NOT NULL COMMENT 'IdNotificationType \n0: information\n1: warning\n2: error',
  `IdApp` int(10) unsigned NOT NULL,
  `IdEvent` int(10) unsigned NOT NULL COMMENT 'IdEvent id reda koji se dodao u bazu(id task, id Share)',
  `UserFullname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `UserNotificationDescription` varchar(255) DEFAULT NULL,
  `UserNotificationCreated` int(10) unsigned NOT NULL,
  `UserNotificationTimeExpires` int(10) unsigned DEFAULT '0' COMMENT 'se popunjava kada korisnik prvi put vidi notifikaciju',
  `UserNotificationStatus` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usernotification`
--

INSERT INTO `usernotification` (`IdUserNotification`, `IdUser`, `IdNotificationType`, `IdApp`, `IdEvent`, `UserFullname`, `UserNotificationDescription`, `UserNotificationCreated`, `UserNotificationTimeExpires`, `UserNotificationStatus`) VALUES
(1, 1, 10, 4, 2, 'Admin', 'You have been added to new group', 1437129742, 1439721742, 1),
(2, 1, 1, 1, 1, 'Admin', 'Generic Description', 1437129742, 1439721742, 1),
(3, 1, 3, 1, 1, 'Admin', 'Generic Description', 1437129750, 1439721750, 1),
(4, 1, 2, 1, 1, 'Admin', 'Generic Description', 1437129751, 1439721751, 1),
(5, 66, 2, 1, 1, 'Admin', 'Generic Description', 1437136468, 1437156468, 1),
(6, 66, 1, 1, 1, 'Admin', 'Generic Description', 1437136508, 1437156508, 1),
(7, 66, 1, 1, 1, 'Admin', 'Generic Description', 1437136515, 1437156515, 1),
(8, 66, 3, 1, 1, 'Admin', 'Generic Description', 1437136520, 1437156520, 1),
(9, 66, 3, 1, 1, 'Admin', 'Generic Description', 1437136533, 1437156533, 1),
(10, 66, 10, 4, 5, 'Admin', 'You have been added to new group', 1437136543, 1437156543, 1),
(11, 66, 1, 1, 1, 'Admin', 'Generic Description', 1440601622, 1440621622, 1),
(12, 66, 2, 1, 1, 'Admin', 'Generic Description', 1440603676, 1440623676, 1),
(13, 66, 3, 1, 1, 'Admin', 'Generic Description', 1440603676, 1440623676, 1),
(14, 66, 1, 1, 1, 'Admin', 'Generic Description', 1440603677, 1440623677, 1),
(15, 66, 3, 1, 1, 'Admin', 'Generic Description', 1440603677, 1440623677, 1),
(16, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441121511, 1441141511, 1),
(17, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441121515, 1441141515, 1),
(18, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441127371, 1441147371, 1),
(19, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441127381, 1441147381, 1),
(20, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441127381, 1441147381, 1),
(21, 66, 10, 4, 10, 'Admin', 'You have been added to new group', 1441127388, 1441147388, 1),
(22, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441127401, 1441147401, 1),
(23, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441128363, 1441148363, 1),
(24, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441128413, 1441148413, 1),
(25, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441128424, 1441148424, 1),
(26, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441128425, 1441148425, 1),
(27, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441128425, 1441148425, 1),
(28, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441128425, 1441148425, 1),
(29, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441128613, 1441148613, 1),
(30, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441128631, 1441148631, 1),
(31, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441128632, 1441148632, 1),
(32, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441128807, 1441148807, 1),
(33, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441129648, 1441149648, 1),
(34, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441129764, 1441149764, 1),
(35, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441129780, 1441149780, 1),
(36, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441129781, 1441149781, 1),
(37, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441129788, 1441149788, 1),
(38, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441129789, 1441149789, 1),
(39, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441130895, 1441150895, 1),
(40, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441130913, 1441150913, 1),
(41, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441131216, 1441151216, 1),
(42, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441131217, 1441151217, 1),
(43, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441131229, 1441151229, 1),
(44, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441131231, 1441151231, 1),
(45, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441131996, 1441151996, 1),
(46, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441131998, 1441151998, 1),
(47, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441132009, 1441152009, 1),
(48, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441132073, 1441152073, 1),
(49, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441132134, 1441152134, 1),
(50, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441132326, 0, 1),
(51, 66, 2, 1, 1, 'Admin', 'Generic Description', 1441132327, 0, 1),
(52, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441132328, 0, 1),
(53, 66, 1, 1, 1, 'Admin', 'Generic Description', 1441132328, 0, 1),
(54, 66, 3, 1, 1, 'Admin', 'Generic Description', 1441132329, 0, 1),
(55, 1, 1, 2, 2, 'admin', 'nova notifikacija', 123123123, 125715123, 1),
(56, 1, 1, 2, 1, 'admin', 'nova', 123123123, 125715123, 1),
(57, 1, 3, 2, 2, 'nikola', 'proba', 123123123, 125715123, 1),
(58, 1, 10, 3, 13, 'Admin', 'You have been added to group', 1442250289, 1444842289, 1),
(59, 1, 10, 3, 14, 'Admin', 'You have been added to group', 1442592901, 1445184901, 1),
(60, 24, 10, 3, 14, 'Admin', 'You have been added to group', 1442592925, 0, 0),
(61, 1, 10, 3, 14, 'Admin', 'You have been added to group', 1442592937, 1445184937, 1),
(62, 24, 10, 3, 14, 'Admin', 'You have been added to group', 1442592942, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usertracking`
--

CREATE TABLE IF NOT EXISTS `usertracking` (
  `IdUserLog` int(10) unsigned NOT NULL,
  `UserTrackingEventDetails` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app`
--
ALTER TABLE `app`
 ADD PRIMARY KEY (`IdApp`), ADD UNIQUE KEY `IdApp_UNIQUE` (`IdApp`), ADD UNIQUE KEY `AppName_UNIQUE` (`AppName`);

--
-- Indexes for table `appmenu`
--
ALTER TABLE `appmenu`
 ADD PRIMARY KEY (`IdAppMenu`), ADD UNIQUE KEY `IdAppMenu` (`IdAppMenu`);

--
-- Indexes for table `chatmessage`
--
ALTER TABLE `chatmessage`
 ADD PRIMARY KEY (`IdChatMessage`);

--
-- Indexes for table `cloudconf`
--
ALTER TABLE `cloudconf`
 ADD PRIMARY KEY (`CloudConfKey`,`CloudConfValue`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
 ADD PRIMARY KEY (`IdUser`,`IdContactUser`);

--
-- Indexes for table `dbconf`
--
ALTER TABLE `dbconf`
 ADD PRIMARY KEY (`DbConfKey`,`DbConfValue`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
 ADD PRIMARY KEY (`IdFile`), ADD KEY `fk_File_User1_idx` (`IdUser`);

--
-- Indexes for table `filetype`
--
ALTER TABLE `filetype`
 ADD PRIMARY KEY (`IdFileType`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
 ADD PRIMARY KEY (`IdGroup`), ADD UNIQUE KEY `GroupName` (`GroupName`);

--
-- Indexes for table `notificationtype`
--
ALTER TABLE `notificationtype`
 ADD PRIMARY KEY (`IdNotificationType`);

--
-- Indexes for table `share`
--
ALTER TABLE `share`
 ADD PRIMARY KEY (`IdFile`,`IdUser`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
 ADD PRIMARY KEY (`IdTask`);

--
-- Indexes for table `taskuser`
--
ALTER TABLE `taskuser`
 ADD PRIMARY KEY (`IdTask`,`IdUser`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`IdUser`), ADD UNIQUE KEY `UserName` (`UserName`), ADD UNIQUE KEY `Email_UNIQUE` (`UserEmail`), ADD UNIQUE KEY `Key_UNIQUE` (`UserKey`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
 ADD PRIMARY KEY (`IdUser`,`IdGroup`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
 ADD PRIMARY KEY (`IdUserLog`);

--
-- Indexes for table `usernotification`
--
ALTER TABLE `usernotification`
 ADD PRIMARY KEY (`IdUserNotification`);

--
-- Indexes for table `usertracking`
--
ALTER TABLE `usertracking`
 ADD PRIMARY KEY (`IdUserLog`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app`
--
ALTER TABLE `app`
MODIFY `IdApp` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `appmenu`
--
ALTER TABLE `appmenu`
MODIFY `IdAppMenu` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `chatmessage`
--
ALTER TABLE `chatmessage`
MODIFY `IdChatMessage` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
MODIFY `IdFile` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `filetype`
--
ALTER TABLE `filetype`
MODIFY `IdFileType` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
MODIFY `IdGroup` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `notificationtype`
--
ALTER TABLE `notificationtype`
MODIFY `IdNotificationType` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
MODIFY `IdTask` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `IdUser` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
MODIFY `IdUserLog` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usernotification`
--
ALTER TABLE `usernotification`
MODIFY `IdUserNotification` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
