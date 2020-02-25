<?php
App::uses('License', 'Licenses.Model');
App::uses('CakeEvent', 'Event');

class LicenseTest extends CakeTestCase
{
	public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'contact',
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
        'plugin.Licenses.license_number',
        'plugin.Licenses.license_status',
        'plugin.Licenses.license_type',
        'plugin.Licenses.license_type_conversion',
        'plugin.Licenses.license_type_variant',
        'plugin.Licenses.license_variant',
        'plugin.Licenses.question',
        'plugin.Licenses.question_answer',
        'plugin.Licenses.reciprocal',
        'plugin.Licenses.screening_question',
        'plugin.Licenses.screening_answer',
        'plugin.Licenses.variant',
        'plugin.Notes.note',
        'plugin.Uploads.upload',
	);

    public function setUp()
    {
        parent::setUp();

        $this->License = ClassRegistry::init('Licenses.License');
        $this->LicenseStatus = ClassRegistry::init('Licenses.LicenseStatus');
    }

    public function testAdd()
    {
        $this->assertEquals(1, 1);
    }

    public function testGetApplication()
    {
    	$license = $this->License->getApplication(1);

    	$passed = (isset($license['License']['id']) && $license['License']['id'] == 1) ? true : false;

    	$this->assertTrue($passed);
    }
    public function testGetLicenseById()
    {
    	$license = $this->License->getLicenseById(1);

    	$passed = $license ? true : false;

    	$this->assertTrue($passed);
    }

    public function testGetLicenseByType()
    {
        // test good result
        $license = $this->License->getLicenseByType('firm_license_type_1', 1, 'Firm', 'Firms');
        $passed = (is_array($license) && Hash::get($license, 'License.id') == 2) ? true : false;
        $this->assertTrue($passed);
        // test bad result
        $license = $this->License->getLicenseByType('firm_license_type_1', 999, 'Firm', 'Firms');
        $passed = (is_array($license) && Hash::extract($license, 'License.id') == 2) ? true : false;
        $this->assertFalse($passed);
    }

    public function testCanRenew()
    {
        $can_renew = $this->License->canRenew(1);
        $this->assertFalse($can_renew);
    }

    public function testCanConvert()
    {
        $can_convert = $this->License->canConvert(1);
        $this->assertTrue($can_convert);
    }

    public function testCalcApprovalDates()
    {
        $contain = array(
            'LicenseType',
            'Application' => array(
                'order' => array('Application.id' => 'DESC')
            ),
        );

        $license = $this->License->getLicenseById(1, $contain);

        list($effective_date, $renewal_end_date) = $this->License->calcApprovalDates($license['LicenseType'], '2013-09-25 00:00:00', '2013-09-20 00:00:00');
        $passed = ($effective_date == '2013-09-20 00:00:00' && $renewal_end_date == '2031-03-18 00:00:00') ? true : false;
        $this->assertTrue($passed);
    }

    public function testSysNote()
    {
        $this->Note = ClassRegistry::init('Notes.Note');

        $contain = array('Note');

        if ($this->Note->sysNote(1, 'Licenses', 'License', 1, 'Testing sysNote writing from unit test...'))
        {
            $test_passed = true;
        }

        // since we are using fixtures, the following is validation code for developers when running this unit test
        //debug($this->License->getLicenseById(1, $contain));
        //exit;

        $this->assertTrue($test_passed);
    }

    public function testSetManuallyEdited()
    {
        $event = new CakeEvent('fake_event', $this, array('license_id' => 1));

        $this->License->setManuallyEdited($event);

        $license = $this->License->findById(1);

        $this->assertEquals($license['License']['manually_edited'], 1);
    }

    public function testSetStatus()
    {
        $expired_status = $this->LicenseStatus->findByStatus('Expired');

        if ($this->License->setStatus('Expired', 1))
        {
            $license = $this->License->find(
                'first',
                array(
                    'contain' => array(
                        'LicenseStatus'
                    ),
                    'conditions' => array(
                        'License.id' => 1
                    )
                )
            );
            $this->assertEquals($expired_status['LicenseStatus']['id'], $license['LicenseStatus']['id']);
        }
    }

    public function testActivateLicense()
    {
        $active_status = $this->LicenseStatus->findByStatus('Active');
        if ($this->License->setStatus('Active', 1))
        {
            $license = $this->License->find(
                'first',
                array(
                    'contain' => array(
                        'LicenseStatus'
                    ),
                    'conditions' => array(
                        'License.id' => 1
                    )
                )
            );
            $this->assertEquals($active_status['LicenseStatus']['id'], $license['LicenseStatus']['id']);
        }
    }

    public function testGetOpenApplication()
    {
        $open_application = $this->License->getOpenApplication(6972);

        $this->assertTrue($open_application['Application']['id'] == 4370, 'Open application is not correct.');
    }

    public function testGetCurrentApplicationId()
    {
        $this->assertTrue($this->License->getCurrentApplicationId(6972) == 4370, 'Current application id is not correct.');
    }
}