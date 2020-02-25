<?php
/**
 * AccountFixture
 *
 */
class AccountFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
        'legacy_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
        'group_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
        'username' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
        'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
        'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
        'label' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
        'first_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45),
        'last_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45),
        'middle_initial' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
        'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
        'ssn' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
        'ssn_last_four' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4),
        'dob' => array('type' => 'date', 'null' => true, 'default' => null),
        'enabled' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'),
        'probation' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
        'perjury_acknowledged' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'no_mail' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
        'last_login' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'no_public_contact' => array('tinyint(1)', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array(
            'id' => 1,
            'legacy_id' => 1,
            'group_id' => 2,
            'username' => 'jdoe',
            'password' => 'sekret',
            'title' => null,
            'label' => 'Doe, John',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_initial' => 'F',
            'email' => 'jdoe@mail.com',
            'ssn' => null,
            'ssn_last_four' => 8456,
            'dob' => '1973-02-03',
            'enabled' => 1,
            'probation' => 0,
            'perjury_acknowledged' => null,
            'no_mail' => 0,
            'last_login' => '2013-03-18 10:39:23',
            'created' => '2013-03-18 10:39:23',
            'modified' => '2013-03-18 10:41:31'
        ),
        // James Mckee
        array(
            'id' => 3284,
            'legacy_id' => 5093,
            'group_id' => 1,
            'username' => 'jmckee',
            'password' => 'sekret',
            'title' => 'Mr',
            'label' => 'Mckee, James F',
            'first_name' => 'James',
            'last_name' => 'Mckee',
            'middle_initial' => 'F',
            'email' => 'jmckee@mail.com',
            'ssn' => null,
            'ssn_last_four' => 8456,
            'dob' => '1973-02-03',
            'enabled' => 1,
            'probation' => 0,
            'perjury_acknowledged' => 1,
            'no_mail' => 0,
            'last_login' => '2013-03-18 10:39:23',
            'created' => '2013-03-18 10:39:23',
            'modified' => '2013-03-18 10:41:31'
        )
    );
}
