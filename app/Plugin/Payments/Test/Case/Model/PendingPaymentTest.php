<?php
App::uses('PendingPayment', 'Payments.Model');

class PendingPaymentTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.payments.pending_payment',
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // load the Payment model
        $this->PendingPayment = ClassRegistry::init('Payments.PendingPayment');
    }

    /**
     * addItem method
     *
     * @return void
     */
    public function testAddItem()
    {
        // get the comparison record count
        $comp_count = $this->PendingPayment->find('count')+1;

        // get a data record
        $data = $this->PendingPayment->find('first');

        unset($data['PendingPayment']['id']);
        unset($data['PendingPayment']['created']);
        unset($data['PendingPayment']['modified']);

        // attempt to add new record
        $this->PendingPayment->create();
        $this->PendingPayment->add($data);

        // get the new record count
        $new_count = $this->PendingPayment->find('count');

        $this->assertEquals($comp_count, $new_count);
    }
}