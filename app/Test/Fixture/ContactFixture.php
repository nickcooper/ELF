<?php
/**
 * ContactFixture
 *
 */
class ContactFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'foreign_plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_obj' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'account_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'comment' => 'May or may not associated to an account.'),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'first_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 11, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ext' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 4, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'foreign_plugin' => 'Accounts',
			'foreign_obj' => 'Reference',
			'foreign_key' => 1,
			'account_id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 2,
			'foreign_plugin' => 'Accounts',
			'foreign_obj' => 'Reference',
			'foreign_key' => 2,
			'account_id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 3,
			'foreign_plugin' => 'Accounts',
			'foreign_obj' => 'Reference',
			'foreign_key' => 3,
			'account_id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 4,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 4,
			'account_id' => 4,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 5,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 5,
			'account_id' => 5,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 6,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 6,
			'account_id' => 6,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 7,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 7,
			'account_id' => 7,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 8,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 8,
			'account_id' => 8,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 9,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 9,
			'account_id' => 9,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
		array(
			'id' => 10,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 10,
			'account_id' => 10,
			'title' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ips',
			'ext' => 'Lo',
			'email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 16:04:42',
			'modified' => '2013-08-09 16:04:42'
		),
	);

}
