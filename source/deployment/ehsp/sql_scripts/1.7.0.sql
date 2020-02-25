# 1.7.0 EHSP data updates

# ---------------------------
# Move credit hours into app_lic_credit_hours 
# ---------------------------

INSERT INTO `app_lic_credit_hours` (`application_type_id`, `license_type_id`, `code_hours`, `total_hours`, `created`, `modified`) 
VALUES (
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'TRAIN'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'INSP'),
	40, 40, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'SAMP'),
	20, 20, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'CONT'),
	40, 40, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'WORK'),
	24, 24, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'LSR'),
	8, 8, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'FIRM'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'TRAIN'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'INSP'),
	16, 16, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'SAMP'),
	8, 8, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'CONT'),
	8, 8, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'WORK'),
	8, 8, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'LSR'),
	4, 4, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'FIRM'),
	0, 0, NOW(), NOW()
);

# ---------------------------
# Insert fee records for conversion type
# ---------------------------

INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Training Provider License','Licenses','LicenseType',1,3,200.00,0,1,'2013-06-27 06:09:43','2013-06-27 06:09:43');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Lead Inspector / Risk Assessor','Licenses','LicenseType',2,3,60.00,0,1,'2013-06-27 06:09:43','2013-06-27 06:09:43');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Sampling Technician','Licenses','LicenseType',3,3,60.00,0,1,'2013-06-27 06:09:43','2013-06-27 06:09:43');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Lead Safe Renovator','Licenses','LicenseType',6,3,60.00,0,1,'2013-06-27 06:09:43','2013-06-27 06:09:43');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Abatement Worker','Licenses','LicenseType',5,3,60.00,0,1,'2013-06-27 06:09:43','2013-06-27 06:09:43');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Abatement Contractor','Licenses','LicenseType',4,3,60.00,0,1,'2013-06-27 06:09:43','2013-06-27 06:09:43');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Firm','Licenses','LicenseType',7,3,0.00,0,1,'2013-06-27 06:09:43','2013-06-27 06:09:43');

# ---------------------------
# Update Elements to License Types for INSP and SAMP
# Don't make Education section required for initial or renewal
# ---------------------------

UPDATE `element_license_types` SET `initial_required`='0', `renewal_required`='0'  
	WHERE `license_type_id` IN (SELECT `id` FROM `license_types` WHERE `abbr` IN ('INSP', 'SAMP'))
	AND `element_id` = (SELECT `id` FROM `elements` WHERE label = 'Education');

# ---------------------------
# Updating the agency-specific descrpition for the Firm Information application sections
# ---------------------------

UPDATE elements
SET description = 'In this section, you will have the ability to search for and associate to Firm Licenses that are in the system.  If you need to create a new Firm to associate to, you will first need to navigate to your 
					My Account page and select Firm from the Add New License drop-down.'
WHERE label = 'Firm Information';