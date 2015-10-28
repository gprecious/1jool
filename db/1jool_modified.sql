-- MySQL Script generated by MySQL Workbench
-- Fri Sep  4 16:08:00 2015
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema bigtrue
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema bigtrue
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bigtrue` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `bigtrue` ;

-- -----------------------------------------------------
-- Table `bigtrue`.`yh_member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_member` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_member` (
  `no` INT NOT NULL ,
  `mb_id` VARCHAR(32) NULL ,
  `mb_pw` VARCHAR(32) NULL ,
  `mb_name` VARCHAR(32) NULL ,
  `mb_block` INT NULL DEFAULT 0 ,
  `mb_group` VARCHAR(255) NULL ,
  `mb_loginTime` DATETIME NULL ,
  `mb_joinTime` DATETIME NULL ,
  `mb_quiz_question` VARCHAR(32) NULL ,
  `mb_quiz_answer` VARCHAR(32) NULL ,
  `mb_address` VARCHAR(255) NULL ,
  `mb_phoneNumber` VARCHAR(32) NULL ,
  `mb_telNumber` VARCHAR(32) NULL ,
  `mb_email` VARCHAR(45) NULL ,
  `mb_emailAccept` INT NULL ,
  PRIMARY KEY (`no`)  )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_group` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_group` (
  `no` INT NOT NULL ,
  `gp_id` VARCHAR(32) NULL ,
  `gp_name` VARCHAR(32) NULL ,
  PRIMARY KEY (`no`)  )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_quiz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_quiz` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_quiz` (
  `no` INT NOT NULL ,
  `quiz_content` VARCHAR(45) NULL ,
  `yh_quizcol` VARCHAR(45) NULL ,
  PRIMARY KEY (`no`)  )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_product` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_product` (
  `pd_no` INT NOT NULL ,
  `pd_name` VARCHAR(45) NULL ,
  `pd_shortDesc` VARCHAR(255) NULL ,
  `pd_code` VARCHAR(45) NULL ,
  `pd_price` INT NULL ,
  `pd_viewCount` INT NULL ,
  `pd_regDate` DATETIME NULL ,
  `pd_thumbnail` VARCHAR(255) NULL ,
  `pd_isProduct` INT NULL ,
  PRIMARY KEY (`pd_no`)  )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_product_sub`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_product_sub` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_product_sub` (
  `no` INT NOT NULL ,
  `pd_no` INT NULL ,
  `pds_contents` VARCHAR(2500) NULL ,
  PRIMARY KEY (`no`)  ,
  INDEX `pd_no_idx` (`pd_no` ASC)  ,
  CONSTRAINT `pd_no`
    FOREIGN KEY (`pd_no`)
    REFERENCES `bigtrue`.`yh_product` (`pd_no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_reply`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_reply` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_reply` (
  `no` INT NOT NULL ,
  `pd_no` INT NULL ,
  `rp_regDate` DATETIME NULL ,
  `rp_content` VARCHAR(255) NULL ,
  `mb_no` INT NULL ,
  PRIMARY KEY (`no`)  ,
  INDEX `pd_no_idx` (`pd_no` ASC)  ,
  INDEX `mb_no_idx` (`mb_no` ASC)  ,
  CONSTRAINT `pd_no`
    FOREIGN KEY (`pd_no`)
    REFERENCES `bigtrue`.`yh_product` (`pd_no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `mb_no`
    FOREIGN KEY (`mb_no`)
    REFERENCES `bigtrue`.`yh_member` (`no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_product_date_option`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_product_date_option` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_product_date_option` (
  `no` INT NOT NULL ,
  `pd_no` INT NULL ,
  `pddo_date` DATETIME NULL ,
  PRIMARY KEY (`no`)  ,
  INDEX `pd_no_idx` (`pd_no` ASC)  ,
  CONSTRAINT `pd_no`
    FOREIGN KEY (`pd_no`)
    REFERENCES `bigtrue`.`yh_product` (`pd_no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_product_option`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_product_option` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_product_option` (
  `no` INT NOT NULL ,
  `pd_no` INT NULL ,
  `pdo_title` VARCHAR(32) NULL ,
  `pdo_content` VARCHAR(45) NULL ,
  `pdo_additionalPrice` INT NULL ,
  PRIMARY KEY (`no`)  ,
  INDEX `pd_no_idx` (`pd_no` ASC)  ,
  CONSTRAINT `pd_no`
    FOREIGN KEY (`pd_no`)
    REFERENCES `bigtrue`.`yh_product` (`pd_no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_wishlist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_wishlist` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_wishlist` (
  `no` INT NOT NULL ,
  `mb_no` INT NULL ,
  `pd_no` INT NULL ,
  PRIMARY KEY (`no`)  ,
  INDEX `mb_no_idx` (`mb_no` ASC)  ,
  INDEX `pd_no_idx` (`pd_no` ASC)  ,
  CONSTRAINT `mb_no`
    FOREIGN KEY (`mb_no`)
    REFERENCES `bigtrue`.`yh_member` (`no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `pd_no`
    FOREIGN KEY (`pd_no`)
    REFERENCES `bigtrue`.`yh_product` (`pd_no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_order` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_order` (
  `no` INT NOT NULL ,
  `mb_no` INT NULL ,
  `pd_no` INT NULL ,
  `ord_regDate` DATETIME NULL ,
  `ord_amount` INT NULL ,
  `ord_totalPrice` INT NULL ,
  PRIMARY KEY (`no`)  ,
  INDEX `mb_no_idx` (`mb_no` ASC)  ,
  INDEX `pd_no_idx` (`pd_no` ASC)  ,
  CONSTRAINT `mb_no`
    FOREIGN KEY (`mb_no`)
    REFERENCES `bigtrue`.`yh_member` (`no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `pd_no`
    FOREIGN KEY (`pd_no`)
    REFERENCES `bigtrue`.`yh_product` (`pd_no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bigtrue`.`yh_product_photo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bigtrue`.`yh_product_photo` ;

CREATE TABLE IF NOT EXISTS `bigtrue`.`yh_product_photo` (
  `no` INT NOT NULL ,
  `pd_no` INT NULL ,
  `pdp_path` VARCHAR(255) NULL ,
  PRIMARY KEY (`no`)  ,
  INDEX `pd_no_idx` (`pd_no` ASC)  ,
  CONSTRAINT `pd_no`
    FOREIGN KEY (`pd_no`)
    REFERENCES `bigtrue`.`yh_product` (`pd_no`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
