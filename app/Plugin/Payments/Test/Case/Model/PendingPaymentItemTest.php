<?php
App::uses('PendingPaymentItem', 'Payments.Model');

class PendingPaymentItemTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.payments.pending_payment_item',
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
        $this->PendingPaymentItem = ClassRegistry::init('Payments.PendingPaymentItem');
    }

    /**
     * addItem method
     *
     * @return void
     */
    public function testAddItem()
    {
        // get the comparison record count
        $comp_count = $this->PendingPaymentItem->find('count')+1;

        // get a data record
        $data = $this->PendingPaymentItem->find('first');

        unset($data['PendingPaymentItem']['id']);
        unset($data['PendingPaymentItem']['created']);
        unset($data['PendingPaymentItem']['modified']);

        // attempt to add new record
        $this->PendingPaymentItem->create();
        $this->PendingPaymentItem->add($data);

        // get the new record count
        $new_count = $this->PendingPaymentItem->find('count');

        $this->assertEquals($comp_count, $new_count);
    }
}