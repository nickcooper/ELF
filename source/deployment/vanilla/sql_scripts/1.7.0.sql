# 1.7.0 Vanilla data updates

# ---------------------------
# Create the Application Type table
# ---------------------------

CREATE TABLE `application_types` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`));

# ---------------------------
# Insert records for the Application Type table
# ---------------------------

INSERT INTO `application_types` (`label`, `created`, `modified`) VALUES ('Initial', NOW(), NOW());
INSERT INTO `application_types` (`label`, `created`, `modified`) VALUES ('Renewal', NOW(), NOW());
INSERT INTO `application_types` (`label`, `created`, `modified`) VALUES ('Conversion', NOW(), NOW());

# ---------------------------
# Create converted license id column to link to the license that was/is being converted
# ---------------------------

ALTER TABLE `applications` 
ADD COLUMN `converted_license_id` INT NULL DEFAULT NULL AFTER `initial`;

# ---------------------------
# Change applications.initial values to reference application_types.id
# ---------------------------

UPDATE `applications` SET `initial` = (SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial') 
WHERE `applications`.`initial` = 1;

UPDATE `applications` SET `initial` = (SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal') 
WHERE `applications`.`initial` = 0;

# ---------------------------
# Change applications.initial to application.application_type_id
# ---------------------------

ALTER TABLE `applications` 
CHANGE COLUMN `initial` `application_type_id` TINYINT(2) NULL DEFAULT '1' ;

# ---------------------------
# Update the Fee type to reflect the application type. Note, this may be replaced with the application type id
# ---------------------------

UPDATE `fees` SET `fees`.`type` = (SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial') WHERE `fees`.`type` = 'new';
UPDATE `fees` SET `fees`.`type` = (SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal') WHERE `fees`.`type` = 'renewal';

ALTER TABLE `fees` 
CHANGE COLUMN `type` `application_type_id` INT NOT NULL ;

# ---------------------------
# Create the Application Type License Type credit hours table
# ---------------------------

CREATE TABLE `app_lic_credit_hours` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `application_type_id` INT NOT NULL,
  `license_type_id` INT NOT NULL,
  `code_hours` INT NOT NULL,
  `total_hours` INT NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`));

# ---------------------------
# Move credit hours into app_lic_credit_hours 
# ---------------------------

ALTER TABLE `license_types` 
DROP COLUMN `renewal_total_hours`,
DROP COLUMN `renewal_code_hours`,
DROP COLUMN `initial_total_hours`,
DROP COLUMN `initial_code_hours`;

# ---------------------------
# Updating ElementLicenseTypes table to accept conversion_required
# ---------------------------

ALTER TABLE `element_license_types` ADD COLUMN `conversion_required` TINYINT(1) NULL DEFAULT '0' AFTER `renewal_required`;

# ---------------------------
# Recreate the billing items report view
# ---------------------------

DROP VIEW IF EXISTS `billing_items_report` ;
DROP TABLE IF EXISTS `billing_items_report`;

CREATE VIEW `billing_items_report` AS 
SELECT `Application`.`id` AS `id`,`LicenseType`.`id` AS `license_type_id`,`LicenseType`.`label` AS `label`,`Application`.`paid_date` AS `paid_date`,`License`.`issued_date` AS `issued_date`,`Application`.`application_type_id` AS `application_type_id` 
FROM ((`applications` `Application` LEFT JOIN `licenses` `License` ON ((`Application`.`license_id` = `License`.`id`))) LEFT JOIN `license_types` `LicenseType` ON ((`License`.`license_type_id` = `LicenseType`.`id`))) WHERE (`Application`.`paid_date` IS NOT NULL) ORDER BY `Application`.`paid_date`;

# ---------------------------
# Insert the converted record into the License Status table
# ---------------------------

INSERT INTO `license_statuses` (`status`, `license_status_level_id`, `created`, `modified`) VALUES ('Converted', 2, NOW(), NOW());