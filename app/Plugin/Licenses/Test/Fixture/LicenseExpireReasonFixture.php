<?php
class LicenseExpireReasonFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'license_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'expire_date' => 'date',
		'descr' => array('type' => 'string', 'length' => '200', 'null' => true),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
	
	public $records = array(
		// Active License
		array(
			'id' => 1,
			'license_id' => '1',
			'expire_date' => '2014-06-24',
			'descr' => 'application expires',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
		array(
			'id' => 2,
			'license_id' => '2',
			'expire_date' => '2014-06-24',
			'descr' => 'course expires',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
	);
}