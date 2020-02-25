<?php
/**
 * LicenseStatusFixture
 *
 */
class LicenseStatusFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);

	public $records = array(
		// Active License
		array(
			'id' => 1,
			'status' => 'Active',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
		array(
			'id' => 2,
			'status' => 'Expired',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
		array(
			'id' => 3,
			'status' => 'Incomplete',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
	);
}
