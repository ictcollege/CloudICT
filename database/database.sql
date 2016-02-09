-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2016 at 10:36 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ictcloud`
--

-- --------------------------------------------------------

--
-- Table structure for table `app`
--

CREATE TABLE IF NOT EXISTS `app` (
  `IdApp` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AppName` varchar(255) NOT NULL,
  `AppLink` varchar(255) NOT NULL,
  `AppIcon` varchar(255) NOT NULL,
  `AppStatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1,2\n0: nije admin\n1: admin grupe\n2: admin',
  `AppOrder` mediumint(3) NOT NULL,
  `AppColor` varchar(250) NOT NULL,
  PRIMARY KEY (`IdApp`),
  UNIQUE KEY `IdApp_UNIQUE` (`IdApp`),
  UNIQUE KEY `AppName_UNIQUE` (`AppName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `app`
--

INSERT INTO `app` (`IdApp`, `AppName`, `AppLink`, `AppIcon`, `AppStatus`, `AppOrder`, `AppColor`) VALUES
(2, 'Files', 'files/', 'fa-folder-open', 0, 2, '#19eb60'),
(3, 'Users', 'admin/users/', 'fa-users', 2, 3, '#1f6eb6'),
(4, 'System', 'admin/applications', 'fa-cog', 1, 4, '#ff0035'),
(5, 'Tasks', '/Tasks', 'fa-indent', 0, 0, '#2026ab');

-- --------------------------------------------------------

--
-- Table structure for table `appmenu`
--

CREATE TABLE IF NOT EXISTS `appmenu` (
  `IdAppMenu` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdApp` int(10) unsigned NOT NULL,
  `AppMenuName` varchar(255) NOT NULL,
  `AppMenuLink` varchar(255) NOT NULL,
  `AppMenuIcon` varchar(255) NOT NULL,
  `AppMenuOrder` int(11) DEFAULT NULL,
  PRIMARY KEY (`IdAppMenu`),
  UNIQUE KEY `IdAppMenu` (`IdAppMenu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `appmenu`
--

INSERT INTO `appmenu` (`IdAppMenu`, `IdApp`, `AppMenuName`, `AppMenuLink`, `AppMenuIcon`, `AppMenuOrder`) VALUES
(1, 2, 'All Files', 'files', 'fa-files-o', 1),
(2, 2, 'Favourites', 'files/favourites', 'fa-star-o', 2),
(3, 2, 'Shared With You', 'files/shared_with_you', 'fa-share-alt', 3),
(4, 2, 'Shared With Others', 'files/shared_with_others', 'fa-share', 4),
(5, 2, 'Shared By Link', 'files/shared_by_link', 'fa-link', 5),
(6, 3, 'Users', 'admin/users', 'fa-user', 1),
(7, 3, 'Groups', 'admin/groups', 'fa-users', 2),
(8, 3, 'Privileges', 'admin/privileges', 'fa-wrench', 3),
(9, 4, 'Applications', 'admin/applications', 'fa-puzzle-piece', 1),
(10, 5, 'Tasks', 'Tasks/', 'fa-align-justify', NULL),
(11, 5, 'My Tasks', 'Tasks/assigned', 'fa-indent', NULL),
(12, 5, 'Finished Tasks', 'Task/finished', 'fa-indent', NULL),
(13, 5, 'Create New Task', 'Tasks/create', 'fa-plus', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chatmessage`
--

CREATE TABLE IF NOT EXISTS `chatmessage` (
  `IdChatMessage` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdSender` int(10) unsigned NOT NULL,
  `IdReceiver` int(10) unsigned NOT NULL,
  `ChatMessageText` text NOT NULL,
  `ChatMessageTime` int(10) unsigned NOT NULL,
  `ChatMessageSenderName` varchar(100) NOT NULL,
  `ChatMessageReceiverName` varchar(100) NOT NULL,
  PRIMARY KEY (`IdChatMessage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `chatmessage`
--


-- --------------------------------------------------------

--
-- Table structure for table `cloudconf`
--

CREATE TABLE IF NOT EXISTS `cloudconf` (
  `CloudConfKey` varchar(255) NOT NULL,
  `CloudConfValue` varchar(255) NOT NULL,
  PRIMARY KEY (`CloudConfKey`,`CloudConfValue`)
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
  `IdContactUser` int(10) unsigned NOT NULL,
  PRIMARY KEY (`IdUser`,`IdContactUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `dbconf`
--

CREATE TABLE IF NOT EXISTS `dbconf` (
  `DbConfKey` varchar(255) NOT NULL,
  `DbConfValue` varchar(255) NOT NULL,
  PRIMARY KEY (`DbConfKey`,`DbConfValue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbconf`
--


-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `IdGroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(16) NOT NULL,
  PRIMARY KEY (`IdGroup`),
  UNIQUE KEY `GroupName` (`GroupName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`IdGroup`, `GroupName`) VALUES
(13, 'admins'),
(16, 'Init Users');

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
(3, 4),
(7, 2),
(9, 4),
(1, 4),
(1, 2),
(3, 3),
(8, 3),
(8, 2),
(10, 4),
(11, 2),
(11, 3),
(11, 4),
(14, 3),
(14, 2),
(14, 4),
(15, 2),
(15, 51),
(13, 4),
(13, 3),
(16, 2),
(13, 28),
(13, 2),
(13, 5);

-- --------------------------------------------------------

--
-- Table structure for table `notificationtype`
--

CREATE TABLE IF NOT EXISTS `notificationtype` (
  `IdNotificationType` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NotificationTypeName` varchar(255) NOT NULL,
  `NotificationTypeIcon` varchar(255) NOT NULL,
  PRIMARY KEY (`IdNotificationType`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

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
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `IdTask` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdUser` int(10) unsigned NOT NULL,
  `TaskName` varchar(255) NOT NULL,
  `TaskDescription` text,
  `TaskTimeCreated` int(10) unsigned NOT NULL,
  `TaskTimeToExecute` int(10) unsigned NOT NULL,
  `TaskExecuteType` tinyint(1) NOT NULL COMMENT '0: moraju svi da zavrse zadatak\n1: moze samo jedan da zavrsi zadatak',
  PRIMARY KEY (`IdTask`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`IdTask`, `IdUser`, `TaskName`, `TaskDescription`, `TaskTimeCreated`, `TaskTimeToExecute`, `TaskExecuteType`) VALUES
(1, 1, 'test1', 'cisto da vidim kako rade notifikacije', 1454886000, 1454886000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `taskuser`
--

CREATE TABLE IF NOT EXISTS `taskuser` (
  `IdTask` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `TaskUserFullname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TaskUserAssigned` int(10) unsigned NOT NULL,
  `TaskUserTimeExecuted` int(10) DEFAULT NULL,
  PRIMARY KEY (`IdTask`,`IdUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taskuser`
--

INSERT INTO `taskuser` (`IdTask`, `IdUser`, `TaskUserFullname`, `TaskUserAssigned`, `TaskUserTimeExecuted`) VALUES
(1, 32, 'user32', 1454886000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `IdUser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdRole` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: User\n2: grup admin\n3: admin',
  `UserName` varchar(16) NOT NULL,
  `UserPassword` char(32) NOT NULL,
  `UserFullname` varchar(100) NOT NULL,
  `UserEmail` varchar(255) NOT NULL DEFAULT '',
  `UserDiskQuota` bigint(19) unsigned NOT NULL DEFAULT '0',
  `UserDiskUsed` bigint(19) NOT NULL DEFAULT '0',
  `UserStatus` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'zatrebace za nesto (ban korisnika, disable...)',
  `UserKey` char(32) NOT NULL COMMENT 'kljuc koj se salje na mail korisniku koj je pozvan u cloud sistem',
  `UserKeyExpires` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdUser`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `Email_UNIQUE` (`UserEmail`),
  UNIQUE KEY `Key_UNIQUE` (`UserKey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`IdUser`, `IdRole`, `UserName`, `UserPassword`, `UserFullname`, `UserEmail`, `UserDiskQuota`, `UserDiskUsed`, `UserStatus`, `UserKey`, `UserKeyExpires`) VALUES
(1, 3, 'admin', 'c93ccd78b2076528346216b3b2f701e6', 'Administrator', 'admin@mail.com', 5000000000, 2774948, 1, '', 0),
(2, 1, 'user', '0d8d5cd06832b29560745fe4e1b941cf', 'User', 'user@mail.com', 5000, 0, 0, '1', 1),
(3, 2, 'gradmin', '33d5b8e4321e95a22ef87a9c99eaf61f', 'Group Administrator', 'gradmin@mail.com', 5, 0, 1, 'c4f1b457f07f002da137de4eeb9afdfe', 0),
(32, 1, 'darko', 'bb3f057705b4feb9c87eca70a9f209ab', 'user32', 'darko.lesendric@gmail.com', 50000000, 7463249, 1, 'dfb1986d889bc63ab92d8b1979eaa1c9', 0),
(33, 3, 'test1', '16d7a4fca7442dda3ad93c9a726597e4', 'user33', 'test@test1234.com', 5, 0, 1, '03f9351f0be95c5ede401901829935fc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `IdUser` int(10) unsigned NOT NULL,
  `IdGroup` int(10) unsigned NOT NULL,
  `UserGroupStatusAdmin` tinyint(1) unsigned zerofill NOT NULL,
  PRIMARY KEY (`IdUser`,`IdGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`IdUser`, `IdGroup`, `UserGroupStatusAdmin`) VALUES
(1, 13, 1),
(1, 16, 1),
(2, 16, 0),
(3, 13, 1),
(3, 16, 1),
(11, 16, 0),
(32, 13, 1),
(33, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE IF NOT EXISTS `userlog` (
  `IdUserLog` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdUser` int(10) unsigned NOT NULL,
  `UserLogLoggedIn` int(10) NOT NULL,
  `UserLogLoggedOut` int(10) DEFAULT NULL,
  PRIMARY KEY (`IdUserLog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `userlog`
--


-- --------------------------------------------------------

--
-- Table structure for table `usernotification`
--

CREATE TABLE IF NOT EXISTS `usernotification` (
  `IdUserNotification` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdUser` int(10) unsigned NOT NULL,
  `IdNotificationType` int(10) unsigned NOT NULL COMMENT 'IdNotificationType \n0: information\n1: warning\n2: error',
  `IdApp` int(10) unsigned NOT NULL,
  `IdEvent` int(10) unsigned NOT NULL COMMENT 'IdEvent id reda koji se dodao u bazu(id task, id Share)',
  `UserFullname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `UserNotificationDescription` varchar(255) DEFAULT NULL,
  `UserNotificationCreated` int(10) unsigned NOT NULL,
  `UserNotificationTimeExpires` int(10) unsigned DEFAULT '0' COMMENT 'se popunjava kada korisnik prvi put vidi notifikaciju',
  `UserNotificationStatus` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdUserNotification`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;

--
-- Dumping data for table `usernotification`
--

INSERT INTO `usernotification` (`IdUserNotification`, `IdUser`, `IdNotificationType`, `IdApp`, `IdEvent`, `UserFullname`, `UserNotificationDescription`, `UserNotificationCreated`, `UserNotificationTimeExpires`, `UserNotificationStatus`) VALUES
(1, 66, 10, 4, 2, 'Admin', 'You have been added to new group', 1437129742, 1437149742, 1),
(2, 66, 1, 1, 1, 'Admin', 'Generic Description', 1437129748, 1437149748, 1),
(3, 66, 3, 1, 1, 'Admin', 'Generic Description', 1437129750, 1437149750, 1),
(4, 66, 2, 1, 1, 'Admin', 'Generic Description', 1437129751, 1437149751, 1),
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
(55, 32, 1, 2, 90, 'Administrator', 'Shared new file with you!', 1454927172, 1457519172, 1),
(56, 32, 1, 2, 2, 'Administrator', 'Shared new file with you!', 1454953217, 1457545217, 1),
(57, 2, 1, 2, 3, 'Administrator', 'Shared new file with you!', 1454953217, 0, 0),
(58, 32, 1, 2, 4, 'Administrator', 'Shared folder with child items!', 1454953237, 1457545237, 1),
(59, 32, 1, 2, 6, 'Administrator', 'Shared folder with child items!', 1454953237, 1457545237, 1),
(60, 32, 1, 2, 8, 'Administrator', 'Shared folder with child items!', 1454953237, 1457545237, 1),
(61, 32, 1, 2, 10, 'Administrator', 'Shared folder with child items!', 1454953237, 1457545237, 1),
(62, 32, 1, 2, 12, 'Administrator', 'Shared folder with child items!', 1454953262, 1457545262, 1),
(63, 3, 1, 2, 16, 'Administrator', 'Shared folder with child items!', 1454953262, 0, 0),
(64, 33, 1, 2, 20, 'Administrator', 'Shared folder with child items!', 1454953262, 0, 0),
(65, 2, 1, 2, 24, 'Administrator', 'Shared folder with child items!', 1454953262, 0, 0),
(66, 1, 1, 2, 28, 'user32', 'Shared new file with you!', 1454953430, 1457545430, 1),
(67, 1, 1, 2, 30, 'user32', 'Shared folder with child items!', 1454953544, 1457545544, 1),
(68, 1, 1, 2, 32, 'user32', 'Shared folder with child items!', 1454953544, 1457545544, 1),
(69, 1, 1, 2, 34, 'user32', 'Shared folder with child items!', 1454953544, 1457545544, 1),
(70, 3, 1, 2, 36, 'user32', 'Shared folder with child items!', 1454953544, 0, 0),
(71, 3, 1, 2, 38, 'user32', 'Shared folder with child items!', 1454953544, 0, 0),
(72, 3, 1, 2, 40, 'user32', 'Shared folder with child items!', 1454953544, 0, 0),
(73, 33, 1, 2, 42, 'user32', 'Shared folder with child items!', 1454953544, 0, 0),
(74, 33, 1, 2, 44, 'user32', 'Shared folder with child items!', 1454953544, 0, 0),
(75, 33, 1, 2, 46, 'user32', 'Shared folder with child items!', 1454953544, 0, 0),
(76, 1, 1, 2, 48, 'user32', 'Shared new file with you!', 1454955317, 1457547317, 1),
(77, 1, 1, 2, 49, 'user32', 'Shared new file with you!', 1454955334, 1457547334, 1),
(78, 3, 1, 2, 50, 'user32', 'Shared new file with you!', 1454955334, 0, 0),
(79, 33, 1, 2, 51, 'user32', 'Shared new file with you!', 1454955334, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usertracking`
--

CREATE TABLE IF NOT EXISTS `usertracking` (
  `IdUserLog` int(10) unsigned NOT NULL,
  `UserTrackingEventDetails` varchar(255) NOT NULL,
  PRIMARY KEY (`IdUserLog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usertracking`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
