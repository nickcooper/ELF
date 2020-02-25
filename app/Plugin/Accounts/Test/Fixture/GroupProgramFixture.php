<?php
class GroupProgramFixture extends CakeTestFixture 
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'label' => array('type' => 'string', 'length' => 150, 'null' => false),
        'descr' => array('type' => 'string', 'length' => 250, 'null' => true),
        'enabled' => array('type' => 'integer', 'length' => 1, 'null' => false),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1, 
            'label' => 'Program Admin',
            'descr' => 'Testing program group.',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
    );
}
?>