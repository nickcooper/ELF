# 1.7.0 DPS data updates

# ---------------------------
# Remove the Courses section from Special Electrician applications 
# ---------------------------

DELETE FROM element_license_types WHERE license_type_id = 5 AND element_id = 8;

# ---------------------------
# Move credit hours into app_lic_credit_hours 
# ---------------------------

INSERT INTO `app_lic_credit_hours` (`application_type_id`, `license_type_id`, `code_hours`, `total_hours`, `created`, `modified`) 
VALUES (
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'MA'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'MB'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'JA'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'JB'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'SE'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'RM'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'RE'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'IMA'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'IMB'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'AE'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'UP'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'EC'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Initial'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'REC'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'MA'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'MB'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'JA'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'JB'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'SE'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'RM'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'RE'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'IMA'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'IMB'),
	6, 6, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'AE'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'UP'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'EC'),
	0, 0, NOW(), NOW()
),
(
	(SELECT `application_types`.`id` FROM `application_types` WHERE `application_types`.`label` = 'Renewal'), 
	(SELECT `license_types`.`id` FROM `license_types` WHERE `license_types`.`abbr` = 'REC'),
	0, 0, NOW(), NOW()
);

# ---------------------------
# Update required sections for converting to license type Master Class A 
# ---------------------------

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Licensed By Exam')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Journeyman Class A 
# ---------------------------

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Licensed By Exam')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Residential Electrician
# ---------------------------

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Licensed By Exam')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Residential Master 
# ---------------------------

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Master')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Master')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Master')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Licensed By Exam')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Master')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types 
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Master')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Master Class B
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Work Experience')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Journeyman Class B
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Work Experience')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Journeyman Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Inactive Master Class A
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class A')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Inactive Master Class B
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Inactive Master Class B')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Apprentice
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Apprentice')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Apprentice')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Apprentice')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Apprentice')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Unclassified Person
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Unclassified Person')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Unclassified Person')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Unclassified Person')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Unclassified Person')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Special Electrician
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Special Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Personal Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Special Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Special Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Variant')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Special Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Accounts' AND elements.foreign_obj = 'Account'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Special Electrician')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Electrical Contractor
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Electrical Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Electrical Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Firm Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Electrical Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Contractor Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Electrical Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Associated Licensees')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Electrical Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Licenses' AND elements.foreign_obj = 'Application'
AND elements.label = 'Insurance Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Electrical Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Electrical Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Update required sections for converting to license type Residential Contractor
# ---------------------------

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Addresses')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Firm Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Contractor Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Associated Licensees')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Licenses' AND elements.foreign_obj = 'Application'
AND elements.label = 'Insurance Information')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.foreign_plugin = 'Firms' AND elements.foreign_obj = 'Firm'
AND elements.label = 'Screening Questions')));

UPDATE element_license_types
SET conversion_required = 1
WHERE (element_license_types.license_type_id = (SELECT license_types.id FROM license_types WHERE license_types.label = 'Residential Contractor')
AND (element_license_types.element_id = (SELECT elements.id FROM elements WHERE elements.label = 'Perjury Information')));

# ---------------------------
# Insert fee records for conversion type
# ---------------------------

INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Master Class A','Licenses','LicenseType',1,3,375.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Master Class B','Licenses','LicenseType',2,3,375.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Inactive Master Class A','Licenses','LicenseType',8,3,75.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Inactive Master Class B','Licenses','LicenseType',9,3,75.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Journeyman Class A','Licenses','LicenseType',3,3,75.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Journeyman Class B','Licenses','LicenseType',4,3,75.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Special Electrician','Licenses','LicenseType',5,3,75.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Residential Master','Licenses','LicenseType',6,3,375.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Residential Electrician','Licenses','LicenseType',7,3,75.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Apprentice','Licenses','LicenseType',10,3,20.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Unclassified Person','Licenses','LicenseType',11,3,20.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Electrical Contractor','Licenses','LicenseType',12,3,375.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fees` (`label`,`foreign_plugin`,`foreign_obj`,`foreign_key`,`application_type_id`,`fee`,`apply_tax`,`removable`,`created`,`modified`) VALUES ('Residential Contractor','Licenses','LicenseType',13,3,375.00,1,1,'2013-09-24 17:36:29','2013-09-24 17:36:29');

INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.49,'-35 months','-34 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.9,'-34 months','-33 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.31,'-33 months','-32 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.72,'-32 months','-31 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.13,'-31 months','-30 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.54,'-30 months','-29 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-72.95,'-29 months','-28 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-83.36,'-28 months','-27 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-93.77,'-27 months','-26 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-104.18,'-26 months','-25 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-114.59,'-25 months','-24 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-125,'-24 months','-23 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-135.49,'-23 months','-22 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-145.9,'-22 months','-21 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-156.39,'-21 months','-20 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-166.72,'-20 months','-19 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-177.13,'-19 months','-18 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-187.54,'-18 months','-17 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-197.95,'-17 months','-16 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-208.36,'-16 months','-15 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-218.77,'-15 months','-14 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-229.18,'-14 months','-13 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-239.59,'-13 months','-12 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-250,'-12 months','-11 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-260.49,'-11 months','-10 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-270.9,'-10 months','-9 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-281.31,'-9 months','-8 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-291.72,'-8 months','-7 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-302.13,'-7 months','-6 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-312.54,'-6 months','-5 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-322.95,'-5 months','-4 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-333.36,'-4 months','-3 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.49,'-35 months','-34 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.9,'-34 months','-33 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.31,'-33 months','-32 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.72,'-32 months','-31 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.13,'-31 months','-30 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.54,'-30 months','-29 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-72.95,'-29 months','-28 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-83.36,'-28 months','-27 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-93.77,'-27 months','-26 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-104.18,'-26 months','-25 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-114.59,'-25 months','-24 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-125,'-24 months','-23 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-135.49,'-23 months','-22 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-145.9,'-22 months','-21 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-156.39,'-21 months','-20 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-166.72,'-20 months','-19 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-177.13,'-19 months','-18 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-187.54,'-18 months','-17 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-197.95,'-17 months','-16 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-208.36,'-16 months','-15 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-218.77,'-15 months','-14 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-229.18,'-14 months','-13 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-239.59,'-13 months','-12 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-250,'-12 months','-11 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-260.49,'-11 months','-10 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-270.9,'-10 months','-9 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-281.31,'-9 months','-8 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-291.72,'-8 months','-7 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-302.13,'-7 months','-6 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-312.54,'-6 months','-5 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-322.95,'-5 months','-4 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-333.36,'-4 months','-3 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-2.12,'-35 months','-34 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-4.92,'-34 months','-33 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.28,'-33 months','-32 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.36,'-32 months','-31 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.44,'-31 months','-30 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-12.52,'-30 months','-29 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-14.6,'-29 months','-28 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-16.68,'-28 months','-27 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-18.76,'-27 months','-26 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.84,'-26 months','-25 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-22.92,'-25 months','-24 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-25,'-24 months','-23 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-27.12,'-23 months','-22 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-29.2,'-22 months','-21 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.28,'-21 months','-20 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-33.36,'-20 months','-19 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-35.44,'-19 months','-18 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-37.52,'-18 months','-17 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-39.6,'-17 months','-16 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.68,'-16 months','-15 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-43.76,'-15 months','-14 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-45.84,'-14 months','-13 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-47.92,'-13 months','-12 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-50,'-12 months','-11 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.12,'-11 months','-10 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-54.2,'-10 months','-9 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-56.28,'-9 months','-8 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-58.36,'-8 months','-7 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-60.44,'-7 months','-6 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.52,'-6 months','-5 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-64.6,'-5 months','-4 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-66.68,'-4 months','-3 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-2.12,'-35 months','-34 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-4.92,'-34 months','-33 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.28,'-33 months','-32 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.36,'-32 months','-31 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.44,'-31 months','-30 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-12.52,'-30 months','-29 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-14.6,'-29 months','-28 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-16.68,'-28 months','-27 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-18.76,'-27 months','-26 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.84,'-26 months','-25 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-22.92,'-25 months','-24 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-25,'-24 months','-23 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-27.12,'-23 months','-22 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-29.2,'-22 months','-21 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.28,'-21 months','-20 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-33.36,'-20 months','-19 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-35.44,'-19 months','-18 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-37.52,'-18 months','-17 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-39.6,'-17 months','-16 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.68,'-16 months','-15 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-43.76,'-15 months','-14 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-45.84,'-14 months','-13 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-47.92,'-13 months','-12 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-50,'-12 months','-11 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.12,'-11 months','-10 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-54.2,'-10 months','-9 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-56.28,'-9 months','-8 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-58.36,'-8 months','-7 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-60.44,'-7 months','-6 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.52,'-6 months','-5 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-64.6,'-5 months','-4 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Inactive Master Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-66.68,'-4 months','-3 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-2.12,'-35 months','-34 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-4.92,'-34 months','-33 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.28,'-33 months','-32 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.36,'-32 months','-31 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.44,'-31 months','-30 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-12.52,'-30 months','-29 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-14.6,'-29 months','-28 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-16.68,'-28 months','-27 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-18.76,'-27 months','-26 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.84,'-26 months','-25 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-22.92,'-25 months','-24 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-25,'-24 months','-23 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-27.12,'-23 months','-22 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-29.2,'-22 months','-21 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.28,'-21 months','-20 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-33.36,'-20 months','-19 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-35.44,'-19 months','-18 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-37.52,'-18 months','-17 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-39.6,'-17 months','-16 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.68,'-16 months','-15 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-43.76,'-15 months','-14 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-45.84,'-14 months','-13 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-47.92,'-13 months','-12 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-50,'-12 months','-11 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.12,'-11 months','-10 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-54.2,'-10 months','-9 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-56.28,'-9 months','-8 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-58.36,'-8 months','-7 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-60.44,'-7 months','-6 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.52,'-6 months','-5 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-64.6,'-5 months','-4 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class A' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-66.68,'-4 months','-3 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-2.12,'-35 months','-34 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-4.92,'-34 months','-33 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.28,'-33 months','-32 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.36,'-32 months','-31 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.44,'-31 months','-30 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-12.52,'-30 months','-29 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-14.6,'-29 months','-28 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-16.68,'-28 months','-27 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-18.76,'-27 months','-26 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.84,'-26 months','-25 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-22.92,'-25 months','-24 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-25,'-24 months','-23 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-27.12,'-23 months','-22 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-29.2,'-22 months','-21 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.28,'-21 months','-20 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-33.36,'-20 months','-19 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-35.44,'-19 months','-18 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-37.52,'-18 months','-17 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-39.6,'-17 months','-16 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.68,'-16 months','-15 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-43.76,'-15 months','-14 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-45.84,'-14 months','-13 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-47.92,'-13 months','-12 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-50,'-12 months','-11 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.12,'-11 months','-10 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-54.2,'-10 months','-9 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-56.28,'-9 months','-8 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-58.36,'-8 months','-7 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-60.44,'-7 months','-6 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.52,'-6 months','-5 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-64.6,'-5 months','-4 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Journeyman Class B' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-66.68,'-4 months','-3 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-2.12,'-35 months','-34 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-4.92,'-34 months','-33 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.28,'-33 months','-32 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.36,'-32 months','-31 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.44,'-31 months','-30 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-12.52,'-30 months','-29 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-14.6,'-29 months','-28 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-16.68,'-28 months','-27 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-18.76,'-27 months','-26 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.84,'-26 months','-25 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-22.92,'-25 months','-24 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-25,'-24 months','-23 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-27.12,'-23 months','-22 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-29.2,'-22 months','-21 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.28,'-21 months','-20 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-33.36,'-20 months','-19 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-35.44,'-19 months','-18 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-37.52,'-18 months','-17 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-39.6,'-17 months','-16 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.68,'-16 months','-15 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-43.76,'-15 months','-14 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-45.84,'-14 months','-13 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-47.92,'-13 months','-12 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-50,'-12 months','-11 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.12,'-11 months','-10 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-54.2,'-10 months','-9 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-56.28,'-9 months','-8 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-58.36,'-8 months','-7 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-60.44,'-7 months','-6 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.52,'-6 months','-5 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-64.6,'-5 months','-4 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Special Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-66.68,'-4 months','-3 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.49,'-35 months','-34 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.9,'-34 months','-33 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.31,'-33 months','-32 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.72,'-32 months','-31 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.13,'-31 months','-30 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.54,'-30 months','-29 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-72.95,'-29 months','-28 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-83.36,'-28 months','-27 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-93.77,'-27 months','-26 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-104.18,'-26 months','-25 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-114.59,'-25 months','-24 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-125,'-24 months','-23 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-135.49,'-23 months','-22 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-145.9,'-22 months','-21 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-156.39,'-21 months','-20 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-166.72,'-20 months','-19 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-177.13,'-19 months','-18 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-187.54,'-18 months','-17 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-197.95,'-17 months','-16 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-208.36,'-16 months','-15 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-218.77,'-15 months','-14 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-229.18,'-14 months','-13 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-239.59,'-13 months','-12 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-250,'-12 months','-11 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-260.49,'-11 months','-10 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-270.9,'-10 months','-9 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-281.31,'-9 months','-8 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-291.72,'-8 months','-7 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-302.13,'-7 months','-6 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-312.54,'-6 months','-5 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-322.95,'-5 months','-4 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Master' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-333.36,'-4 months','-3 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-2.12,'-35 months','-34 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-4.92,'-34 months','-33 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.28,'-33 months','-32 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.36,'-32 months','-31 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.44,'-31 months','-30 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-12.52,'-30 months','-29 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-14.6,'-29 months','-28 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-16.68,'-28 months','-27 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-18.76,'-27 months','-26 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.84,'-26 months','-25 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-22.92,'-25 months','-24 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-25,'-24 months','-23 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-27.12,'-23 months','-22 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-29.2,'-22 months','-21 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.28,'-21 months','-20 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-33.36,'-20 months','-19 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-35.44,'-19 months','-18 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-37.52,'-18 months','-17 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-39.6,'-17 months','-16 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.68,'-16 months','-15 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-43.76,'-15 months','-14 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-45.84,'-14 months','-13 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-47.92,'-13 months','-12 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-50,'-12 months','-11 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.12,'-11 months','-10 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-54.2,'-10 months','-9 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-56.28,'-9 months','-8 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-58.36,'-8 months','-7 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-60.44,'-7 months','-6 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.52,'-6 months','-5 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-64.6,'-5 months','-4 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Electrician' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-66.68,'-4 months','-3 months','2013-09-24 17:36:31','2013-09-24 17:36:31');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-1.74,'-11 months','-10 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-3.4,'-10 months','-9 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-5.06,'-9 months','-8 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.72,'-8 months','-7 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.38,'-7 months','-6 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.04,'-6 months','-5 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-11.7,'-5 months','-4 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Apprentice' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-13.36,'-4 months','-3 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-1.74,'-11 months','-10 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-3.4,'-10 months','-9 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-5.06,'-9 months','-8 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-6.72,'-8 months','-7 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-8.38,'-7 months','-6 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.04,'-6 months','-5 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-11.7,'-5 months','-4 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Unclassified Person' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-13.36,'-4 months','-3 months','2013-09-24 17:36:29','2013-09-24 17:36:29');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.49,'-35 months','-34 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.9,'-34 months','-33 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.31,'-33 months','-32 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.72,'-32 months','-31 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.13,'-31 months','-30 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.54,'-30 months','-29 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-72.95,'-29 months','-28 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-83.36,'-28 months','-27 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-93.77,'-27 months','-26 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-104.18,'-26 months','-25 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-114.59,'-25 months','-24 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-125,'-24 months','-23 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-135.49,'-23 months','-22 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-145.9,'-22 months','-21 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-156.39,'-21 months','-20 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-166.72,'-20 months','-19 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-177.13,'-19 months','-18 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-187.54,'-18 months','-17 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-197.95,'-17 months','-16 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-208.36,'-16 months','-15 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-218.77,'-15 months','-14 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-229.18,'-14 months','-13 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-239.59,'-13 months','-12 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-250,'-12 months','-11 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-260.49,'-11 months','-10 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-270.9,'-10 months','-9 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-281.31,'-9 months','-8 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-291.72,'-8 months','-7 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-302.13,'-7 months','-6 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-312.54,'-6 months','-5 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-322.95,'-5 months','-4 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Electrical Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-333.36,'-4 months','-3 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-10.49,'-35 months','-34 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-20.9,'-34 months','-33 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-31.31,'-33 months','-32 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-41.72,'-32 months','-31 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-52.13,'-31 months','-30 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-62.54,'-30 months','-29 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-72.95,'-29 months','-28 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-83.36,'-28 months','-27 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-93.77,'-27 months','-26 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-104.18,'-26 months','-25 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-114.59,'-25 months','-24 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-125,'-24 months','-23 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-135.49,'-23 months','-22 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-145.9,'-22 months','-21 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-156.39,'-21 months','-20 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-166.72,'-20 months','-19 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-177.13,'-19 months','-18 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-187.54,'-18 months','-17 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-197.95,'-17 months','-16 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-208.36,'-16 months','-15 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-218.77,'-15 months','-14 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-229.18,'-14 months','-13 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-239.59,'-13 months','-12 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-250,'-12 months','-11 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-260.49,'-11 months','-10 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-270.9,'-10 months','-9 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-281.31,'-9 months','-8 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-291.72,'-8 months','-7 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-302.13,'-7 months','-6 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-312.54,'-6 months','-5 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-322.95,'-5 months','-4 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
INSERT INTO `fee_modifiers` (`fee_id`,`label`,`type`,`fee`,`start_range`,`end_range`,`modified`,`created`) VALUES ((SELECT `fees`.`id` FROM `fees` WHERE `fees`.`label` = 'Residential Contractor' AND `fees`.`application_type_id` = 3),'Prorate','dollar',-333.36,'-4 months','-3 months','2013-09-24 17:36:30','2013-09-24 17:36:30');
