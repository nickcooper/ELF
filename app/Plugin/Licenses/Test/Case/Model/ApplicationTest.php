<?php
App::uses('Application', 'Licenses.Model');
App::uses('License', 'Licenses.Model');

class ApplicationTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'contact',
        'dynamic_section',
        'plugin.Accounts.account',
        'plugin.Accounts.education_degree',
        'plugin.Accounts.degree',
        'plugin.Accounts.manager',
        'plugin.Accounts.other_license',
        'plugin.Accounts.reference',
        'plugin.Accounts.program',
        'plugin.Accounts.practical_work_experience',
        'plugin.Accounts.practical_work_experience_type',
        'plugin.Accounts.practical_work_percentage',
        'plugin.Accounts.practical_work_percentage_type',
        'plugin.Accounts.work_experience',
        'plugin.Accounts.work_experience_type',
        'plugin.Accounts.work_experiences_work_experience_type',
        'plugin.AddressBook.address',
        'plugin.ContinuingEducation.course_catalog',
        'plugin.ContinuingEducation.course_catalogs_license_type',
        'plugin.ContinuingEducation.course_roster',
        'plugin.ContinuingEducation.course_section',
        'plugin.ContinuingEducation.exam_score',
        'plugin.ContinuingEducation.training_provider',
        'plugin.Firms.firm',
        'plugin.Firms.firm_type',
        'plugin.Licenses.application',
        'plugin.licenses.application_status',
        'plugin.Licenses.application_submission',
        'plugin.Licenses.application_type',
        'plugin.Licenses.app_lic_credit_hour',
        'plugin.Licenses.contractor',
        'plugin.Licenses.element',
        'plugin.Licenses.element_license_type',
        'plugin.Licenses.insurance_information',
        'plugin.Licenses.license',
        'plugin.Licenses.license_expire_reason',
        'plugin.Licenses.license_gap',
        'plugin.Licenses.licenses_license',
        'plugin.Licenses.license_number',
        'plugin.Licenses.license_status',
        'plugin.Licenses.license_type',
        'plugin.Licenses.license_type_variant',
        'plugin.Licenses.license_variant',
        'plugin.Licenses.question',
        'plugin.Licenses.question_answer',
        'plugin.Licenses.reciprocal',
        'plugin.Licenses.screening_question',
        'plugin.Licenses.screening_answer',
        'plugin.Licenses.third_party_test',
        'plugin.Licenses.variant',
        'plugin.Payments.billing_item',
        'plugin.Payments.payment',
        'plugin.Payments.payment_item',
        'plugin.Notes.note',
        'plugin.Uploads.upload',
    );
    /*public $autoFixtures = false;*/

    public function setUp()
    {
        parent::setUp();

        $this->Application = ClassRegistry::init('Licenses.Application');
        $this->License = ClassRegistry::init('Licenses.License');
    }

    /**
     * Test Application::canSubmit()
     *
     * @return void
     * @access public
     */
    public function testCanSubmit()
    {
        $pass = false;
        $msg = null;

        try
        {
            $this->Application->canSubmit(1);
            $pass = true;
        }
        catch (Exception $e)
        {
            $pass = false;
            $msg = $e->getMessage();
        }

        $this->assertEquals(true, $pass, $msg);
    }

    /**
     * Test Application.purjury_name
     *
     * @return void
     * @access public
     */
    public function testPerjuryName()
    {
        $validated = false;
        $data = $this->Application->findById(2);
        $this->Application->set($data);
        if (!$this->Application->validates(array('fieldList' => array('perjury_name'))))
        {
            $validated = true;
        }

        $this->assertEquals(true, $validated);
    }

    /**
     * Test Application.purjury_data
     *
     * @return void
     * @access public
     */
    public function testPerjuryDate()
    {
        $validated = false;
        $data = $this->Application->findById(3);
        $this->Application->set($data);

        if (!$this->Application->validates(array('fieldList' => array('perjury_date'))))
        {
            $validated = true;
        }

        $this->assertEquals(true, $validated);
    }

    /**
     * Test Application::save()
     *
     * @return void
     * @access public
     */
    public function testSave()
    {
        $saved = false;

        $data = $this->Application->findById(1);

        // set up data for approval date test
        $old_approval_date = $data['Application']['processed_date'];
        $data['Application']['processed_date'] = '2013-11-05 10:00:00';

        $this->Application->create();

        $pass = true;
        if (!$this->Application->save($data))
        {
            debug($data['Application']['materials_received']);
            debug($this->Application->validationErrors); exit;
        }

        $this->assertTrue($pass);

        // set up approved_date test
        $data = $this->Application->findById(1);
        $new_approval_date = $data['Application']['processed_date'];

        $this->assertTrue($old_approval_date != $new_approval_date);
    }

    /**
     * Test Application::saveApproval()
     *
     * @return void
     * @access public
     */
    public function testSaveApproval()
    {
        try
        {
            // James Mckee's pending app
            $this->Application->saveApproval(4370);

            $application = $this->Application->find('first', array('conditions' => array('Application.id' => 4370)));

            $this->assertTrue($application['Application']['application_submission_id'] != 0, 'Approved application is missing submission id.');
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Test Application::saveApproval()
     *
     * @return void
     * @access public
     */
    public function testSaveApprovalMissingApplication()
    {
        try
        {
            // James Mckee's pending app
            $this->Application->delete(4370);
            $this->Application->saveApproval(4370);
        }
        catch (Exception $e)
        {
            $this->setExpectedException('Exception');
            throw $e;
        }
    }

    /**
     * Test Application::saveApproval()
     *
     * @return void
     * @access public
     */
    public function testSaveApprovalMissingLicense()
    {
        try
        {
            // James Mckee's pending app
            $this->License->deleteAll(array('License.id' => 6972), false, false);
            $this->Application->saveApproval(4370);
        }
        catch (Exception $e)
        {
            $this->setExpectedException('Exception');
            throw $e;
        }
    }

    /**
     * Test Application::getCurrentApplication()
     *
     * @return void
     * @access public
     */
    public function testGetCurrentApplication()
    {
        try
        {
            // James Mckee's pending app
            $current_application = $this->Application->getCurrentApplication(4370);

            $this->assertTrue($current_application['id'] == 4370, 'Current application is not correct.');
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Test Application::getOpenSubmission()
     *
     * @return void
     * @access public
     */
    public function testGetOpenSubmission()
    {
        try
        {
            // James Mckee's pending app
            $open_submission = $this->Application->getOpenSubmission(4370);

            $this->assertTrue($open_submission['id'] == 7544, 'Open submission is not correct.');
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Test Application::getCurrentSubmission()
     *
     * @return void
     * @access public
     */
    public function testGetCurrentSubmission()
    {
        try
        {
            // James Mckee's pending app
            $current_submission = $this->Application->getCurrentSubmission(4370);

            $this->assertTrue($current_submission['id'] == 7544, 'Current submission is not correct.');
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Test Application::isOpen()
     *
     * @return void
     * @access public
     */
    public function testIsOpen()
    {
        try
        {
            // James Mckee's pending app
            $this->assertTrue($this->Application->isOpen(4370), 'Open Application is not open.');
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Test Application::getSubmissions()
     *
     * @return void
     * @access public
     */
    public function testGetSubmissions()
    {
        $results = $this->Application->getSubmissions(4370);

        $this->assertEquals($results[0]['ApplicationSubmission']['id'], 7544);
    }

    /**
     * Test Application::getLicenseTypeList()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeList()
    {
        $results = $this->Application->getLicenseTypeList();

        $expected = array(4 => 'Firm License Type #1', 1 => 'License Type #1', 3 => 'License Type #3');

        $this->assertEquals($expected, $results);
    }

    /**
     * Test Application::getApplicationTypeList()
     *
     * @return void
     * @access public
     */
    public function testgetApplicationTypeList()
    {
        $results = $this->Application->getApplicationTypeList();

        $expected = array(3 => 'Conversion', 1 => 'Initial', 2 => 'Renewal');

        $this->assertEquals($expected, $results);
    }

    /**
     * Test Application::getApplicationStatusList()
     *
     * @return void
     * @access public
     */
    public function testGetApplicationStatusList()
    {
        $results = $this->Application->getApplicationStatusList();

        $expected = array(4 => 'Approved', 5 => 'Denied', 2 => 'Incomplete', 3 => 'Pending', 1 => 'Undefined');

        $this->assertEquals($expected, $results);
    }

    /**
     * Test Application::pendingCount()
     *
     * @return void
     * @access public
     */
    public function testPendingCount()
    {
        $results = $this->Application->pendingCount();

        $this->assertEquals(1, $results);
    }

}