# 1.3.0 DPS data updates

# Trello Fri 1 (8) - Updating the agency-specific descrpitions of each of the application sections

Update elements
set description = 'In this section, you must enter at least one address.  You will have the ability to classify each address entered as either Home, Business, Mailing or Other.  You will select a primary address which will be used for all correspondence with the agency.'
where label = 'Addresses';

Update elements
set description = 'In this section, you will need to search for and associate to a Master license in order to proceed.'
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
set description = 'Please indicate whether or not you have completed a two-year post high school course in electrical wiring and/or whether you have completed a four-year or five-year apprentice electrician program.'
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
set description = 'Please list the current license(s) you have with any jurisdiction other than the State of Iowa Electrical Examining Board.'
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
set description = 'In this section, please provide your verifiable work experience.  You will be able to submit multiple work experience entries.
If you are requesting an "A" type license through an existing city license, and you took a proctored test, and the test is on the list of "approved city exams", you will need to go to our website, download the forms and have it filled out and notarized. The forms and the list of approved cities can be found at http://www.dps.state.ia.us/fm/electrician/forms/forms.shtml'
where label = 'Work Experience';

Update elements
set description = 'No special instructions.'
where label = 'Licensed by Exam';

Update elements
set description = 'Provide names, addresses, and phone numbers of three (3) persons or firms, preferably in the electrical industry, to be used as references. These can be Supervisors, instructors, mentors, co-workers, supply houses, or clients.'
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


