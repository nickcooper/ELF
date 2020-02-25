<?php
class PendingPaymentItemFixture extends CakeTestFixture
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'foreign_plugin' => array('type' => 'string', 'length' => 45, 'null' => false),
        'foreign_obj' => array('type' => 'string', 'length' => 45, 'null' => false),
        'foreign_key' => array('type' => 'integer', 'length' => 10, 'null' => false),
        'payment_id' => array('type' => 'integer', 'length' => 10, 'null' => false),
        'fee_id' => array('type' => 'integer', 'length' => 10, 'null' => false),
        'fee' => array('type' => 'float', 'length' => 5, 'default' => '0.00'),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1,
            'foreign_plugin' => 'Licenses',
            'foreign_obj' => 'Application',
            'foreign_key' => 1,
            'payment_id' => 1,
            'fee_id' => 1,
            'fee' => '60.00',
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        array(
            'id' => 2,
            'foreign_plugin' => 'Licenses',
            'foreign_obj' => 'Application',
            'foreign_key' => 1,
            'payment_id' => 1,
            'fee_id' => 1,
            'fee' => '30.00',
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        array(
            'id' => 3,
            'foreign_plugin' => 'Licenses',
            'foreign_obj' => 'Application',
            'foreign_key' => 1,
            'payment_id' => 1,
            'fee_id' => 1,
            'fee' => '120.00',
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
    );
}