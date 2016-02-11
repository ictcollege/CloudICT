-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2016 at 10:26 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `change_file_folder`(IN `idfolder` INT UNSIGNED, IN `idfile` INT UNSIGNED, IN `iduser` INT UNSIGNED)
    NO SQL
BEGIN
DECLARE path VARCHAR(511);
SET path=CONCAT(
    (SELECT folders.FolderPath FROM folders WHERE folders.IdFolder = idfolder),
    '/',
    (SELECT file.FileName FROM file WHERE file.IdFile = idfile)
    );
UPDATE file SET file.IdFolder = idfolder,file.FilePath
=path WHERE file.IdFile = idfile;
UPDATE shares SET shares.IdFolder = idfolder,shares.FullPath = path WHERE shares.IdFile = idfile;
END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_direct_share_file`(IN `idowner` INT UNSIGNED, IN `idfile` INT UNSIGNED, IN `privilege` INT)
    NO SQL
BEGIN
DECLARE path VARCHAR(511) DEFAULT NULL;
DECLARE name VARCHAR(255);

SELECT file.FilePath,file.FileName INTO path,name FROM file WHERE file.IdFile = idfile;


INSERT INTO shares (
    shares.IdOwner,
    shares.IdFile,
    shares.Name,
    shares.ShareCreated,
    shares.FullPath,
    shares.SharePrivilege,
    shares.SharedByLink
    ) 
VALUES (
    idowner,
    idfile,
    name,
    UNIX_TIMESTAMP(),
    path,
    privilege,
    1
    );
	SELECT path AS path;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_direct_share_folder`(IN `idowner` INT UNSIGNED, IN `idfolder` INT UNSIGNED, IN `privilege` INT)
    NO SQL
BEGIN
DECLARE path VARCHAR(511) DEFAULT NULL;
DECLARE name VARCHAR(100);
SELECT folders.FolderPath,folders.FolderName INTO path,name FROM folders WHERE folders.IdFolder = idfolder;


INSERT INTO shares (
    shares.IdOwner,
    shares.IdFolder,
    shares.Name,
    shares.ShareCreated,
    shares.FullPath,
    shares.SharePrivilege,
    shares.SharedByLink
    ) 
VALUES (
    idowner,
    idfolder,
    name,
    UNIX_TIMESTAMP(),
    path,
    privilege,
    1
    );
	SELECT path AS path;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `scheduled_notification_deletion`()
BEGIN 

	DELETE FROM `usernotification`
	WHERE `usernotification`.`UserNotificationTimeExpires` < UNIX_TIMESTAMP();
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `share_new_folder`(IN `idowner` INT UNSIGNED, IN `idshared` INT UNSIGNED, IN `idfolder` INT UNSIGNED, IN `privilege` TINYINT)
    NO SQL
BEGIN
-- share that folder
INSERT INTO shares (shares.IdOwner,shares.IdShared,shares.IdFolder,shares.ShareCreated,shares.Name,shares.FullPath,shares.SharePrivilege) 
SELECT DISTINCT idowner,idshared,idfolder,UNIX_TIMESTAMP(),folders.FolderName,folders.FolderPath,privilege FROM folders WHERE folders.IdFolder = idfolder;
-- share child folders
INSERT INTO shares 
(shares.IdOwner,shares.IdShared,shares.IdFolder,shares.ShareCreated,shares.Name,shares.FullPath,shares.SharePrivilege) 
SELECT DISTINCT
idowner,idshared,folders.IdFolder,UNIX_TIMESTAMP(),folders.FolderName,folders.FolderPath,privilege FROM folders WHERE folders.IdParent = idfolder;
-- share child files

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `test5`(IN `idowner` INT UNSIGNED, IN `idshared` INT UNSIGNED, IN `idfolder` INT UNSIGNED, IN `privilege` TINYINT)
BEGIN
  DECLARE done BOOLEAN DEFAULT FALSE;
  DECLARE _id BIGINT UNSIGNED;
  DECLARE cur CURSOR FOR SELECT folders.IdFolder FROM folders WHERE folders.IdParent = idfolder;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done := TRUE;
  CALL test1(idowner,idshared,idfolder,privilege);
  OPEN cur;

  testLoop: LOOP
    FETCH cur INTO _id;
    IF done THEN
      LEAVE testLoop;
    END IF;
    CALL test1(idowner,idshared,_id,privilege);
  END LOOP testLoop;

  CLOSE cur;
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
(2, 'Files', 'files/', 'fa-folder-open', 0, 2, '#19eb60'),
(3, 'Users', 'admin/users/', 'fa-users', 2, 3, '#1f6eb6'),
(4, 'System', 'admin/applications', 'fa-cog', 1, 4, '#ff0035');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

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
(9, 4, 'Applications', 'admin/applications', 'fa-puzzle-piece', 1);

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
  `IdFolder` int(10) unsigned DEFAULT NULL,
  `FileType` varchar(50) NOT NULL,
  `FileExtension` varchar(50) DEFAULT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(300) NOT NULL,
  `FileSize` bigint(19) NOT NULL,
  `FileCreated` int(10) unsigned NOT NULL,
  `FileLastModified` int(10) unsigned NOT NULL,
  `Favourites` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`IdFile`, `IdUser`, `IdFolder`, `FileType`, `FileExtension`, `FileName`, `FilePath`, `FileSize`, `FileCreated`, `FileLastModified`, `Favourites`) VALUES
(7, 1, 2, 'image/jpeg', 'jpg', '1460982_876936099071959_6141047125330035193_n.jpg', 'C:/xampp/htdocs/CloudICT/data/1/slike/1460982_876936099071959_6141047125330035193_n.jpg', 86979, 1454958787, 1454958787, 0);

--
-- Triggers `file`
--
DELIMITER $$
CREATE TRIGGER `after_delete_updateUserDiskUsed` AFTER DELETE ON `file`
 FOR EACH ROW BEGIN
UPDATE user SET user.UserDiskUsed = user.UserDiskUsed - OLD.FileSize WHERE user.IdUser = OLD.IdUser;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_update_UserDiskUsed` AFTER INSERT ON `file`
 FOR EACH ROW BEGIN
UPDATE user SET user.UserDiskUsed = user.UserDiskUsed + NEW.FileSize WHERE user.IdUser = NEW.IdUser;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_update_UserDiskUsed` AFTER UPDATE ON `file`
 FOR EACH ROW BEGIN 
UPDATE user SET user.UserDiskUsed = user.UserDiskUsed + (NEW.FileSize - OLD.FileSize) WHERE user.IdUser = NEW.IdUser;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE IF NOT EXISTS `folders` (
  `IdFolder` int(10) unsigned NOT NULL,
  `IdUser` int(10) unsigned NOT NULL,
  `FolderName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `FolderMask` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FolderPath` varchar(511) COLLATE utf8_unicode_ci NOT NULL,
  `Favourites` tinyint(4) NOT NULL,
  `IdParent` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`IdFolder`, `IdUser`, `FolderName`, `FolderMask`, `FolderPath`, `Favourites`, `IdParent`) VALUES
(2, 1, 'slike', '', 'c:/xampp/htdocs/cloudict/data/1/slike', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `IdGroup` int(10) unsigned NOT NULL,
  `GroupName` varchar(16) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

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
(13, 2),
(13, 3),
(16, 2),
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
-- Table structure for table `shares`
--

CREATE TABLE IF NOT EXISTS `shares` (
  `IdShare` int(10) unsigned NOT NULL,
  `IdOwner` int(10) unsigned NOT NULL,
  `IdShared` int(10) unsigned NOT NULL,
  `IdFile` int(10) unsigned DEFAULT NULL,
  `IdFolder` int(10) unsigned DEFAULT NULL,
  `ShareCreated` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `FullPath` varchar(300) NOT NULL,
  `SharePrivilege` tinyint(4) NOT NULL,
  `SharedByLink` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
  `UserDiskUsed` bigint(19) NOT NULL DEFAULT '0',
  `UserStatus` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'zatrebace za nesto (ban korisnika, disable...)',
  `UserKey` char(32) NOT NULL COMMENT 'kljuc koj se salje na mail korisniku koj je pozvan u cloud sistem',
  `UserKeyExpires` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`IdUser`, `IdRole`, `UserName`, `UserPassword`, `UserFullname`, `UserEmail`, `UserDiskQuota`, `UserDiskUsed`, `UserStatus`, `UserKey`, `UserKeyExpires`) VALUES
(1, 3, 'admin', 'c93ccd78b2076528346216b3b2f701e6', 'Administrator', 'admin@mail.com', 5000000000, 86979, 1, '', 0),
(2, 1, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'User', 'user@mail.com', 5, 0, 1, '3cf0dcb8a797454b47e2530125c28b8d', 0),
(3, 2, 'gradmin', '33d5b8e4321e95a22ef87a9c99eaf61f', 'Group Administrator', 'gradmin@mail.com', 5, 0, 1, 'c4f1b457f07f002da137de4eeb9afdfe', 0),
(32, 1, 'darko', 'bb3f057705b4feb9c87eca70a9f209ab', 'user32', 'darko.lesendric@gmail.com', 50000000, 0, 1, 'dfb1986d889bc63ab92d8b1979eaa1c9', 0),
(33, 3, 'test1', '16d7a4fca7442dda3ad93c9a726597e4', 'user33', 'test@test1234.com', 5, 0, 1, '03f9351f0be95c5ede401901829935fc', 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

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
(56, 32, 1, 2, 1, 'Administrator', 'Shared new file with you!', 1454929473, 1457521473, 1);

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
  ADD PRIMARY KEY (`IdFile`), ADD KEY `fk_File_User1_idx` (`IdUser`), ADD KEY `fk_file_folder` (`IdFolder`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`IdFolder`), ADD KEY `fk_folder` (`IdParent`);

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
-- Indexes for table `shares`
--
ALTER TABLE `shares`
  ADD PRIMARY KEY (`IdShare`), ADD KEY `fk_share_file` (`IdFile`), ADD KEY `fk_share_user` (`IdOwner`), ADD KEY `fk_share_folder` (`IdFolder`);

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
  MODIFY `IdAppMenu` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `chatmessage`
--
ALTER TABLE `chatmessage`
  MODIFY `IdChatMessage` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `IdFile` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `IdFolder` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `IdGroup` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `notificationtype`
--
ALTER TABLE `notificationtype`
  MODIFY `IdNotificationType` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `shares`
--
ALTER TABLE `shares`
  MODIFY `IdShare` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `IdTask` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `IdUser` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `IdUserLog` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usernotification`
--
ALTER TABLE `usernotification`
  MODIFY `IdUserNotification` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `file`
--
ALTER TABLE `file`
ADD CONSTRAINT `fk_file_folder` FOREIGN KEY (`IdFolder`) REFERENCES `folders` (`IdFolder`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
ADD CONSTRAINT `fk_folder` FOREIGN KEY (`IdParent`) REFERENCES `folders` (`IdFolder`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shares`
--
ALTER TABLE `shares`
ADD CONSTRAINT `fk_share_file` FOREIGN KEY (`IdFile`) REFERENCES `file` (`IdFile`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_share_folder` FOREIGN KEY (`IdFolder`) REFERENCES `folders` (`IdFolder`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
