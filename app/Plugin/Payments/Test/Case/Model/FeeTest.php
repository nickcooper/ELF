<?php
App::uses('Fee', 'Payments.Model');

class FeeTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.payments.fee',
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // load the Fee model
        $this->Fee = ClassRegistry::init('Payments.Fee');
    }

    public function testGetFeeByKey()
    {
        $fee = $this->Fee->getFeeByKey('lt1_initial');

        $passed = $fee ? true : false;

        $this->assertTrue($passed);
    }
}