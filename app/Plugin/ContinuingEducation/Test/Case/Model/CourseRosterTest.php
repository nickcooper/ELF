<?php

App::uses('CourseRoster', 'ContinuingEducation.Model');

class CourseRosterTest extends CakeTestCase
{

    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'contact',
        'plugin.accounts.account',
        'plugin.accounts.education_degree',
        'plugin.accounts.degree',
        'plugin.licenses.insurance_information',
        'plugin.accounts.practical_work_experience',
        'plugin.accounts.practical_work_experience_type',
        'plugin.accounts.practical_work_percentage',
        'plugin.accounts.practical_work_percentage_type',
        'plugin.accounts.other_license',
        'plugin.accounts.reference',
        'plugin.accounts.work_experience',
        'plugin.address_book.address',
        'plugin.continuing_education.course_catalog',
        'plugin.continuing_education.course_catalogs_license_type',
        'plugin.continuing_education.course_roster',
        'plugin.continuing_education.course_section',
        'plugin.continuing_education.exam_score',
        'plugin.continuing_education.training_provider',
        'plugin.licenses.application',
        'plugin.licenses.app_lic_credit_hour',
        'plugin.licenses.license',
        'plugin.licenses.license_type',
        'plugin.licenses.reciprocal',
        'plugin.uploads.upload',
    );

    /**
     * Test CourseRoster::complete()
     *
     * @return void
     * @access public
     */
    public function testComplete()
    {
        // initialize CourseRoster model
        $this->CourseRoster = ClassRegistry::init('ContinuingEducation.CourseRoster');

        // initialize error message
        $message = null;

        // find a test record from the fixture
        $record = $this->CourseRoster->find('first', array('conditions' => array('completed' => false)));

        // verify record
        $message = 'testComplete did not find a roster record with an incomplete course';
        $this->assertEquals(0, $record['CourseRoster']['completed'], $message);

        // set the id
        $id = $record['CourseRoster']['id'];
        $this->CourseRoster->id = $id;

        //$this->CourseRoster->complete($id);

        // update the course to be completed
        $this->CourseRoster->saveField('completed', 1);

        // verify only one row was updated
        $message = 'testComplete did not update the roster record to complete the course';
        $this->assertEquals(1, $this->CourseRoster->getAffectedRows(), $message);

        // reload the test record
        $record = $this->CourseRoster->findById($id);

        // verify that the test record was updated
        $message = 'testComplete did not update the test record as expected';
        $this->assertEquals(1, $record['CourseRoster']['completed'], $message);
    }

    public function testGetCourseExpiration()
    {
        // initialize CourseRoster model
        $this->CourseRoster = ClassRegistry::init('ContinuingEducation.CourseRoster');

        $this->License = ClassRegistry::init('Licenses.License');
        $license = $this->License->getLicenseById(
            1,
            array(
                'Application' => array(
                    'Reciprocal'
                ),
                'LicenseType' => array(
                    'AppLicCreditHour'
                )
            )
        );
        //print "<pre>"; print_r($license); print "</pre>"; exit;

        $course_expire_date = $this->CourseRoster->getCourseExpiration(1);
        $message = 'getCourseExpiration did not return the course date expected';
        $this->assertEquals('2016-08-09 14:16:06', $course_expire_date, $message);

        $course_expire_date = $this->CourseRoster->getReciprocalExpiration(1);
        $message = 'getReciprocalExpiration did not return the reciprocal date expected';
        $this->assertEquals('2015-08-10', $course_expire_date, $message);

        $course_expire_date = $this->CourseRoster->getCourseRefresher(1);
        $message = 'getCourseRefresher did not return the refresher date expected';
        $this->assertEquals('2015-08-10', $course_expire_date, $message);
    }
}