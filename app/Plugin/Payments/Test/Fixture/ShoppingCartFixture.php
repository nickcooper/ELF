<?php
class ShoppingCartFixture extends CakeTestFixture
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'account_id' => array('type' => 'integer', 'length' => 10, 'null' => false),
        'fee_id' => array('type' => 'integer', 'length' => 10, 'null' => false),
        'label' => array('type' => 'string', 'length' => 150, 'null' => false),
        'owner' => array('type' => 'string', 'length' => 150, 'null' => true),
        'foreign_plugin' => array('type' => 'string', 'length' => 45, 'null' => false),
        'foreign_obj' => array('type' => 'string', 'length' => 45, 'null' => false),
        'foreign_key' => array('type' => 'integer', 'length' => 11, 'null' => false),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1,
            'account_id' => 1,
            'fee_id' => 1,
            'label' => 'Test Label 1',
            'owner' => 'Test owner 1',
            'foreign_plugin' => 'TestPlugin_1',
            'foreign_obj' => 'TestObject_1',
            'foreign_key' => 1,
            'created' => '2013-03-18 10:39:23',
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 2,
            'account_id' => 1,
            'fee_id' => 2,
            'label' => 'Test Label 2',
            'owner' => 'Test owner 2',
            'foreign_plugin' => 'TestPlugin_2',
            'foreign_obj' => 'TestObject_2',
            'foreign_key' => 2,
            'created' => '2013-03-18 10:39:23',
            'modified' => '2013-03-18 10:41:31'
        ),
    );
}
?>