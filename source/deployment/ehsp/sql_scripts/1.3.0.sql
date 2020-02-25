# Trello Fri 1 (8) - Updating the agency-specific descrpitions of each of the application sections

Update elements
set description = 'In this section, you must enter at least one address.  You will have the ability to classify each address entered as either Home, Business, Mailing or Other.  You will select a primary address which will be used for all correspondence with the agency.'
where label = 'Addresses';

Update elements
set description = 'In this section, you will have the ability to search for and associate to Individual Licenses that are in the system.'
where label = 'Associated Licensees';

Update elements
set description = 'No special instructions.'
where label = 'Contact Information';

Update elements
set description = 'The Courses listed are courses that were provided to the agency by training providers.'
where label = 'Course Information';

Update elements
set description = 'No special instructions.'
where label = 'Course Locations';

Update elements
set description = 'The Courses listed are courses that were provided to the agency by training providers.'
where label = 'Courses';

Update elements
set description = 'Please provide your highest level of education attained.  You can upload your diploma, degree or certificate for each education level attained.  Please indicate the highest level attained.'
where label = 'Education';

Update elements
set description = 'In this section, you will have the ability to search for and associate to Firm Licenses that are in the system.'
where label = 'Firm Information';

Update elements
set description = 'No special instructions.'
where label = 'General Questions';

Update elements
set description = 'No special instructions.'
where label = 'Instructor Information';

Update elements
set description = 'In this section, you will find your existing licenses and will also have the ability to submit additional license applications by selecting Add New License.'
where label = 'License Information';

Update elements
set description = 'No special instructions.'
where label = 'Manager Information';

Update elements
set description = 'Please provide any other applicable license(s) related to the license being applied for.  You will be able to add multiple entries.'
where label = 'Other Professional Licenses';

Update elements
set description = 'No special instructions.'
where label = 'Personal Information';

Update elements
set description = 'In this section, you will provide the total number of months experience for all applicable categories.  This information will be used to determine if you meet the licenses requirements for licensure.'
where label = 'Practical Work Experience';

Update elements
set description = 'No special instructions.'
where label = 'Provider Information';

Update elements
set description = 'No special instructions.'
where label = 'Reciprocal Education';

Update elements
set description = 'Please provide answers to the provided questions.  If you answer Yes, you will be prompted to provide an explanation.'
where label = 'Screening Questions';

Update elements
set description = 'In this section, you will be able to upload documents related to any license being applied for.  Please provide a description of the document being uploaded.'
where label = 'Supporting Documents';

Update elements
set description = 'No special instructions.'
where label = 'Third Party Test';

Update elements
set description = 'No special instructions.'
where label = 'Variant';

Update elements
set description = 'With reference to your electrical experience, please indicate the percentage of time spent in each type of work.  Please make sure the accumulation of wiring experience entries equals 100%.'
where label = 'Wiring Experience';

Update elements
set description = 'In this section, please provide your verifiable work experience.  You will be able to submit multiple work experience entries.'
where label = 'Work Experience';

Update elements
set description = 'No special instructions.'
where label = 'Licensed by Exam';

Update elements
set description = 'Provide names, addresses, and phone numbers of three (3) persons or firms to be used as references. These can be Supervisors, instructors, mentors, co-workers, supply houses, or clients.'
where label = 'References';

Update elements
set description = 'Please enter the Iowa Division of Labor - Contractor Registration Number as well as your Federal Tax ID or Employer Identification Number and expiration date.'
where label = 'Contractor Information';

Update elements
set description = 'Please provide the Insurance Company Name, Policy Amount, Expiration Date and Uploaded policy.  You have the ability to add multiple policies in this section.'
where label = 'Insurance Information';


# Add SuperAdmin accounts for Janet and Amy to the EHSP database

INSERT INTO `accounts` (`id`, `legacy_id`, `group_id`, `username`, `password`, `title`, `label`, `first_name`,
 `last_name`, `middle_initial`, `email`, `ssn`, `ssn_last_four`, `dob`, `enabled`, `probation`,
 `perjury_acknowledged`, `no_mail`, `last_login`, `created`, `modified`)
VALUES
(NULL,NULL,2,'amy.schatz@iowaid',NULL,'Ms.','Schatz, Amy','Amy',
'Schatz',NULL,'amy.schatz@iowaid',NULL,'','1969-12-31',1,0,
NULL,0,'2013-06-26 09:12:35','2013-06-23 18:40:17','2013-06-26 09:12:35'),

(NULL,NULL,2,'JanetBergeland@iowaid',NULL,'Ms.','Bergeland, Janet','Janet',
'Bergeland',NULL,'JanetBergeland@iowaid',NULL,'','1969-12-31',1,0,
NULL,0,NULL,'2013-06-23 18:40:17','2013-06-23 18:40:17');


/* Updating refresher expiration records (Nick Cooper) */

/* Jason McIntosh */
UPDATE `expirations` SET `expire_date`='2017-06-11' WHERE `id`='59956';
UPDATE `expirations` SET `expire_date`='2017-06-11' WHERE `id`='59957';

/* Rick G. Young */
UPDATE `expirations` SET `expire_date`='2016-05-12' WHERE `id`='59958';
UPDATE `expirations` SET `expire_date`='2016-05-12' WHERE `id`='59959';

/* Rick S. Young */
UPDATE `expirations` SET `expire_date`='2016-05-19' WHERE `id`='59629';
UPDATE `expirations` SET `expire_date`='2016-05-19' WHERE `id`='59630';

/* Jeremy Suiter */
UPDATE `expirations` SET `expire_date`='2016-04-01' WHERE `id`='56369';
UPDATE `expirations` SET `expire_date`='2016-04-01' WHERE `id`='56370';

/* Brian Egemo */
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60143';
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60144';

/* Robin Foote */
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60145';
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60146';

/* Partick Harvey */
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60151';
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60152';

/* Julie Kutz */
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60157';
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60158';

/* Wayne Salgren Jr */
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60167';
UPDATE `expirations` SET `expire_date`='2016-07-06' WHERE `id`='60168';

/* Rosa Haukedahl */
UPDATE `expirations` SET `expire_date`='2015-09-28' WHERE `id`='60263';
UPDATE `expirations` SET `expire_date`='2015-09-28' WHERE `id`='60262';


/* Updating the EHSP program group name */
UPDATE `groups` SET `program_id`='4', `group_program_id`='1', `label`='Program Admin (LEAD)' WHERE `id`='3';

# ---------------------------
# Clear out bad configuration records
# ---------------------------

DELETE from `configurations` WHERE `configurations`.`name` = '';

UPDATE `configurations` SET `configurations`.`name` = 'education_form_path' WHERE `configurations`.`name` = 'Accounts.education_form_path';

INSERT INTO `configurations` (`program_id`, `plugin`, `name`, `value`, `field_type`, `options`, `created`, `modified`) 
VALUES 
(NULL, NULL, 'allow_credit_card', 'No', NULL, 'a:2:{i:0;s:3:"Yes";i:1;s:2:"No";}', NOW(), NOW()),
(NULL, NULL, 'tax_percentage', '0', NULL, NULL, NOW(), NOW()),
((SELECT `programs`.`id` FROM `programs` WHERE `programs`.`abbr` = 'LEAD'), NULL, 'education_form_path', 'education_short_form', NULL, 'a:2:{i:0;s:20:"education_short_form";i:1;s:19:"education_long_form";}', NOW(), NOW());
