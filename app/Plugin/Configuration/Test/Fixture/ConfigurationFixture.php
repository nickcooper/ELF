<?php
/**
 * FirmFixture
 *
 */
class ConfigurationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 11),
		'plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'name' => array('type' => 'string', 'null' => false, 'length' => 127),
		'value' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255),
		'field_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'options' => array('type' => 'text', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);

	public $records = array(
		array(
			'id' => 1,
			'program_id' => null,
			'plugin' => null,
			'name' => 'test_record',
			'value' => 'falcon',
			'field_type' => null,
			'options' => null,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
	);
}
