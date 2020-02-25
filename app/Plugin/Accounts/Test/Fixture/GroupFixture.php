<?php
class GroupFixture extends CakeTestFixture 
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'program_id' => array('type' => 'integer', 'length' => 10, 'null' => true),
        'group_program_id' => array('type' => 'integer', 'length' => 10, 'null' => true),
        'label' => array('type' => 'string', 'length' => 150, 'null' => false),
        'descr' => array('type' => 'string', 'length' => 250, 'null' => true),
        'enabled' => array('type' => 'integer', 'length' => 1, 'null' => false),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1, 
            'program_id' => null,
            'group_program_id' => null,
            'label' => 'Default Group',
            'descr' => 'All new accounts are assigned to this group. No admin/special permissions.',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 2, 
            'program_id' => null,
            'group_program_id' => null,
            'label' => 'Super Admin',
            'descr' => 'Iowa Interactive staff accounts only. Full application access.',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 3, 
            'program_id' => 1,
            'group_program_id' => 1,
            'label' => 'Program Admin (PRG1)',
            'descr' => 'Testing program group.',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 4, 
            'program_id' => 2,
            'group_program_id' => 1,
            'label' => 'Program Admin (PRG2)',
            'descr' => 'Testing program group.',
            'enabled' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
    );
}
?>