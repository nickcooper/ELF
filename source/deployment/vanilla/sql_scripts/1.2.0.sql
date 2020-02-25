SET @FOREIGN_KEY_CHECKS=0;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

/* Drops unused column */
ALTER TABLE `payments` DROP COLUMN `slug` ;

/* Updates for Prorating */
ALTER TABLE `payment_items` 
DROP COLUMN `cost` , 
DROP COLUMN `fee_id` , 
CHANGE COLUMN `foreign_plugin` `foreign_plugin` VARCHAR(45) NOT NULL  , 
CHANGE COLUMN `foreign_obj` `foreign_obj` VARCHAR(45) NOT NULL  , 
CHANGE COLUMN `foreign_key` `foreign_key` INT(10) NOT NULL  , 
ADD COLUMN `label` VARCHAR(105) NOT NULL  AFTER `payment_id` , 
ADD COLUMN `fee` DECIMAL(5,2) NOT NULL DEFAULT '0.00'  AFTER `label` , 
ADD COLUMN `fee_type` VARCHAR(45) NOT NULL  AFTER `fee` , 
ADD COLUMN `fee_data` TINYTEXT NOT NULL  AFTER `fee_type` ;

/* Updates for Prorating */
ALTER TABLE `fees` DROP COLUMN `flat_rate` , ADD COLUMN `fee` DECIMAL(5,2) NULL DEFAULT NULL  AFTER `type` ;

/* Updates for Prorating */
ALTER TABLE `shopping_carts` ADD COLUMN `label` VARCHAR(150) NULL DEFAULT NULL  AFTER `fee_id` ;

/* Updates for Prorating */
ALTER TABLE `fee_modifiers` DROP COLUMN `rate` , ADD COLUMN `fee` FLOAT(11) NULL DEFAULT '0'  AFTER `type` ;

/* Restructure output docs */
ALTER TABLE `output_document_batches` 
DROP FOREIGN KEY `fk_output_document_batches_output_document_types1` ;

ALTER TABLE `output_document_batches` 
DROP COLUMN `output_document_type_id` , 
ADD COLUMN `label` VARCHAR(150) NULL DEFAULT NULL  AFTER `id` 
, ADD INDEX `label` (`label` ASC) ;

/* Restructure output docs */
ALTER TABLE `output_document_batch_items` 
DROP FOREIGN KEY `fk_output_document_batch_items_output_document_types1` , 
DROP FOREIGN KEY `fk_output_document_batch_items_output_document_batches1` ;

ALTER TABLE `output_document_batch_items` 
DROP COLUMN `output_document_type_id` , 
ADD COLUMN `output_document_type` VARCHAR(150) NULL DEFAULT NULL  AFTER `id` 
, ADD INDEX `type` (`output_document_type` ASC) ;

/* Update column */
ALTER TABLE `insurance_informations` CHANGE COLUMN `insurance_amount` `insurance_amount` DECIMAL(10,2) NULL DEFAULT '0'  ;

/* Updates for Prorating */
ALTER TABLE `pending_payments` 
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  , 
CHANGE COLUMN `account_id` `account_id` INT(11) NOT NULL  , 
CHANGE COLUMN `payment_type_id` `payment_type_id` INT(11) NOT NULL  , 
CHANGE COLUMN `identifier` `identifier` VARCHAR(150) NOT NULL  , 
CHANGE COLUMN `total` `total` DECIMAL(5,2) NULL DEFAULT NULL  , 
CHANGE COLUMN `transaction_data` `transaction_data` TEXT NULL DEFAULT NULL ;

/* Updates for Prorating */
ALTER TABLE `pending_payment_items` 
CHANGE COLUMN `label` `label` VARCHAR(150) NOT NULL  AFTER `pending_payment_id` , 
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  , 
CHANGE COLUMN `foreign_plugin` `foreign_plugin` VARCHAR(45) NOT NULL  , 
CHANGE COLUMN `foreign_obj` `foreign_obj` VARCHAR(45) NOT NULL  , 
CHANGE COLUMN `foreign_key` `foreign_key` INT(11) NOT NULL  , 
CHANGE COLUMN `pending_payment_id` `pending_payment_id` INT(11) NOT NULL ;

/* Restructure output docs */
DROP TABLE IF EXISTS `output_document_types` ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
