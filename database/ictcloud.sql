-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2015 at 07:06 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

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
  `IdApp` int(10) unsigned NOT NULL,
  `AppName` varchar(255) NOT NULL,
  `AppLink` varchar(255) NOT NULL,
  `AppIcon` varchar(255) NOT NULL,
  `AppStatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1,2\n0: nije admin\n1: admin grupe\n2: admin',
  `AppOrder` mediumint(3) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app`
--

INSERT INTO `app` (`IdApp`, `AppName`, `AppLink`, `AppIcon`, `AppStatus`, `AppOrder`) VALUES
(1, 'Tasks', 'tasks/', 'image/tasks.png', 0, 1),
(2, 'Files', 'files/', 'image/files.png', 0, 2),
(3, 'Admin Panel', 'adminl/', 'image/adminPanel.png', 2, 3),
(4, 'Group Panel', 'grups/', 'image/Group.png', 1, 4);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appmenu`
--

INSERT INTO `appmenu` (`IdAppMenu`, `IdApp`, `AppMenuName`, `AppMenuLink`, `AppMenuIcon`, `AppMenuOrder`) VALUES
(1, 1, 'taskMenu1', 'taskMenu1', 'taskMenu1.png', 1),
(2, 1, 'taskMenu2', 'taskMenu2', 'taskMenu2.png', 2),
(3, 1, 'taskMenu3', 'taskMenu3', 'taskMenu2.png', 3),
(4, 2, 'fileMenu1', 'fileMenu1', 'fileMenu1.png', 1),
(5, 2, 'fileMenu2', 'fileMenu2', 'fileMenu2.png', 2),
(6, 3, 'adminMenu1', 'adminMenu1', 'adminMenu1.png', 1),
(7, 3, 'adminMenu2', 'adminMenu2', 'adminMenu1.png', 2),
(8, 4, 'GroupMenu1', 'GroupMenu1', 'GroupMenu1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chatmessage`
--

CREATE TABLE IF NOT EXISTS `chatmessage` (
  `IdChatMessage` int(10) unsigned NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `filetype`
--

CREATE TABLE IF NOT EXISTS `filetype` (
  `IdFileType` int(10) unsigned NOT NULL,
  `FileTypeMime` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

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
  `IdGroup` int(10) unsigned NOT NULL,
  `GroupName` varchar(16) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`IdGroup`, `GroupName`) VALUES
(1, 'admins'),
(3, 'grupa1'),
(4, 'grupa2'),
(2, 'users');

-- --------------------------------------------------------

--
-- Table structure for table `notificationtype`
--

CREATE TABLE IF NOT EXISTS `notificationtype` (
  `IdNotificationType` int(10) unsigned NOT NULL,
  `NotificationTypeName` varchar(255) NOT NULL
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
  `SharePrivilege` tinyint(4) unsigned DEFAULT NULL
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

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
  `UserGroupStatusAdmin` tinyint(1) unsigned zerofill NOT NULL
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
  `UserNotificationTimeExpires` int(10) unsigned DEFAULT NULL COMMENT 'se popunjava kada korisnik prvi put vidi notifikaciju'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  MODIFY `IdAppMenu` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `chatmessage`
--
ALTER TABLE `chatmessage`
  MODIFY `IdChatMessage` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `IdFile` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=143;
--
-- AUTO_INCREMENT for table `filetype`
--
ALTER TABLE `filetype`
  MODIFY `IdFileType` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `IdGroup` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `IdTask` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `IdUser` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `IdUserLog` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usernotification`
--
ALTER TABLE `usernotification`
  MODIFY `IdUserNotification` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
