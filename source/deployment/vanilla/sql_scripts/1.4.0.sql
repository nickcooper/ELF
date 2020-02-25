# put 1.4 table structure changes here

# Add perjury columns to applications table (Nick Cooper)
ALTER TABLE `applications` 
ADD COLUMN `perjury_name` VARCHAR(250) NULL DEFAULT NULL  AFTER `initial` , 
ADD COLUMN `perjury_date` DATE NULL DEFAULT NULL  AFTER `perjury_name` ;

# Update the application sections to include renewal required field.
ALTER TABLE `element_license_types` 
CHANGE COLUMN `required` `initial_required` TINYINT(1) NULL DEFAULT 0  , 
ADD COLUMN `renewal_required` TINYINT(1) NULL DEFAULT 0  AFTER `initial_required` ;

# Copy existing initial required setting to the renewal required field
UPDATE `element_license_types` SET `renewal_required` = `initial_required` ;

# Update application section data key
UPDATE `elements` SET data_keys = 'LicenseVariant' WHERE data_keys = 'License.Variant' ;

# Update the element description field to text
ALTER TABLE `elements` CHANGE COLUMN `description` `description` TEXT NULL DEFAULT NULL  ;

# Update the configuration for allowed_login_groups
INSERT INTO `configurations` (`name`, `value`) VALUES ('allowed_login_groups', '1,2,3') ;
