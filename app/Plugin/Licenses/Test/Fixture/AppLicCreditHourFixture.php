<?php
class AppLicCreditHourFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'application_type_id' => array('type' => 'integer', 'null' => false),
		'license_type_id' => array('type' => 'integer', 'null' => false),
		'code_hours' => array('type' => 'integer', 'null' => false),
		'total_hours' => array('type' => 'integer', 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	public $records = array(
		array(
			'id' => 1,
			'application_type_id' => 1,
			'license_type_id' => 1,
			'code_hours' => 10,
			'total_hours' => 10,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
	);
}