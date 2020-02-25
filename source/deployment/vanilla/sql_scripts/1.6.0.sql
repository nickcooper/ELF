# 1.6.0 Vanilla data updates


# ---------------------------
# Updating CourseCatalog abbr field to allow up to six characters
# ---------------------------

ALTER TABLE `course_catalogs` CHANGE COLUMN `abbr` `abbr` VARCHAR(6) NULL DEFAULT NULL;


# ---------------------------
# Updating CourseCatalog to accept code and non-code hours in decimal format
# ---------------------------

ALTER TABLE `course_catalogs` CHANGE COLUMN `hours` `code_hours` FLOAT NULL DEFAULT 0;
ALTER TABLE `course_catalogs` ADD COLUMN `non_code_hours` FLOAT NULL DEFAULT 0  AFTER `code_hours` ;


# ---------------------------
# Updating LicenseType to accept code and total hours in decimal format
# ---------------------------

ALTER TABLE `license_types` CHANGE COLUMN `initial_hours` `initial_code_hours` float default '0';
ALTER TABLE `license_types` ADD COLUMN `initial_total_hours` FLOAT NULL DEFAULT 0  AFTER `initial_code_hours`;

ALTER TABLE `license_types` CHANGE COLUMN `renewal_hours` `renewal_code_hours` float default '0';
ALTER TABLE `license_types` ADD COLUMN `renewal_total_hours` FLOAT NULL DEFAULT 0  AFTER `renewal_code_hours`;


# ---------------------------
# Add configuration for continuing education type
# ---------------------------

INSERT INTO `configurations` (`name`, `value`, `options`) VALUES ('continuing_ed_type', 'full', 'a:2:{i:0;s:4:"full";i:1;s:7:"minimal";}');


# ---------------------------
# Updating LicenseType to specify if available for initial and/or renewal licensing
# ---------------------------

ALTER TABLE `license_types` ADD COLUMN `avail_for_initial` TINYINT(1) NOT NULL DEFAULT '1'  AFTER `cycle` ,
							ADD COLUMN `avail_for_renewal` TINYINT(1) NOT NULL DEFAULT '1'  AFTER `initial_total_hours` ;


# ---------------------------
# Updating Payments to include payment_date and payment_received_date fields
# ---------------------------

ALTER TABLE `payments` ADD COLUMN `payment_date` DATETIME NULL DEFAULT NULL  AFTER `amount_paid` , 
					   ADD COLUMN `payment_received_date` DATETIME NULL DEFAULT NULL  AFTER `payment_date` ;


# ---------------------------
# Updating Pending Payments to include payment_date and payment_received_date fields
# ---------------------------

ALTER TABLE `pending_payments` ADD COLUMN `payment_date` DATETIME NULL DEFAULT NULL  AFTER `amount_paid` , 
							   ADD COLUMN `payment_received_date` DATETIME NULL DEFAULT NULL  AFTER `payment_date` ;

