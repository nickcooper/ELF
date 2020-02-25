# 1.5.0 EHSP data updates

# ---------------------------
# Update LEAD admin group home page settings in groups table
# ---------------------------
UPDATE `groups` SET `home`='/licenses/licenses/queue' WHERE `label`='Program Admin (LEAD)';

# ---------------------------
# Add Group Payment Types records
# ---------------------------

INSERT INTO `groups_payment_types` (`group_id`, `payment_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `groups`.`id` FROM `groups` WHERE `groups`.`label` = 'Program Admin (LEAD)'),
	(SELECT `payment_types`.`id` FROM `payment_types` WHERE `payment_types`.`label` = 'Cash'),
	NOW(),
	NOW()
);

INSERT INTO `groups_payment_types` (`group_id`, `payment_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `groups`.`id` FROM `groups` WHERE `groups`.`label` = 'Program Admin (LEAD)'),
	(SELECT `payment_types`.`id` FROM `payment_types` WHERE `payment_types`.`label` = 'Check'),
	NOW(),
	NOW()
);

INSERT INTO `groups_payment_types` (`group_id`, `payment_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `groups`.`id` FROM `groups` WHERE `groups`.`label` = 'Program Admin (LEAD)'),
	(SELECT `payment_types`.`id` FROM `payment_types` WHERE `payment_types`.`label` = 'Money Order'),
	NOW(),
	NOW()
);

# ---------------------------
# Add License Types License Types records
# ---------------------------

INSERT INTO `license_types_license_types` (`parent_license_type_id`, `license_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'FIRM'),
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'INSP'),
	NOW(),
	NOW()
);

INSERT INTO `license_types_license_types` (`parent_license_type_id`, `license_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'FIRM'),
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'SAMP'),
	NOW(),
	NOW()
);

INSERT INTO `license_types_license_types` (`parent_license_type_id`, `license_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'FIRM'),
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'CONT'),
	NOW(),
	NOW()
);

INSERT INTO `license_types_license_types` (`parent_license_type_id`, `license_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'FIRM'),
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'WORK'),
	NOW(),
	NOW()
);

INSERT INTO `license_types_license_types` (`parent_license_type_id`, `license_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'FIRM'),
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'LSR'),
	NOW(),
	NOW()
);

# ---------------------------
# Add Account Management section to Program Admin
# ---------------------------

INSERT INTO `program_plugins` (`program_id`, `plugin_id`) 
VALUES (
	(SELECT `register_plugins`.`id` FROM `register_plugins` WHERE `register_plugins`.`label` = 'Accounts'),
	(SELECT `programs`.`id` FROM `programs` WHERE `programs`.`abbr` = 'LEAD')
);

# ---------------------------
# Add site name to the configuration
# ---------------------------

INSERT INTO `configurations` (`name`, `value`, `created`, `modified`) VALUES ('site_name', 'Environmental Health Services Portal', NOW(), NOW());