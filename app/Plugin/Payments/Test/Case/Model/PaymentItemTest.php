<?php
App::uses('PaymentItem', 'Payments.Model');
App::uses('Application', 'Licenses.Model');

class PaymentItemTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.payments.payment_item',
        'plugin.licenses.application',
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
        $this->PaymentItem = ClassRegistry::init('Payments.PaymentItem');
    }

    /**
     * addItem method
     *
     * @return void
     */
    public function testAddItem()
    {
        $data = array(
            'PaymentItem' => array(
                'foreign_plugin' => 'Licenses',
                'foreign_obj' => 'Application',
                'foreign_key' => 1,
                'payment_id' => 1,
                'fee_id' => 1,
                'fee' => '60.00',
                'created' => '2013-06-24 16:44:45',
                'modified' => '2013-06-24 16:44:45',
            )
        );

        // get the comparison record count
        $comp_count = $this->PaymentItem->find('count')+1;

        // attempt to add new record
        $this->PaymentItem->create();
        $this->PaymentItem->add($data);

        // get the new record count
        $new_count = $this->PaymentItem->find('count');

        $this->assertEquals($comp_count, $new_count);
    }
}