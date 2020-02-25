# 1.6.0 EHSP data updates

# ---------------------------
# Updating code/total hours for license types
# ---------------------------

UPDATE `license_types` SET `license_types`.`initial_total_hours` = 40, `license_types`.`renewal_total_hours` = 16 WHERE `license_types`.`abbr` = 'INSP';
UPDATE `license_types` SET `license_types`.`initial_total_hours` = 20, `license_types`.`renewal_total_hours` = 8 WHERE `license_types`.`abbr` = 'SAMP';
UPDATE `license_types` SET `license_types`.`initial_total_hours` = 40, `license_types`.`renewal_total_hours` = 8 WHERE `license_types`.`abbr` = 'CONT';
UPDATE `license_types` SET `license_types`.`initial_total_hours` = 24, `license_types`.`renewal_total_hours` = 8 WHERE `license_types`.`abbr` = 'WORK';
UPDATE `license_types` SET `license_types`.`initial_total_hours` = 8, `license_types`.`renewal_total_hours` = 4 WHERE `license_types`.`abbr` = 'LSR';