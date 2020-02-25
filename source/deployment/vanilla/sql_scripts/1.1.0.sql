SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


# Add legacy_id index to accounts table
ALTER TABLE `accounts` 
ADD INDEX `legacy_id` (`legacy_id` ASC) ;


# Update merchant_code and service_code in programs table
ALTER TABLE `programs` 
CHANGE COLUMN `merchant_code` `merchant_code` VARCHAR(45) NULL DEFAULT NULL  , 
CHANGE COLUMN `service_code` `service_code` VARCHAR(45) NULL DEFAULT NULL  ;


# Add month_calc and static_expiration to the license_types table
ALTER TABLE `license_types` 
ADD COLUMN `month_calc` TINYINT(1) NOT NULL DEFAULT 1  AFTER `renew_after` , 
ADD COLUMN `static_expiration` DATE NULL DEFAULT NULL  AFTER `month_calc` ;


# Update the expire_date in the applications table
ALTER TABLE `applications` 
CHANGE COLUMN `expire_date` `expire_date` DATETIME NULL DEFAULT NULL  ;


# Add license_id index to the firm_licenses table
ALTER TABLE `firm_licenses` 
ADD INDEX `fk_firm_licenses_licenses1_idx` (`license_id` ASC) ;


# Update the account_id in the notes table
ALTER TABLE `notes` 
CHANGE COLUMN `account_id` `account_id` INT(10) NULL DEFAULT NULL  ;


# Update parent_key, foreign_obj and foreign_key in the uploads table.
ALTER TABLE `uploads` 
CHANGE COLUMN `parent_key` `parent_key` INT(10) NULL DEFAULT NULL COMMENT 'An ID to group all related documents together (supporting documents).'  , 
CHANGE COLUMN `foreign_obj` `foreign_obj` VARCHAR(45) NULL DEFAULT NULL  , 
CHANGE COLUMN `foreign_key` `foreign_key` INT(10) NULL DEFAULT NULL  ;


# Add account_id index to the managers table
ALTER TABLE `managers` 
ADD INDEX `fk_managers_accounts1_idx` (`account_id` ASC) ;


# Add application_id index to the question_answers table
ALTER TABLE `question_answers` 
ADD INDEX `fk_question_answers_applications1_idx` (`application_id` ASC) ;


# Update degree in the degrees table
ALTER TABLE `degrees` 
CHANGE COLUMN `degree` `degree` VARCHAR(100) NOT NULL  ;


# Update the rate in the fee_modifiers table
ALTER TABLE `fee_modifiers` 
CHANGE COLUMN `rate` `rate` FLOAT(11) NULL DEFAULT 0.00  ;


# Add training_provider_id index to the instructor_assignments table
ALTER TABLE `instructor_assignments` 
ADD INDEX `fk_instructors_training_providers_training_providers1_idx` (`training_provider_id` ASC) ;


# Add audit_id in the audit_deltas table
ALTER TABLE `audit_deltas` 
ADD INDEX `fk_audits_idx` (`audit_id` ASC) ;


# Update the insurance_amount in the insurance_information table
ALTER TABLE `insurance_informations` 
CHANGE COLUMN `insurance_amount` `insurance_amount` DECIMAL(10,2) NULL DEFAULT '0'  ;


-- -----------------------------------------------------
-- View `billing_items_report`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `billing_items_report`;
CREATE  OR REPLACE VIEW `billing_items_report` AS 
SELECT `Application`.`id` AS `id`,`LicenseType`.`id` AS `license_type_id`,`LicenseType`.`label` AS `label`,`Application`.`paid_date` AS `paid_date`,`License`.`issued_date` AS `issued_date`,`Application`.`initial` AS `initial` 
FROM ((`applications` `Application` LEFT JOIN `licenses` `License` ON ((`Application`.`license_id` = `License`.`id`))) LEFT JOIN `license_types` `LicenseType` ON ((`License`.`license_type_id` = `LicenseType`.`id`))) WHERE (`Application`.`paid_date` IS NOT NULL) ORDER BY `Application`.`paid_date`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
