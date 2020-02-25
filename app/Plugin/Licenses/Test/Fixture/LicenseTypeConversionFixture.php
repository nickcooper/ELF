<?php
/**
 * LicenseTypeConversionFixture
 *
 */
class LicenseTypeConversionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'license_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'convert_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'lic_type_id' => array('column' => array('license_type_id', 'convert_type_id'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'license_type_id' => '1',
			'convert_type_id' => '2',
			'modified' => null,
			'created' => null
		),
		array(
			'id' => '2',
			'license_type_id' => '2',
			'convert_type_id' => '1',
			'modified' => null,
			'created' => null
		),
	);

}
