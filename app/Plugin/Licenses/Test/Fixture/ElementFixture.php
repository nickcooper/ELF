<?php
/**
 * ElementFixture
 *
 */
class ElementFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'label' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'comment' => 'Default label used as block heading in view.', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null),
		'element_plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'element' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'comment' => 'my_element, element_dir/my_element', 'charset' => 'latin1'),
		'foreign_plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_obj' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'data_keys' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'comment' => 'Where the data resides in the license data array. Used to validate data exists before submitting the application.', 'charset' => 'latin1'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'label' => 'Address',
			'description' => 'Address Description',
			'element_plugin' => 'address_book',
			'element' => 'addresses',
			'foreign_plugin' => 'Accounts',
			'foreign_obj' => 'Account',
			'data_keys' => 'Account.Address',
			'modified' => '2013-08-09 14:23:59',
			'created' => '2013-08-09 14:23:59'
		),
	);
}
