-- -----------------------------------------------------
-- Database ictcloud
-- -----------------------------------------------------
DROP DATABASE IF EXISTS ictcloud;
CREATE DATABASE ictcloud DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT ALL ON ictcloud.* TO ict IDENTIFIED BY 'cloud';
USE ictcloud;

-- -----------------------------------------------------
-- Table `User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `User` (
  `IdUser` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IdRole` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `UserName` VARCHAR(16) UNIQUE NOT NULL,
  `PassWord` CHAR(32) NOT NULL,
  `FullName` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(255) NOT NULL DEFAULT '',
  `DiskQuota` BIGINT(19) UNSIGNED NOT NULL,
  `DiskUsed` BIGINT(19) UNSIGNED ZEROFILL NOT NULL,
  `Status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`IdUser`)
) ENGINE = InnoDB;

INSERT INTO `User`(`IdUser`,`UserName`,`Password`) VALUES
(1,'admin','21232f297a57a5a743894a0e4a801fc3');

-- -----------------------------------------------------
-- Table `Group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Group` (
  `IdGroup` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `GroupName` VARCHAR(16) UNIQUE NOT NULL,
  PRIMARY KEY (`IdGroup`)
) ENGINE = InnoDB;

INSERT INTO `Group`(`IdGroup`,`GroupName`) VALUES
(1,'admin');

-- -----------------------------------------------------
-- Table `UserGroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UserGroup` (
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `IdGroup` INT(10) UNSIGNED NOT NULL,
  `StatusAdmin` TINYINT(1) UNSIGNED ZEROFILL NOT NULL,
  PRIMARY KEY (`IdUser`, `IdGroup`),
  INDEX `fk_UserGroup_Group1_idx` (`IdGroup` ASC),
  CONSTRAINT `fk_UserGroup_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserGroup_Group1`
    FOREIGN KEY (`IdGroup`)
    REFERENCES `Group` (`IdGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

INSERT INTO `UserGroup`(`IdUser`,`IdGroup`) VALUES
(1,1);

-- -----------------------------------------------------
-- Table `FileType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FileType` (
  `IdFileType` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MimeType` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`IdFileType`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `File`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `File` (
  `IdFile` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `IdFileType` INT(10) UNSIGNED NOT NULL,
  `IdFolder` INT(10) UNSIGNED NULL,
  `FileName` VARCHAR(255) NOT NULL,
  `FileSize` INT(10) UNSIGNED NOT NULL,
  `LastAccessed` INT(10) UNSIGNED NOT NULL,
  `LastModified` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`IdFile`),
  INDEX `fk_File_User1_idx` (`IdUser` ASC),
  INDEX `fk_File_FileType1_idx` (`IdFileType` ASC),
  INDEX `fk_File_File1_idx` (`IdFolder` ASC),
  CONSTRAINT `fk_File_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_File_FileType1`
    FOREIGN KEY (`IdFileType`)
    REFERENCES `FileType` (`IdFileType`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_File_File1`
    FOREIGN KEY (`IdFolder`)
    REFERENCES `File` (`IdFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Share`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Share` (
  `IdFile` INT(10) UNSIGNED NOT NULL,
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `FullName` VARCHAR(255) NOT NULL,
  `Privilege` TINYINT(4) UNSIGNED NULL,
  PRIMARY KEY (`IdFile`, `IdUser`),
  INDEX `fk_File_has_User_User1_idx` (`IdUser` ASC),
  INDEX `fk_File_has_User_File1_idx` (`IdFile` ASC),
  CONSTRAINT `fk_File_has_User_File1`
    FOREIGN KEY (`IdFile`)
    REFERENCES `File` (`IdFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_File_has_User_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ChatMessage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ChatMessage` (
  `IdChatMessage` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IdSender` INT(10) UNSIGNED NOT NULL,
  `IdReceiver` INT(10) UNSIGNED NOT NULL,
  `TextMessage` TEXT NOT NULL,
  `Time` INT(10) UNSIGNED NOT NULL,
  `SenderName` VARCHAR(100) NOT NULL,
  `ReceiverName` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`IdChatMessage`),
  INDEX `fk_ChatMessage_User1_idx` (`IdSender` ASC),
  INDEX `fk_ChatMessage_User2_idx` (`IdReceiver` ASC),
  CONSTRAINT `fk_ChatMessage_User1`
    FOREIGN KEY (`IdSender`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ChatMessage_User2`
    FOREIGN KEY (`IdReceiver`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Task`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Task` (
  `IdTask` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `TaskName` VARCHAR(255) NOT NULL,
  `Description` TEXT NULL,
  `TimeCreated` INT(10) UNSIGNED NOT NULL,
  `TimeToExecute` INT(10) UNSIGNED NOT NULL,
  `ExecuteType` VARCHAR(20) NULL,
  PRIMARY KEY (`IdTask`),
  INDEX `fk_Task_User1_idx` (`IdUser` ASC),
  CONSTRAINT `fk_Task_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TaskUser`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `TaskUser` (
  `IdTask` INT(10) UNSIGNED NOT NULL,
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `TimeExecuted` INT(10) NULL,
  PRIMARY KEY (`IdTask`, `IdUser`),
  INDEX `fk_TaskUser_User1_idx` (`IdUser` ASC),
  CONSTRAINT `fk_TaskUser_Task1`
    FOREIGN KEY (`IdTask`)
    REFERENCES `Task` (`IdTask`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TaskUser_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Contact`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Contact` (
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `IdContactUser` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`IdUser`, `IdContactUser`),
  INDEX `fk_Contact_User2_idx` (`IdContactUser` ASC),
  CONSTRAINT `fk_Contact_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contact_User2`
    FOREIGN KEY (`IdContactUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NotificationType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `NotificationType` (
  `IdNotificationType` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`IdNotificationType`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `UserNotification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UserNotification` (
  `IdUserNotification` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `IdNotificationType` INT(10) UNSIGNED NOT NULL,
  `Description` VARCHAR(255) NULL,
  `TimeExpires` INT(10) UNSIGNED NULL,
  INDEX `fk_UserNotification_User1_idx` (`IdUser` ASC),
  INDEX `fk_UserNotification_NotificationType1_idx` (`IdNotificationType` ASC),
  PRIMARY KEY (`IdUserNotification`),
  CONSTRAINT `fk_UserNotification_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserNotification_NotificationType1`
    FOREIGN KEY (`IdNotificationType`)
    REFERENCES `NotificationType` (`IdNotificationType`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `UserLog`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UserLog` (
  `IdUserLog` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IdUser` INT(10) UNSIGNED NOT NULL,
  `LoggedIn` INT(10) NOT NULL,
  `LoggedOut` INT(10) NULL,
  PRIMARY KEY (`IdUserLog`),
  INDEX `fk_UserLog_User1_idx` (`IdUser` ASC),
  CONSTRAINT `fk_UserLog_User1`
    FOREIGN KEY (`IdUser`)
    REFERENCES `User` (`IdUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `UserTracking`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UserTracking` (
  `IdUserLog` INT(10) UNSIGNED NOT NULL,
  `EventDetails` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`IdUserLog`),
  CONSTRAINT `fk_UserTracking_UserLog1`
    FOREIGN KEY (`IdUserLog`)
    REFERENCES `UserLog` (`IdUserLog`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DatabaseConf`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DatabaseConf` (
  `DbKey` VARCHAR(255) NOT NULL,
  `DbValue` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`DbKey`, `DbValue`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CloudConf`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `CloudConf` (
  `CloudKey` VARCHAR(255) NOT NULL,
  `CloudValue` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`CloudKey`, `CloudValue`)
) ENGINE = InnoDB;

INSERT INTO `CloudConf` VALUES
('UserDiskQuota','5368709120');

-- -----------------------------------------------------
-- Triggers
-- -----------------------------------------------------
DELIMITER $$

CREATE TRIGGER `User_Before_Insert` BEFORE INSERT ON `User` FOR EACH ROW
BEGIN
    IF NEW.FullName = '' THEN
        SET NEW.FullName = NEW.UserName;
    END IF;
    IF NEW.DiskQuota = 0 THEN
        SET NEW.DiskQuota = CAST((SELECT CloudValue FROM CloudConf WHERE CloudKey = 'UserDiskQuota') AS UNSIGNED);
    END IF;
END
$$

CREATE TRIGGER `UserLog_Before_Insert` BEFORE INSERT ON `UserLog` FOR EACH ROW
BEGIN
    IF NEW.LoggedIn = 0 THEN
        SET NEW.LoggedIn = UNIX_TIMESTAMP();
    END IF;
END
$$

CREATE TRIGGER `UserLog_Before_Update` BEFORE UPDATE ON `UserLog` FOR EACH ROW
BEGIN
    IF NEW.LoggedOut = 1 THEN
        SET NEW.LoggedOut = UNIX_TIMESTAMP();
    END IF;
END
$$

DELIMITER ;
