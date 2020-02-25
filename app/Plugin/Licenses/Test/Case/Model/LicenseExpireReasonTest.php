<?php
App::uses('License', 'Licenses.Model');
App::uses('LicenseExpireReason', 'Licenses.Model');

class LicenseExpireReasonTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

    	'plugin.Licenses.license',
    	'plugin.Licenses.license_expire_reason',
   	);

    public function setUp()
    {
        parent::setUp();

        // initialize needed models
        $this->License = ClassRegistry::init('Licenses.License');
        $this->LicenseExpireReason = ClassRegistry::init('Licenses.LicenseExpireReason');
    }

	/**
     * Test LicenseExpireReason::setReason()
     *
     * @return void
     * @access public
     */
    public function testSetReason()
    {
        $license_expire_reason = array();
        try
        {
            $license = $this->License->findById(1);

            $this->LicenseExpireReason->setReason($license['License']['id'], '2015-02-05', 'Test Reason');

            $license_expire_reason = $this->LicenseExpireReason->findByLicenseId($license['License']['id']);

            $this->assertEquals($license_expire_reason['LicenseExpireReason']['expire_date'], '2015-02-05', $message);
    	}
    	catch (Exception $e)
    	{
    		$message = $e->getMessage();
    	}

    }
}