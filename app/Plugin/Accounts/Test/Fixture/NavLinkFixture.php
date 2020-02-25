<?php
class NavLinkFixture extends CakeTestFixture 
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'group_id' => array('type' => 'integer', 'null' => true),
        'plugin' => array('type' => 'string', 'length' => 50, 'null' => true),
        'label' => array('type' => 'string', 'length' => 50, 'null' => false),
        'descr' => array('type' => 'text', 'null' => true),
        'path' => array('type' => 'string', 'length' => 125, 'null' => true),
        'enabled' => array('type' => 'integer', 'length' => 1, 'null' => false, 'default' => 0),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1, 
            'group_id' => 2, 
            'plugin' => 'Accounts',
            'label' => 'Accounts',
            'descr' => 'Account Management',
            'path' => '/accounts/accounts',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 2, 
            'group_id' => 3, 
            'plugin' => 'Accounts',
            'label' => 'Accounts',
            'descr' => 'Account Management',
            'path' => '/accounts/accounts',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 3, 
            'group_id' => 3, 
            'plugin' => 'Licenses',
            'label' => 'Licenses',
            'descr' => 'License Management',
            'path' => '/licenses/licenses',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
    );
}