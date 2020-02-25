<?php
App::uses('AppLicCreditHour', 'Licenses.Model');

class AppLicCreditHourTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.licenses.app_lic_credit_hour'
    );

    public function setUp()
    {
        parent::setUp();
        $this->AppLicCreditHour = ClassRegistry::init('Licenses.AppLicCreditHour');
    }

    /**
     * Test AppLicCreditHour::getCreditHours()
     *
     * @return void
     * @access public
     */
    public function testGetCreditHours()
    {
        $result = $this->AppLicCreditHour->getCreditHours(1, 1);

        $expected = array(10, 10);

        $this->assertEquals($expected, $result);
    }
}