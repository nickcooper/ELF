<?php
class ManagerFixture extends CakeTestFixture 
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'foreign_plugin' => array('type' => 'string', 'length' => '45', 'null' => true),
        'foreign_obj' => array('type' => 'string', 'length' => '45', 'null' => false),
        'foreign_key' => array('type' => 'integer', 'length' => '10', 'null' => false),
        'account_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
        'primary_flag' => array('type' => 'integer', 'length' => '1', 'default' => 0),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1,
            'foreign_plugin' => 'Firms',
            'foreign_obj' => 'Firm',
            'foreign_key' => 1,
            'account_id' => 1,
            'primary_flag' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 2,
            'foreign_plugin' => 'Firms',
            'foreign_obj' => 'Firm',
            'foreign_key' => 2,
            'account_id' => 2,
            'primary_flag' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
    );
}