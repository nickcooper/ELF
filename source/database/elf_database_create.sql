SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `accounts` ;

CREATE  TABLE IF NOT EXISTS `accounts` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `legacy_id` VARCHAR(150) NULL DEFAULT NULL COMMENT 'For legacy import or historical reference only' ,
  `group_id` INT(10) NULL DEFAULT NULL ,
  `username` VARCHAR(45) NULL DEFAULT NULL ,
  `password` VARCHAR(45) NULL DEFAULT NULL ,
  `title` VARCHAR(45) NULL DEFAULT NULL ,
  `label` VARCHAR(150) NULL ,
  `first_name` VARCHAR(45) NOT NULL ,
  `last_name` VARCHAR(45) NOT NULL ,
  `middle_initial` VARCHAR(45) NULL DEFAULT NULL ,
  `email` VARCHAR(250) NULL DEFAULT NULL ,
  `ssn` VARCHAR(250) NULL DEFAULT NULL ,
  `ssn_last_four` CHAR(4) NOT NULL ,
  `dob` DATE NULL ,
  `enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `probation` TINYINT(1) NOT NULL DEFAULT '0' ,
  `perjury_acknowledged` TINYINT(1) NULL DEFAULT NULL ,
  `no_mail` TINYINT(1) NOT NULL DEFAULT 0 ,
  `last_login` DATETIME NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `group` (`group_id` ASC) ,
  INDEX `last_login` (`last_login` ASC) ,
  INDEX `enabled` (`enabled` ASC) ,
  INDEX `legacy_id` (`legacy_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `course_catalogs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course_catalogs` ;

CREATE  TABLE IF NOT EXISTS `course_catalogs` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `program_id` INT(10) NOT NULL ,
  `label` VARCHAR(100) NULL DEFAULT NULL ,
  `abbr` VARCHAR(6) NULL DEFAULT NULL ,
  `descr` VARCHAR(250) NULL DEFAULT NULL ,
  `code_hours` FLOAT NULL DEFAULT 0 ,
  `non_code_hours` FLOAT NULL DEFAULT 0 ,
  `test_attempts` INT(10) NULL DEFAULT NULL ,
  `enabled` TINYINT(4) NOT NULL DEFAULT '1' ,
  `cycle` INT(10) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `label_UNIQUE` (`label` ASC) ,
  UNIQUE INDEX `abbr_UNIQUE` (`abbr` ASC) ,
  INDEX `fk_course_catalogs_programs` (`program_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `training_providers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `training_providers` ;

CREATE  TABLE IF NOT EXISTS `training_providers` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `legacy_id` VARCHAR(150) NULL ,
  `label` VARCHAR(150) NOT NULL ,
  `abbr` VARCHAR(5) NULL DEFAULT NULL ,
  `website` VARCHAR(255) NULL ,
  `training_plan` MEDIUMTEXT NOT NULL ,
  `equipment` MEDIUMTEXT NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `label_UNIQUE` (`label` ASC) ,
  UNIQUE INDEX `abbr_UNIQUE` (`abbr` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `courses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `courses` ;

CREATE  TABLE IF NOT EXISTS `courses` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `course_catalog_id` INT(10) NOT NULL ,
  `training_provider_id` INT(10) NOT NULL ,
  `provider_materials` TINYINT NOT NULL DEFAULT 1 COMMENT 'Provider will supply the course materials.' ,
  `provider_tests` TINYINT NOT NULL DEFAULT 1 ,
  `enabled` TINYINT NOT NULL DEFAULT 1 ,
  `approved` TINYINT NOT NULL DEFAULT 0 ,
  `approved_date` DATETIME NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_courses_course_catalogs1_idx` (`course_catalog_id` ASC) ,
  INDEX `fk_courses_training_providers1_idx` (`training_provider_id` ASC) ,
  CONSTRAINT `fk_courses_course_catalogs1`
    FOREIGN KEY (`course_catalog_id` )
    REFERENCES `course_catalogs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_courses_training_providers1`
    FOREIGN KEY (`training_provider_id` )
    REFERENCES `training_providers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `exam_scores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `exam_scores` ;

CREATE  TABLE IF NOT EXISTS `exam_scores` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NULL ,
  `foreign_obj` VARCHAR(45) NULL ,
  `foreign_key` INT NULL ,
  `exam_date` DATE NULL DEFAULT NULL ,
  `score` DECIMAL(5,2) NOT NULL ,
  `pass` TINYINT(2) NOT NULL DEFAULT '0' ,
  `sponsored` TINYINT(1) NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `license_status_levels`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_status_levels` ;

CREATE  TABLE IF NOT EXISTS `license_status_levels` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `level` TINYINT(2) NOT NULL ,
  `descr` VARCHAR(250) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `license_statuses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_statuses` ;

CREATE  TABLE IF NOT EXISTS `license_statuses` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `status` VARCHAR(45) NOT NULL ,
  `license_status_level_id` INT(10) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_license_statuses_license_status_levels1_idx` (`license_status_level_id` ASC) ,
  CONSTRAINT `fk_license_statuses_license_status_levels1`
    FOREIGN KEY (`license_status_level_id` )
    REFERENCES `license_status_levels` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `programs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `programs` ;

CREATE  TABLE IF NOT EXISTS `programs` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NOT NULL ,
  `slug` VARCHAR(150) NOT NULL ,
  `abbr` VARCHAR(6) NOT NULL COMMENT 'Program abbreviation or acronym. Optional use in license number format.' ,
  `byline` VARCHAR(255) NULL DEFAULT NULL ,
  `email` VARCHAR(255) NULL DEFAULT NULL ,
  `short_descr` VARCHAR(255) NULL DEFAULT NULL ,
  `descr` TEXT NULL DEFAULT NULL ,
  `enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `merchant_code` VARCHAR(45) NULL DEFAULT NULL ,
  `service_code` VARCHAR(45) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`label` ASC) ,
  INDEX `created` (`created` ASC) ,
  INDEX `modified` (`modified` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `license_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_types` ;

CREATE  TABLE IF NOT EXISTS `license_types` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `program_id` INT(10) NOT NULL ,
  `foreign_plugin` VARCHAR(45) NOT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `label` VARCHAR(100) NOT NULL ,
  `slug` VARCHAR(100) NOT NULL ,
  `abbr` VARCHAR(6) NOT NULL COMMENT 'Type abbreviation or acronym. Optional use in license number format.' ,
  `cycle` INT(10) NOT NULL DEFAULT 365 ,
  `avail_for_initial` TINYINT(1) NOT NULL DEFAULT '1' ,
  `initial_code_hours` FLOAT NULL DEFAULT 0 ,
  `initial_total_hours` FLOAT NULL DEFAULT 0 ,
  `avail_for_renewal` TINYINT(1) NOT NULL DEFAULT '1' ,
  `renewal_code_hours` FLOAT NULL DEFAULT 0 ,
  `renewal_total_hours` FLOAT NULL DEFAULT 0 ,
  `renew_before` INT(10) NOT NULL DEFAULT 60 ,
  `renew_after` INT(10) NULL DEFAULT NULL ,
  `month_calc` TINYINT(1) NOT NULL DEFAULT 1 ,
  `static_expiration` DATE NULL DEFAULT NULL ,
  `descr` VARCHAR(250) NULL DEFAULT NULL ,
  `reciprocal` TINYINT(1) NULL DEFAULT '0' ,
  `enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_license_types_programs1_idx` (`program_id` ASC) ,
  CONSTRAINT `fk_license_types_programs1`
    FOREIGN KEY (`program_id` )
    REFERENCES `programs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `license_numbers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_numbers` ;

CREATE  TABLE IF NOT EXISTS `license_numbers` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NOT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `created` DATETIME NULL ,
  `modiifed` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `licenses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `licenses` ;

CREATE  TABLE IF NOT EXISTS `licenses` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `license_type_id` INT(10) NOT NULL ,
  `license_variant_id` INT(10) NULL DEFAULT NULL ,
  `license_status_id` INT(10) NOT NULL ,
  `issued_date` DATETIME NULL DEFAULT NULL ,
  `expire_date` DATETIME NULL DEFAULT NULL ,
  `not_renewing` TINYINT(4) NOT NULL DEFAULT '0' ,
  `pending` TINYINT(4) NOT NULL DEFAULT '0' ,
  `license_number` VARCHAR(45) NOT NULL ,
  `license_number_id` INT NOT NULL ,
  `label` VARCHAR(150) NULL DEFAULT NULL ,
  `legacy_number` VARCHAR(45) NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_licenses_license_status1` (`license_status_id` ASC) ,
  INDEX `fk_licenses_license_types1_idx` (`license_type_id` ASC) ,
  INDEX `fk_licenses_variants1` (`license_variant_id` ASC) ,
  INDEX `fk_licenses_accounts1` (`foreign_key` ASC) ,
  INDEX `fk_licenses_license_numbers1_idx` (`license_number_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `acos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `acos` ;

CREATE  TABLE IF NOT EXISTS `acos` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `parent_id` INT(10) NULL DEFAULT NULL ,
  `model` VARCHAR(255) NULL DEFAULT NULL ,
  `foreign_key` INT(10) NULL DEFAULT NULL ,
  `alias` VARCHAR(255) NULL DEFAULT NULL ,
  `lft` INT(10) NULL DEFAULT NULL ,
  `rght` INT(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_acos_lft_rght` (`lft` ASC, `rght` ASC) ,
  INDEX `idx_acos_alias` (`alias` ASC) ,
  INDEX `idx_acos_model_foreign_key` (`model`(255) ASC, `foreign_key` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `firm_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `firm_types` ;

CREATE  TABLE IF NOT EXISTS `firm_types` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `firms`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `firms` ;

CREATE  TABLE IF NOT EXISTS `firms` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `legacy_id` VARCHAR(150) NULL ,
  `firm_type_id` INT(10) NOT NULL ,
  `label` VARCHAR(255) NOT NULL ,
  `slug` VARCHAR(255) NULL DEFAULT NULL ,
  `alias` VARCHAR(255) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_firms_firm_types1_idx` (`firm_type_id` ASC) ,
  CONSTRAINT `fk_firms_firm_types1`
    FOREIGN KEY (`firm_type_id` )
    REFERENCES `firm_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `addresses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `addresses` ;

CREATE  TABLE IF NOT EXISTS `addresses` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `primary_flag` TINYINT(4) NULL DEFAULT NULL ,
  `label` VARCHAR(255) NULL DEFAULT NULL ,
  `attention` VARCHAR(255) NULL DEFAULT NULL ,
  `phone1` VARCHAR(11) NULL DEFAULT NULL ,
  `ext1` VARCHAR(4) NULL DEFAULT NULL ,
  `phone2` VARCHAR(11) NULL DEFAULT NULL ,
  `ext2` VARCHAR(4) NULL DEFAULT NULL ,
  `fax` VARCHAR(11) NULL DEFAULT NULL ,
  `addr1` VARCHAR(255) NULL DEFAULT NULL ,
  `addr2` VARCHAR(255) NULL DEFAULT NULL ,
  `city` VARCHAR(255) NULL DEFAULT NULL ,
  `state` VARCHAR(2) NULL DEFAULT NULL ,
  `county` VARCHAR(45) NULL DEFAULT NULL ,
  `postal` INT(9) NULL DEFAULT NULL ,
  `latitude` VARCHAR(45) NULL DEFAULT NULL ,
  `longitude` VARCHAR(45) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_addresses_firms1` (`foreign_key` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `application_statuses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `application_statuses` ;

CREATE  TABLE IF NOT EXISTS `application_statuses` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `applications`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `applications` ;

CREATE  TABLE IF NOT EXISTS `applications` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `license_id` INT(10) NOT NULL ,
  `application_status_id` INT(11) NOT NULL DEFAULT '1' ,
  `submitted_date` DATETIME NULL DEFAULT NULL ,
  `paid_date` DATETIME NULL DEFAULT NULL ,
  `effective_date` DATETIME NULL DEFAULT NULL ,
  `expire_date` DATETIME NULL DEFAULT NULL ,
  `materials_received` DATETIME NULL DEFAULT NULL COMMENT '	' ,
  `application_type_id` TINYINT(2) NOT NULL ,
  `converted_license_id` INT(10) NULL DEFAULT NULL ,
  `perjury_name` VARCHAR(250) NULL DEFAULT NULL ,
  `perjury_date` DATE NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `license_id` (`license_id` ASC) ,
  INDEX `fk_applications_application_statuses1_idx` (`application_status_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `aros`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aros` ;

CREATE  TABLE IF NOT EXISTS `aros` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `parent_id` INT(10) NULL DEFAULT NULL ,
  `model` VARCHAR(255) NULL DEFAULT NULL ,
  `foreign_key` INT(10) NULL DEFAULT NULL ,
  `alias` VARCHAR(255) NULL DEFAULT NULL ,
  `lft` INT(10) NULL DEFAULT NULL ,
  `rght` INT(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_aros_accounts1` (`foreign_key` ASC) ,
  INDEX `idx_aros_lft_rght` (`lft` ASC, `rght` ASC) ,
  INDEX `idx_aros_alias` (`alias` ASC) ,
  INDEX `idx_aros_model_foreign_key` (`model`(255) ASC, `foreign_key` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `aros_acos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aros_acos` ;

CREATE  TABLE IF NOT EXISTS `aros_acos` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `aro_id` INT(10) NOT NULL ,
  `aco_id` INT(10) NOT NULL ,
  `_create` VARCHAR(2) NOT NULL DEFAULT '0' ,
  `_read` VARCHAR(2) NOT NULL DEFAULT '0' ,
  `_update` VARCHAR(2) NOT NULL DEFAULT '0' ,
  `_delete` VARCHAR(2) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `ARO_ACO_KEY` (`aro_id` ASC, `aco_id` ASC) ,
  INDEX `fk_aros_acos_aros1` (`aro_id` ASC) ,
  INDEX `fk_aros_acos_acos1` (`aco_id` ASC) ,
  UNIQUE INDEX `idx_aros_acos_aro_id_aco_id` (`aro_id` ASC, `aco_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `instructors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `instructors` ;

CREATE  TABLE IF NOT EXISTS `instructors` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `program_id` INT(10) NULL ,
  `account_id` INT(10) NOT NULL ,
  `approved` TINYINT NOT NULL DEFAULT 0 ,
  `enabled` TINYINT NOT NULL DEFAULT 1 ,
  `pending` TINYINT NOT NULL DEFAULT 1 ,
  `experience` MEDIUMTEXT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `provider_instructor` (`account_id` ASC, `program_id` ASC) ,
  INDEX `fk_instructors_accounts1_idx` (`account_id` ASC) ,
  INDEX `fk_instructors_training_providers1` (`program_id` ASC) ,
  CONSTRAINT `fk_instructors_accounts1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_instructors_programs1`
    FOREIGN KEY (`program_id` )
    REFERENCES `programs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `course_sections`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course_sections` ;

CREATE  TABLE IF NOT EXISTS `course_sections` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `course_catalog_id` INT(10) NULL ,
  `address_id` INT(10) NULL ,
  `training_provider_id` INT(10) NOT NULL ,
  `account_id` INT(10) NULL ,
  `label` VARCHAR(255) NOT NULL ,
  `course_section_number` VARCHAR(50) NULL ,
  `start_date` DATETIME NULL ,
  `end_date` DATETIME NULL ,
  `enabled` TINYINT NULL DEFAULT 0 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_course_sections_addresses1_idx` (`address_id` ASC) ,
  INDEX `fk_course_sections_training_providers1_idx` (`training_provider_id` ASC) ,
  INDEX `fk_course_sections_course_catalogs1_idx` (`course_catalog_id` ASC) ,
  INDEX `fk_course_sections_accounts1_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_course_sections_addresses1`
    FOREIGN KEY (`address_id` )
    REFERENCES `addresses` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_sections_training_providers1`
    FOREIGN KEY (`training_provider_id` )
    REFERENCES `training_providers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_sections_course_catalogs1`
    FOREIGN KEY (`course_catalog_id` )
    REFERENCES `course_catalogs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_sections_accounts1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `course_rosters`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course_rosters` ;

CREATE  TABLE IF NOT EXISTS `course_rosters` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `course_section_id` INT(10) NOT NULL ,
  `account_id` INT(10) NOT NULL ,
  `student_number` VARCHAR(50) NULL DEFAULT NULL ,
  `completed` TINYINT NOT NULL DEFAULT 0 ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_course_rosters_accounts2` (`account_id` ASC) ,
  INDEX `fk_course_rosters_courses1` (`course_section_id` ASC) ,
  CONSTRAINT `fk_course_rosters_course_sections1`
    FOREIGN KEY (`course_section_id` )
    REFERENCES `course_sections` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_rosters_accounts1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `firm_licenses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `firm_licenses` ;

CREATE  TABLE IF NOT EXISTS `firm_licenses` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `firm_id` INT(10) NOT NULL ,
  `license_id` INT(10) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_firm_accounts_firms1` (`firm_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `group_programs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `group_programs` ;

CREATE  TABLE IF NOT EXISTS `group_programs` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NOT NULL ,
  `descr` VARCHAR(250) NULL DEFAULT NULL ,
  `home` VARCHAR(250) NULL DEFAULT '/my_account' ,
  `enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`label` ASC) ,
  INDEX `created` (`created` ASC) ,
  INDEX `modified` (`modified` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Base groups for select options for programs';


-- -----------------------------------------------------
-- Table `groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `groups` ;

CREATE  TABLE IF NOT EXISTS `groups` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `program_id` INT(10) NULL DEFAULT NULL ,
  `group_program_id` INT(10) NULL DEFAULT NULL ,
  `label` VARCHAR(250) NOT NULL ,
  `descr` VARCHAR(255) NULL DEFAULT NULL COMMENT 'A brief descr ofnthe account group.' ,
  `home` VARCHAR(250) NULL DEFAULT '/my_account' ,
  `enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `created` (`created` ASC) ,
  INDEX `fk_groups_programs` (`program_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `history`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `history` ;

CREATE  TABLE IF NOT EXISTS `history` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NOT NULL ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `action` VARCHAR(45) NOT NULL ,
  `old_data` LONGTEXT NOT NULL ,
  `new_data` LONGTEXT NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_history_accounts1` (`account_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `license_audits`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_audits` ;

CREATE  TABLE IF NOT EXISTS `license_audits` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `license_id` INT(10) NOT NULL ,
  `created` DATETIME NULL COMMENT '	' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_license_audits_licenses1_idx` (`license_id` ASC) ,
  CONSTRAINT `fk_license_audits_licenses1`
    FOREIGN KEY (`license_id` )
    REFERENCES `licenses` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `screening_questions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `screening_questions` ;

CREATE  TABLE IF NOT EXISTS `screening_questions` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `license_type_id` INT(10) NOT NULL ,
  `question` VARCHAR(300) NOT NULL COMMENT 'All questions must be in YES/NO answer format.' ,
  `correct_answer` TINYINT NOT NULL DEFAULT 0 COMMENT 'YES = 1, NO = 0. Incorrect answers are flagged for review.' ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_screening_questions_license_types1_idx` (`license_type_id` ASC) ,
  CONSTRAINT `fk_screening_questions_license_types1`
    FOREIGN KEY (`license_type_id` )
    REFERENCES `license_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `screening_answers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `screening_answers` ;

CREATE  TABLE IF NOT EXISTS `screening_answers` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `application_id` INT(10) NOT NULL ,
  `screening_question_id` INT(10) NOT NULL ,
  `answer` TINYINT NULL DEFAULT 0 COMMENT 'YES = 1, NO = 0' ,
  `comment` TEXT NULL ,
  `modified` DATETIME NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `lic_screening` (`application_id` ASC, `screening_question_id` ASC) ,
  INDEX `fk_license_screening_questions_licenses1` (`application_id` ASC) ,
  INDEX `fk_license_screening_questions_screening_questions1` (`screening_question_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `payment_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `payment_types` ;

CREATE  TABLE IF NOT EXISTS `payment_types` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `payments` ;

CREATE  TABLE IF NOT EXISTS `payments` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NOT NULL ,
  `payment_type_id` INT(10) NOT NULL ,
  `identifier` VARCHAR(150) NOT NULL COMMENT 'check number\nmoney order number\nlast four of credit card\netc.' ,
  `total` DECIMAL(5,2) NOT NULL DEFAULT '0.00' ,
  `amount_paid` DECIMAL(5,2) NULL DEFAULT NULL ,
  `payment_date` DATETIME NULL DEFAULT NULL ,
  `payment_received_date` DATETIME NULL DEFAULT NULL ,
  `transaction_data` TEXT NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_transactions_accounts1` (`account_id` ASC) ,
  INDEX `fk_transactions_transaction_types1` (`payment_type_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `payment_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `payment_items` ;

CREATE  TABLE IF NOT EXISTS `payment_items` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NOT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `payment_id` INT(10) NOT NULL ,
  `label` VARCHAR(105) NOT NULL ,
  `fee` DECIMAL(5,2) NOT NULL DEFAULT '0.00' ,
  `fee_type` VARCHAR(45) NOT NULL ,
  `fee_data` TEXT NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_lined_items_transactions1` (`payment_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `notes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `notes` ;

CREATE  TABLE IF NOT EXISTS `notes` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NULL DEFAULT NULL ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `note` LONGTEXT NOT NULL ,
  `access` VARCHAR(45) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_notes_accounts1` (`account_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `pages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pages` ;

CREATE  TABLE IF NOT EXISTS `pages` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `program_id` INT(10) NOT NULL ,
  `title` VARCHAR(150) NOT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `meta_title` VARCHAR(65) NULL DEFAULT NULL ,
  `meta_descr` VARCHAR(150) NULL DEFAULT NULL ,
  `meta_keywords` VARCHAR(150) NULL DEFAULT NULL ,
  `enabled` TINYINT NOT NULL DEFAULT 1 ,
  `content` TEXT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_pages_programs1` (`program_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `uploads`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uploads` ;

CREATE  TABLE IF NOT EXISTS `uploads` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `parent_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `parent_object` VARCHAR(45) NULL DEFAULT NULL ,
  `parent_key` INT(10) NULL DEFAULT NULL COMMENT 'An ID to group all related documents together (supporting documents).' ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_key` INT(10) NULL DEFAULT NULL ,
  `identifier` VARCHAR(45) NULL DEFAULT NULL COMMENT 'Multi purpose field. Can be used to identify document types. e.g. profile pic, degree, resume, inspection, etc.' ,
  `label` VARCHAR(150) NOT NULL COMMENT 'Shown as link text for uploaded file.' ,
  `file_path` VARCHAR(255) NOT NULL ,
  `file_name` VARCHAR(500) NOT NULL COMMENT 'Original file name.' ,
  `file_size` INT(10) NOT NULL ,
  `file_ext` VARCHAR(45) NOT NULL ,
  `mime_type` VARCHAR(45) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `work_experiences`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `work_experiences` ;

CREATE  TABLE IF NOT EXISTS `work_experiences` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NOT NULL ,
  `employer` VARCHAR(150) NOT NULL ,
  `position` VARCHAR(150) NOT NULL ,
  `supervisor_name` VARCHAR(90) NULL ,
  `supervisor_phone` VARCHAR(45) NULL ,
  `start_date` DATE NULL DEFAULT NULL ,
  `end_date` DATE NULL ,
  `hrs_per_week` INT(10) NULL ,
  `descr` VARCHAR(500) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `managers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `managers` ;

CREATE  TABLE IF NOT EXISTS `managers` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `account_id` INT(10) NOT NULL ,
  `primary_flag` TINYINT(1) NULL DEFAULT 0 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `elements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elements` ;

CREATE  TABLE IF NOT EXISTS `elements` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NOT NULL COMMENT 'Default label used as block heading in view.' ,
  `description` TEXT NULL ,
  `element_plugin` VARCHAR(45) NULL ,
  `element` VARCHAR(250) NOT NULL COMMENT 'my_element, element_dir/my_element' ,
  `foreign_plugin` VARCHAR(45) NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `data_keys` VARCHAR(45) NULL COMMENT 'Where the data resides in the license data array. Used to validate data exists before submitting the application.' ,
  `modified` DATETIME NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `element_license_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `element_license_types` ;

CREATE  TABLE IF NOT EXISTS `element_license_types` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `license_type_id` INT(10) NOT NULL ,
  `element_id` INT(10) NOT NULL ,
  `label` VARCHAR(150) NULL COMMENT 'Overwrites the default label in elements table.' ,
  `order` INT(10) NULL DEFAULT 999 ,
  `initial_required` TINYINT(1) NULL DEFAULT 0 ,
  `renewal_required` TINYINT(1) NULL DEFAULT 0 ,
  `conversion_required` TINYINT(1) NULL DEFAULT 0 ,
  `modified` DATETIME NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_license_type_elements_view_elements1_idx` (`element_id` ASC) ,
  INDEX `fk_element_license_types_license_types1_idx` (`license_type_id` ASC) ,
  CONSTRAINT `fk_license_type_elements_view_elements1`
    FOREIGN KEY (`element_id` )
    REFERENCES `elements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_element_license_types_license_types1`
    FOREIGN KEY (`license_type_id` )
    REFERENCES `license_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `variants`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `variants` ;

CREATE  TABLE IF NOT EXISTS `variants` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NOT NULL ,
  `abbr` VARCHAR(6) NOT NULL COMMENT 'Variant abbreviation or acronym. Optional use in license number format.' ,
  `descr` VARCHAR(250) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `license_type_variants`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_type_variants` ;

CREATE  TABLE IF NOT EXISTS `license_type_variants` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `license_type_id` INT(10) NOT NULL ,
  `variant_id` INT(10) NOT NULL ,
  `modified` DATETIME NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_license_type_variants_license_types1_idx` (`license_type_id` ASC) ,
  INDEX `fk_license_type_variants_variants1_idx` (`variant_id` ASC) ,
  CONSTRAINT `fk_license_type_variants_license_types1`
    FOREIGN KEY (`license_type_id` )
    REFERENCES `license_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_license_type_variants_variants1`
    FOREIGN KEY (`variant_id` )
    REFERENCES `variants` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `questions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `questions` ;

CREATE  TABLE IF NOT EXISTS `questions` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `license_type_id` INT(10) NOT NULL ,
  `question` VARCHAR(255) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_questions_license_types1_idx` (`license_type_id` ASC) ,
  CONSTRAINT `fk_questions_license_types1`
    FOREIGN KEY (`license_type_id` )
    REFERENCES `license_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `question_answers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question_answers` ;

CREATE  TABLE IF NOT EXISTS `question_answers` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `application_id` INT(10) NOT NULL ,
  `question_id` INT(10) NOT NULL ,
  `answer` TEXT NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_question_answers_questions1_idx` (`question_id` ASC) ,
  CONSTRAINT `fk_question_answers_questions1`
    FOREIGN KEY (`question_id` )
    REFERENCES `questions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `course_locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course_locations` ;

CREATE  TABLE IF NOT EXISTS `course_locations` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `training_provider_id` INT(10) NOT NULL ,
  `enabled` TINYINT NOT NULL DEFAULT 1 ,
  `contact_person` VARCHAR(255) NOT NULL ,
  `contact_phone` VARCHAR(45) NOT NULL ,
  `created` DATETIME NULL COMMENT '	' ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_course_locations_training_providers1_idx` (`training_provider_id` ASC) ,
  CONSTRAINT `fk_course_locations_training_providers1`
    FOREIGN KEY (`training_provider_id` )
    REFERENCES `training_providers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contacts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contacts` ;

CREATE  TABLE IF NOT EXISTS `contacts` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `account_id` INT(10) NULL COMMENT 'May or may not associated to an account.' ,
  `title` VARCHAR(45) NULL ,
  `first_name` VARCHAR(45) NOT NULL ,
  `last_name` VARCHAR(45) NOT NULL ,
  `phone` VARCHAR(11) NULL DEFAULT NULL ,
  `ext` VARCHAR(4) NULL DEFAULT NULL ,
  `email` VARCHAR(250) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `degrees`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `degrees` ;

CREATE  TABLE IF NOT EXISTS `degrees` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `degree` VARCHAR(100) NOT NULL ,
  `order` INT(3) NULL DEFAULT 999 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `education_degrees`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `education_degrees` ;

CREATE  TABLE IF NOT EXISTS `education_degrees` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NOT NULL ,
  `degree_id` INT(10) NOT NULL ,
  `other` VARCHAR(150) NULL ,
  `school_name` VARCHAR(150) NULL ,
  `start_date` DATE NULL ,
  `end_date` DATE NULL ,
  `certified_date` DATE NULL ,
  `highest_earned` TINYINT(1) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_education_education_degrees1_idx` (`degree_id` ASC) ,
  INDEX `fk_education_accounts1_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_education_education_degrees1`
    FOREIGN KEY (`degree_id` )
    REFERENCES `degrees` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_education_accounts1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `program_certificates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `program_certificates` ;

CREATE  TABLE IF NOT EXISTS `program_certificates` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `program_id` INT(10) NOT NULL ,
  `certificate` VARCHAR(150) NOT NULL ,
  `order` INT(10) NULL DEFAULT 999 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_program_certificates_programs1_idx` (`program_id` ASC) ,
  CONSTRAINT `fk_program_certificates_programs1`
    FOREIGN KEY (`program_id` )
    REFERENCES `programs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `education_certificates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `education_certificates` ;

CREATE  TABLE IF NOT EXISTS `education_certificates` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NOT NULL ,
  `program_certificate_id` INT(10) NOT NULL ,
  `other` VARCHAR(150) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_education_certificates_accounts1_idx` (`account_id` ASC) ,
  INDEX `fk_education_certificates_program_certificates1_idx` (`program_certificate_id` ASC) ,
  CONSTRAINT `fk_education_certificates_accounts1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_education_certificates_program_certificates1`
    FOREIGN KEY (`program_certificate_id` )
    REFERENCES `program_certificates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fees`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fees` ;

CREATE  TABLE IF NOT EXISTS `fees` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(100) NOT NULL ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_key` INT(10) NULL DEFAULT NULL ,
  `application_type_id` INT NOT NULL ,
  `fee` DECIMAL(5,2) NULL DEFAULT NULL ,
  `apply_tax` TINYINT(1) NULL DEFAULT '1' COMMENT 'Exclude this fee from any tax calculations.' ,
  `removable` TINYINT(1) NULL DEFAULT '0' COMMENT 'Can this payment be removed from the shopping cart?' ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 18
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `shopping_carts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `shopping_carts` ;

CREATE  TABLE IF NOT EXISTS `shopping_carts` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NOT NULL ,
  `fee_id` INT(10) NOT NULL ,
  `label` VARCHAR(150) NULL DEFAULT NULL ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_key` INT NULL DEFAULT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_shopping_cart_fees1_idx` (`fee_id` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `states`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `states` ;

CREATE  TABLE IF NOT EXISTS `states` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `state` VARCHAR(50) NOT NULL ,
  `abbr` VARCHAR(2) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `counties`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `counties` ;

CREATE  TABLE IF NOT EXISTS `counties` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `county` VARCHAR(50) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fee_modifiers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fee_modifiers` ;

CREATE  TABLE IF NOT EXISTS `fee_modifiers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `fee_id` INT(11) NOT NULL ,
  `label` VARCHAR(45) NOT NULL ,
  `type` VARCHAR(10) NULL DEFAULT 'dollar' COMMENT 'Either \'dollar\' or \'percent\'' ,
  `fee` FLOAT(11) NULL DEFAULT '0' ,
  `start_range` VARCHAR(45) NULL DEFAULT NULL ,
  `end_range` VARCHAR(45) NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `instructor_assignments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `instructor_assignments` ;

CREATE  TABLE IF NOT EXISTS `instructor_assignments` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `training_provider_id` INT(10) NOT NULL ,
  `account_id` INT(10) NOT NULL ,
  `approved` TINYINT(4) NOT NULL DEFAULT '0' ,
  `approved_date` DATETIME NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `unique` (`training_provider_id` ASC, `account_id` ASC) ,
  INDEX `fk_instructor_training_providers_accounts1_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_instructor_training_providers_accounts1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `dwelling_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dwelling_types` ;

CREATE  TABLE IF NOT EXISTS `dwelling_types` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NULL ,
  `enabled` TINYINT(1) NULL DEFAULT 1 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `abatement_statuses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `abatement_statuses` ;

CREATE  TABLE IF NOT EXISTS `abatement_statuses` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `abatements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `abatements` ;

CREATE  TABLE IF NOT EXISTS `abatements` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NULL ,
  `slug` VARCHAR(45) NULL ,
  `abatement_status_id` INT(10) NOT NULL ,
  `abatement_number` VARCHAR(45) NULL ,
  `license_id` INT(10) NULL ,
  `firm_id` INT(10) NULL ,
  `dwelling_type_id` INT(10) NULL ,
  `dwelling_year_built` CHAR(4) NULL ,
  `work_description` TEXT NULL ,
  `date_received` DATE NULL ,
  `date_submitted` DATE NULL DEFAULT NULL ,
  `revision_count` INT NOT NULL DEFAULT 0 ,
  `enabled` TINYINT(1) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `abatement_phases`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `abatement_phases` ;

CREATE  TABLE IF NOT EXISTS `abatement_phases` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `abatement_id` INT(10) NULL ,
  `begin_date` DATE NULL ,
  `end_date` DATE NULL ,
  `enabled` TINYINT(1) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `output_document_batches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `output_document_batches` ;

CREATE  TABLE IF NOT EXISTS `output_document_batches` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NULL DEFAULT NULL ,
  `batch_date` DATETIME NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `label` (`label` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `output_document_batch_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `output_document_batch_items` ;

CREATE  TABLE IF NOT EXISTS `output_document_batch_items` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `output_document_type` VARCHAR(150) NULL DEFAULT NULL ,
  `output_document_batch_id` INT(10) NULL DEFAULT NULL ,
  `foreign_plugin` VARCHAR(45) NULL ,
  `foreign_obj` VARCHAR(45) NULL ,
  `foreign_key` VARCHAR(45) NULL ,
  `label` VARCHAR(255) NULL DEFAULT NULL ,
  `template_data` TEXT NULL DEFAULT NULL ,
  `batch_date` DATETIME NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_output_document_batch_items_output_document_batches1_idx` (`output_document_batch_id` ASC) ,
  INDEX `type` (`output_document_type` ASC) ,
  CONSTRAINT `fk_output_document_batch_items_output_document_batches1`
    FOREIGN KEY (`output_document_batch_id` )
    REFERENCES `output_document_batches` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `third_party_tests`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `third_party_tests` ;

CREATE  TABLE IF NOT EXISTS `third_party_tests` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(100) NOT NULL ,
  `entity` VARCHAR(45) NULL ,
  `interim` INT(3) NULL DEFAULT NULL ,
  `enabled` TINYINT(4) NOT NULL DEFAULT 0 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `third_party_test_attempts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `third_party_test_attempts` ;

CREATE  TABLE IF NOT EXISTS `third_party_test_attempts` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `course_roster_id` INT(10) NOT NULL ,
  `third_party_test_id` INT(10) NOT NULL ,
  `date` DATETIME NOT NULL ,
  `score` FLOAT NULL ,
  `pass` TINYINT(1) NULL DEFAULT 0 ,
  `created` DATETIME NULL COMMENT '	' ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_third_party_tests_licenses1_idx` (`course_roster_id` ASC) ,
  INDEX `fk_third_party_test_attempts_third_party_tests1_idx` (`third_party_test_id` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `reciprocals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reciprocals` ;

CREATE  TABLE IF NOT EXISTS `reciprocals` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `application_id` INT NOT NULL ,
  `provider` VARCHAR(150) NOT NULL ,
  `course_title` VARCHAR(150) NOT NULL ,
  `hours` INT NULL DEFAULT 0 ,
  `pass` TINYINT(1) NULL DEFAULT 0 ,
  `score` INT NULL ,
  `start_date` DATE NOT NULL ,
  `completed_date` DATE NOT NULL ,
  `expire_date` DATE NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_reciprocals_applications1_idx` (`application_id` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `work_experience_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `work_experience_types` ;

CREATE  TABLE IF NOT EXISTS `work_experience_types` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NOT NULL ,
  `order` INT(3) NULL DEFAULT 999 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `expirations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `expirations` ;

CREATE  TABLE IF NOT EXISTS `expirations` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `parent_plugin` VARCHAR(100) NOT NULL ,
  `parent_obj` VARCHAR(100) NOT NULL ,
  `parent_key` INT(10) NOT NULL ,
  `foreign_plugin` VARCHAR(100) NULL ,
  `foreign_obj` VARCHAR(100) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `expire_date` DATE NOT NULL ,
  `label` VARCHAR(45) NOT NULL ,
  `descr` VARCHAR(200) NULL ,
  `action` VARCHAR(250) NULL COMMENT 'Cake URL path using %s for dynamic values.' ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `refresher_dates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `refresher_dates` ;

CREATE  TABLE IF NOT EXISTS `refresher_dates` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `account_id` INT(10) NOT NULL ,
  `course_catalog_id` INT(10) NOT NULL ,
  `refresher_date` DATE NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_refresher_dates_course_catalogs1_idx` (`course_catalog_id` ASC) ,
  INDEX `fk_refresher_dates_accounts1_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_refresher_dates_course_catalogs1`
    FOREIGN KEY (`course_catalog_id` )
    REFERENCES `course_catalogs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_refresher_dates_accounts1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `course_catalogs_license_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course_catalogs_license_types` ;

CREATE  TABLE IF NOT EXISTS `course_catalogs_license_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `course_catalog_id` INT(10) NOT NULL ,
  `license_type_id` INT(10) NOT NULL ,
  `initial` TINYINT(1) NULL ,
  `renewal` TINYINT(1) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_course_catalog_license_types_license_types1_idx` (`license_type_id` ASC) ,
  INDEX `fk_course_catalog_license_types_course_catalogs1_idx` (`course_catalog_id` ASC) ,
  CONSTRAINT `fk_course_catalog_license_types_license_types1`
    FOREIGN KEY (`license_type_id` )
    REFERENCES `license_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_catalog_license_types_course_catalogs1`
    FOREIGN KEY (`course_catalog_id` )
    REFERENCES `course_catalogs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `audits`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `audits` ;

CREATE  TABLE IF NOT EXISTS `audits` (
  `id` BIGINT(11) NOT NULL AUTO_INCREMENT ,
  `event` VARCHAR(45) NOT NULL ,
  `model` VARCHAR(45) NOT NULL ,
  `entity_id` INT(11) NOT NULL ,
  `json_object` TEXT NULL ,
  `description` TEXT NULL ,
  `source_id` INT(11) NULL DEFAULT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `active` TINYINT(1) NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `audit_deltas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `audit_deltas` ;

CREATE  TABLE IF NOT EXISTS `audit_deltas` (
  `id` BIGINT(11) NOT NULL AUTO_INCREMENT ,
  `audit_id` BIGINT(11) NOT NULL ,
  `property_name` VARCHAR(255) NOT NULL ,
  `old_value` LONGTEXT NULL ,
  `new_value` LONGTEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `license_gaps`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_gaps` ;

CREATE  TABLE IF NOT EXISTS `license_gaps` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `license_id` INT NOT NULL ,
  `application_id` INT NOT NULL ,
  `effective_date` DATE NULL ,
  `previous_application_id` INT NOT NULL ,
  `previous_expire_date` DATE NULL ,
  `diff_days` INT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `license_variants`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_variants` ;

CREATE  TABLE IF NOT EXISTS `license_variants` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `license_id` INT(10) NULL ,
  `variant_id` INT(10) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `program_plugins`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `program_plugins` ;

CREATE  TABLE IF NOT EXISTS `program_plugins` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `program_id` INT(10) NULL DEFAULT NULL ,
  `plugin_id` INT(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `register_plugins`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `register_plugins` ;

CREATE  TABLE IF NOT EXISTS `register_plugins` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NULL ,
  `descr` TEXT NULL ,
  `plugin` VARCHAR(45) NULL ,
  `path` VARCHAR(125) NULL ,
  `uri` VARCHAR(125) NULL ,
  `home` VARCHAR(125) NULL ,
  `enable` TINYINT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `configurations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `configurations` ;

CREATE  TABLE IF NOT EXISTS `configurations` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `program_id` INT NULL ,
  `plugin` VARCHAR(45) NULL ,
  `name` VARCHAR(127) NOT NULL ,
  `value` VARCHAR(255) NULL ,
  `field_type` VARCHAR(45) NULL ,
  `options` TEXT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `practical_work_percentage_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `practical_work_percentage_types` ;

CREATE  TABLE IF NOT EXISTS `practical_work_percentage_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NULL ,
  `enabled` TINYINT(1) NULL DEFAULT 1 ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `practical_work_percentages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `practical_work_percentages` ;

CREATE  TABLE IF NOT EXISTS `practical_work_percentages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `practical_work_percentage_type_id` INT(11) NOT NULL ,
  `account_id` INT(11) NOT NULL ,
  `percentage` INT(3) NULL DEFAULT NULL ,
  `descr` VARCHAR(150) NULL DEFAULT NULL ,
  `enabled` TINYINT(1) NULL DEFAULT 1 ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_practical_work_percentage_type1_idx` (`practical_work_percentage_type_id` ASC) ,
  INDEX `fk_account1_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_practical_work_percentage_type1`
    FOREIGN KEY (`practical_work_percentage_type_id` )
    REFERENCES `practical_work_percentage_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account1`
    FOREIGN KEY (`account_id` )
    REFERENCES `accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `practical_work_experiences`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `practical_work_experiences` ;

CREATE  TABLE IF NOT EXISTS `practical_work_experiences` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `account_id` INT NULL ,
  `practical_work_experience_type_id` INT NULL ,
  `months` INT NULL ,
  `description` VARCHAR(255) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `practical_work_experience_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `practical_work_experience_types` ;

CREATE  TABLE IF NOT EXISTS `practical_work_experience_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `program_id` INT NULL ,
  `label` VARCHAR(255) NULL ,
  `order` INT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `third_party_test_assignments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `third_party_test_assignments` ;

CREATE  TABLE IF NOT EXISTS `third_party_test_assignments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `course_catalog_id` INT(11) NOT NULL ,
  `license_type_id` INT(11) NOT NULL ,
  `third_party_test_id` INT(11) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_course_catalog1_idx` (`course_catalog_id` ASC) ,
  INDEX `fk_third_party_test1_idx` (`third_party_test_id` ASC) ,
  INDEX `license_type` (`license_type_id` ASC) ,
  CONSTRAINT `fk_course_catalog1`
    FOREIGN KEY (`course_catalog_id` )
    REFERENCES `course_catalogs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_third_party_test1`
    FOREIGN KEY (`third_party_test_id` )
    REFERENCES `third_party_tests` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `work_experiences_work_experience_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `work_experiences_work_experience_types` ;

CREATE  TABLE IF NOT EXISTS `work_experiences_work_experience_types` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `work_experience_id` INT(10) NOT NULL ,
  `work_experience_type_id` INT(10) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `work_exp_type` (`work_experience_type_id` ASC) ,
  INDEX `work_exp` (`work_experience_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `course_catalogs_course_catalogs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course_catalogs_course_catalogs` ;

CREATE  TABLE IF NOT EXISTS `course_catalogs_course_catalogs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `course_catalog_id` INT NULL ,
  `parent_course_catalog_id` VARCHAR(45) NULL DEFAULT NULL ,
  `replaced_course_catalog_id` VARCHAR(45) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `references`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `references` ;

CREATE  TABLE IF NOT EXISTS `references` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `account_id` INT NULL ,
  `notes` TEXT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `other_licenses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `other_licenses` ;

CREATE  TABLE IF NOT EXISTS `other_licenses` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NOT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(11) NOT NULL ,
  `label` VARCHAR(150) NOT NULL ,
  `jurisdiction` VARCHAR(150) NOT NULL ,
  `license_number` VARCHAR(45) NOT NULL ,
  `issue_date` DATE NOT NULL ,
  `expire_date` DATE NOT NULL ,
  `active` TINYINT(1) NULL DEFAULT 1 ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  `obtained_by_exam` TINYINT(4) NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contractors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contractors` ;

CREATE  TABLE IF NOT EXISTS `contractors` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `license_id` INT NOT NULL ,
  `crn` VARCHAR(45) NULL ,
  `crn_expire_date` DATE NULL ,
  `fin` VARCHAR(45) NULL ,
  `fin_last_four` VARCHAR(45) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `insurance_informations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `insurance_informations` ;

CREATE  TABLE IF NOT EXISTS `insurance_informations` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(150) NULL DEFAULT NULL ,
  `expire_date` DATE NULL DEFAULT NULL ,
  `insurance_amount` DECIMAL(10,2) NULL DEFAULT '0' ,
  `foreign_plugin` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_obj` VARCHAR(45) NULL DEFAULT NULL ,
  `foreign_key` VARCHAR(45) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pending_payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pending_payments` ;

CREATE  TABLE IF NOT EXISTS `pending_payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `transaction_id` VARCHAR(150) NOT NULL ,
  `account_id` INT(11) NOT NULL ,
  `payment_type_id` INT(11) NOT NULL ,
  `identifier` VARCHAR(150) NOT NULL ,
  `total` DECIMAL(5,2) NULL DEFAULT NULL ,
  `amount_paid` DECIMAL(5,2) NULL DEFAULT NULL ,
  `payment_date` DATETIME NULL DEFAULT NULL ,
  `payment_received_date` DATETIME NULL DEFAULT NULL ,
  `transaction_data` TEXT NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `pending_payment_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pending_payment_items` ;

CREATE  TABLE IF NOT EXISTS `pending_payment_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `foreign_plugin` VARCHAR(45) NOT NULL ,
  `foreign_obj` VARCHAR(45) NOT NULL ,
  `foreign_key` INT(11) NOT NULL ,
  `pending_payment_id` INT(11) NOT NULL ,
  `label` VARCHAR(150) NOT NULL ,
  `fee` DECIMAL(5,2) NOT NULL DEFAULT '0.00' ,
  `fee_type` VARCHAR(45) NOT NULL ,
  `fee_data` TEXT NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `groups_payment_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `groups_payment_types` ;

CREATE  TABLE IF NOT EXISTS `groups_payment_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_id` INT NOT NULL ,
  `payment_type_id` INT NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `license_types_license_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `license_types_license_types` ;

CREATE  TABLE IF NOT EXISTS `license_types_license_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `parent_license_type_id` INT NULL ,
  `license_type_id` INT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `application_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `application_types` ;

CREATE  TABLE IF NOT EXISTS `application_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `app_lic_credit_hours`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_lic_credit_hours` ;

CREATE  TABLE IF NOT EXISTS `app_lic_credit_hours` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `application_type_id` INT NOT NULL ,
  `license_type_id` INT NOT NULL ,
  `code_hours` INT NOT NULL ,
  `total_hours` INT NOT NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `billing_items_report`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `billing_items_report` (`id` INT, `license_type_id` INT, `label` INT, `paid_date` INT, `issued_date` INT, `application_type_id` INT);

-- -----------------------------------------------------
-- View `billing_items_report`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `billing_items_report` ;
DROP TABLE IF EXISTS `billing_items_report`;
CREATE  OR REPLACE VIEW `billing_items_report` AS 
SELECT `Application`.`id` AS `id`,`LicenseType`.`id` AS `license_type_id`,`LicenseType`.`label` AS `label`,`Application`.`paid_date` AS `paid_date`,`License`.`issued_date` AS `issued_date`,`Application`.`application_type_id` AS `application_type_id` 
FROM ((`applications` `Application` LEFT JOIN `licenses` `License` ON ((`Application`.`license_id` = `License`.`id`))) LEFT JOIN `license_types` `LicenseType` ON ((`License`.`license_type_id` = `LicenseType`.`id`))) WHERE (`Application`.`paid_date` IS NOT NULL) ORDER BY `Application`.`paid_date`;
;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
