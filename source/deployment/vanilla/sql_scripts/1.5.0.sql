# 1.5.0 Vanilla data updates

# ---------------------------
# Adding permission script
# ---------------------------

UPDATE aros SET alias=CONCAT(model,foreign_key) WHERE alias IS NULL;

CREATE INDEX idx_acos_lft_rght ON `acos`(lft,rght);
CREATE INDEX idx_acos_alias ON `acos`(alias);
CREATE INDEX idx_acos_model_foreign_key ON `acos`(model(255),foreign_key);
CREATE INDEX idx_aros_lft_rght ON `aros`(lft,rght);
CREATE INDEX idx_aros_alias ON `aros`(alias);
CREATE INDEX idx_aros_model_foreign_key ON `aros`(model(255),foreign_key);
CREATE UNIQUE INDEX idx_aros_acos_aro_id_aco_id ON `aros_acos`(aro_id, aco_id);
ALTER TABLE aros_acos ADD CONSTRAINT FOREIGN KEY (aro_id) REFERENCES `aros`(id);
ALTER TABLE aros_acos ADD CONSTRAINT FOREIGN KEY (aco_id) REFERENCES `acos`(id);

# ---------------------------
# Add group home page settings to groups and group_programs table
# ---------------------------
ALTER TABLE `groups` ADD COLUMN `home` VARCHAR(250) NULL DEFAULT '/my_account'  AFTER `descr` ;
ALTER TABLE `group_programs` ADD COLUMN `home` VARCHAR(250) NULL DEFAULT '/my_account'  AFTER `descr` ;

# ---------------------------
# Update super admin group home page settings in groups table
# ---------------------------
UPDATE `groups` SET `home`='/app_admin' WHERE `label`='Super Admin';

# ---------------------------
# Add Group Payment Types table
# ---------------------------

CREATE TABLE `groups_payment_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_id` INT NOT NULL ,
  `payment_type_id` INT NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) );

# ---------------------------
# Add Group Payment Types records
# ---------------------------

INSERT INTO `groups_payment_types` (`group_id`, `payment_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `groups`.`id` FROM `groups` WHERE `groups`.`label` = 'Default Group'),
	(SELECT `payment_types`.`id` FROM `payment_types` WHERE `payment_types`.`label` = 'Credit Card'),
	NOW(),
	NOW()
);

INSERT INTO `groups_payment_types` (`group_id`, `payment_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `groups`.`id` FROM `groups` WHERE `groups`.`label` = 'Super Admin'),
	(SELECT `payment_types`.`id` FROM `payment_types` WHERE `payment_types`.`label` = 'Cash'),
	NOW(),
	NOW()
);

INSERT INTO `groups_payment_types` (`group_id`, `payment_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `groups`.`id` FROM `groups` WHERE `groups`.`label` = 'Super Admin'),
	(SELECT `payment_types`.`id` FROM `payment_types` WHERE `payment_types`.`label` = 'Check'),
	NOW(),
	NOW()
);

INSERT INTO `groups_payment_types` (`group_id`, `payment_type_id`, `created`, `modified`) 
VALUES (
	(SELECT `groups`.`id` FROM `groups` WHERE `groups`.`label` = 'Super Admin'),
	(SELECT `payment_types`.`id` FROM `payment_types` WHERE `payment_types`.`label` = 'Money Order'),
	NOW(),
	NOW()
);

# ---------------------------
# Add License Types License Types table
# ---------------------------

CREATE  TABLE `license_types_license_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `parent_license_type_id` INT NOT NULL ,
  `license_type_id` INT NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) );

# ---------------------------
# Change fee_data in payment items and pending payment items to TEXT so it's large enough to hold the data
# ---------------------------

ALTER TABLE `payment_items` CHANGE `fee_data` `fee_data` TEXT  CHARACTER SET latin1  COLLATE latin1_swedish_ci  NOT NULL;
ALTER TABLE `pending_payment_items` CHANGE `fee_data` `fee_data` TEXT  CHARACTER SET latin1  COLLATE latin1_swedish_ci  NOT NULL;