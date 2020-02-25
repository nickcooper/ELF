# put 1.3 table structure changes here

-- -----------------------------------------------------
-- Allow work_experiences start_date to be null for legacy imports
-- -----------------------------------------------------

ALTER TABLE `work_experiences` 
CHANGE COLUMN `start_date` `start_date` DATE NULL DEFAULT NULL  ;
 

-- --------------------------------------------------------------------------------------
-- Adding a description column to the elements table for agency-specific tailoring
-- Trello card : Sprint 16, Fri 1 (8)
-- --------------------------------------------------------------------------------------

ALTER TABLE `elements` ADD COLUMN `description` VARCHAR(500) NULL DEFAULT NULL  AFTER `label` ;


-- --------------------------------------------------------------------------------------
-- Allowing NULL values for addresses while importing WorkExperience
-- Trello card : Sprint 16, Tues 1 (5)
-- --------------------------------------------------------------------------------------

ALTER TABLE `addresses` 
CHANGE COLUMN `addr1` `addr1` VARCHAR(255) NULL DEFAULT NULL  , 
CHANGE COLUMN `city` `city` VARCHAR(255) NULL DEFAULT NULL  , 
CHANGE COLUMN `state` `state` VARCHAR(2) NULL DEFAULT NULL  , 
CHANGE COLUMN `postal` `postal` INT(9) NULL DEFAULT NULL  ;


-- --------------------------------------------------------------------------------------
-- Add Credit Card as a payment type
-- --------------------------------------------------------------------------------------
INSERT INTO `payment_types` (`label`) VALUES ('Credit Card');

