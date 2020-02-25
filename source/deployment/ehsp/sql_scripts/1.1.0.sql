# Training Provider renewal cycle
UPDATE `license_types`
SET
    `cycle` = '36',
    `renew_before` = '12',
    `renew_after` = 0,
    `month_calc` = '1',
    `static_expiration` = NULL
WHERE `license_types`.`abbr` = 'TRAIN';

# All other licenses renewal cycle
UPDATE `license_types`
SET
    `cycle` = '12',
    `renew_before` = '6',
    `renew_after` = 0,
    `month_calc` = '1',
    `static_expiration` = NULL
WHERE `license_types`.`abbr` != 'TRAIN';


# Script to fix the license_types to course_catalogs associations for LEAD
# Remove the CNT, WRK, RWK, UAR and WTC courses from INSP license type
DELETE FROM course_catalogs_license_types 
WHERE id IN (6, 7, 4, 8, 5);

# Add the UAR course to the WORK license type
INSERT INTO course_catalogs_license_types
(`course_catalog_id`, `license_type_id`, `initial`, `renewal`) VALUES (10, 5, 0, 1);

