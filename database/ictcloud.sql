-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2015 at 09:59 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


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
  PRIMARY KEY (`IdApp`),
  UNIQUE KEY `IdApp_UNIQUE` (`IdApp`),
  UNIQUE KEY `AppName_UNIQUE` (`AppName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `app`
--

INSERT INTO `app` (`IdApp`, `AppName`, `AppLink`, `AppIcon`, `AppStatus`, `AppOrder`) VALUES
(1, 'Tasks', 'tasks/', 'fa-tasks', 0, 2),
(2, 'Files', 'files/', 'fa-folder-open', 0, 1),
(3, 'Admin Panel', 'admin/users', 'fa-cog', 2, 3),
(4, 'Group Panel', 'grups/', 'fa-users', 1, 4);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `appmenu`
--

INSERT INTO `appmenu` (`IdAppMenu`, `IdApp`, `AppMenuName`, `AppMenuLink`, `AppMenuIcon`, `AppMenuOrder`) VALUES
(1, 2, 'All Files', 'files', 'fa-files-o', 1),
(2, 2, 'Favourites', '', 'fa-star-o', 2),
(3, 2, 'Shared With You', 'taskMenu3', 'fa-share-alt', 3),
(4, 2, 'Shared With Others', 'fileMenu1', 'fa-share', 4),
(5, 2, 'Shared By Link', 'fileMenu2', 'fa-link', 5),
(6, 3, 'Users', 'admin/users', 'fa-user', 1),
(7, 3, 'Groups', 'admin/groups', 'fa-users', 2);

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

-- --------------------------------------------------------

--
-- Table structure for table `dbconf`
--

CREATE TABLE IF NOT EXISTS `dbconf` (
  `DbConfKey` varchar(255) NOT NULL,
  `DbConfValue` varchar(255) NOT NULL,
  PRIMARY KEY (`DbConfKey`,`DbConfValue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `IdFile` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdUser` int(10) unsigned NOT NULL,
  `IdFileType` int(10) unsigned NOT NULL,
  `IdFolder` int(10) unsigned DEFAULT NULL,
  `FileExtension` varchar(50) DEFAULT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(300) NOT NULL,
  `FileSize` int(10) unsigned NOT NULL,
  `FileCreated` int(10) unsigned NOT NULL,
  `FileLastModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`IdFile`),
  KEY `fk_File_User1_idx` (`IdUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `filetype`
--

CREATE TABLE IF NOT EXISTS `filetype` (
  `IdFileType` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FileTypeMime` varchar(255) NOT NULL,
  PRIMARY KEY (`IdFileType`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

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
(11, 'image/gif');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `IdGroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(16) NOT NULL,
  PRIMARY KEY (`IdGroup`),
  UNIQUE KEY `GroupName` (`GroupName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`IdGroup`, `GroupName`) VALUES
(1, 'admins'),
(3, 'grupa1'),
(4, 'grupa2'),
(5, 'test1'),
(6, 'test2'),
(7, 'test3'),
(2, 'users');

-- --------------------------------------------------------

--
-- Table structure for table `notificationtype`
--

CREATE TABLE IF NOT EXISTS `notificationtype` (
  `IdNotificationType` int(10) unsigned NOT NULL,
  `NotificationTypeName` varchar(255) NOT NULL,
  PRIMARY KEY (`IdNotificationType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `SharePrivilege` tinyint(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`IdFile`,`IdUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `share`
--

INSERT INTO `share` (`IdFile`, `IdUser`, `ShareCreated`, `ShareFullName`, `FilePath`, `SharePrivilege`) VALUES
(1, 1, 1440001425, '1/2/3/4', '', 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `UserDiskUsed` bigint(19) unsigned NOT NULL DEFAULT '0',
  `UserStatus` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'zatrebace za nesto (ban korisnika, disable...)',
  `UserKey` char(32) NOT NULL COMMENT 'kljuc koj se salje na mail korisniku koj je pozvan u cloud sistem',
  `UserKeyExpires` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdUser`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `Email_UNIQUE` (`UserEmail`),
  UNIQUE KEY `Key_UNIQUE` (`UserKey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`IdUser`, `IdRole`, `UserName`, `UserPassword`, `UserFullname`, `UserEmail`, `UserDiskQuota`, `UserDiskUsed`, `UserStatus`, `UserKey`, `UserKeyExpires`) VALUES
(1, 3, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin@mail.com', 5, 0, 1, 'bfef2666383dfb285f30a7cfba64b176', 0),
(2, 1, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'User', 'user@mail.com', 5, 0, 1, '3cf0dcb8a797454b47e2530125c28b8d', 0),
(3, 2, 'gradmin', '33d5b8e4321e95a22ef87a9c99eaf61f', 'Group Administrator', 'gradmin@mail.com', 5, 0, 1, 'c4f1b457f07f002da137de4eeb9afdfe', 0),
(11, 1, 'test', 'test', 'test', 'test1@test.com', 5, 4141412, 0, '8eddce31eba00becfeae2ea3e03b4526', 1435157196);

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
(1, 1, 0),
(2, 1, 1),
(2, 4, 1),
(3, 3, 0);

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
  `UserNotificationTimeExpires` int(10) unsigned DEFAULT NULL COMMENT 'se popunjava kada korisnik prvi put vidi notifikaciju',
  PRIMARY KEY (`IdUserNotification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usertracking`
--

CREATE TABLE IF NOT EXISTS `usertracking` (
  `IdUserLog` int(10) unsigned NOT NULL,
  `UserTrackingEventDetails` varchar(255) NOT NULL,
  PRIMARY KEY (`IdUserLog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
