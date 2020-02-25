<?php
App::uses('ShoppingCart', 'Payments.Model');

class ShoppingCartTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.payments.shopping_cart',
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // load the ShoppingCart model
        $this->ShoppingCart = ClassRegistry::init('Payments.ShoppingCart');
    }

    /**
     * addItem method
     *
     * @return void
     */
    public function testAddItem()
    {
        // get the comparison record count
        $comp_count = $this->ShoppingCart->find('count')+1;

        // get a data record
        $data = $this->ShoppingCart->find('first');

        // remove the record id for testing the add method
        $data['ShoppingCart']['id'] = null;

        // change the foreign_key so we don't get duplicate exception
        $data['ShoppingCart']['foreign_key'] = 99;

        // attempt to add new record
        $this->ShoppingCart->create();
        $this->ShoppingCart->addItem(
            $data['ShoppingCart']['account_id'],
            $data['ShoppingCart']['foreign_plugin'],
            $data['ShoppingCart']['foreign_obj'],
            $data['ShoppingCart']['foreign_key'],
            $data['ShoppingCart']['fee_id'],
            $data['ShoppingCart']['label'],
            $data['ShoppingCart']['owner']
        );

        // get the new record count
        $new_count = $this->ShoppingCart->find('count');

        $this->assertEquals($comp_count, $new_count);
    }
}