# 1.6.0 DPS data updates

# ---------------------------
# Update configuration for continuing education type
# ---------------------------

UPDATE `configurations` SET `value`='minimal' WHERE `name`='continuing_ed_type';

# ---------------------------
# Updating code/total hours for license types
# ---------------------------

UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'MA';
UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'MB';
UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'JA';
UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'JB';
UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'RM';
UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'RE';
UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'IMA';
UPDATE `license_types` SET `license_types`.`renewal_total_hours` = 6 WHERE `license_types`.`abbr` = 'IMB';

# ---------------------------
# Setting IMA and IMB license types as unavailable for initial licensing
# ---------------------------

UPDATE `license_types` SET `avail_for_initial`= 0 WHERE `license_types`.`abbr` = 'IMA';
UPDATE `license_types` SET `avail_for_initial`= 0 WHERE `license_types`.`abbr` = 'IMB';

# ---------------------------
# Fix Insurance Information foreign model reference
# ---------------------------

UPDATE `insurance_informations` SET `foreign_obj` = 'License' WHERE `foreign_obj` = 'Licenses';