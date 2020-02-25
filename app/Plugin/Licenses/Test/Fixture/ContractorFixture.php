<?php
class ContractorFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'license_id' => array('type' => 'integer', 'length' => 11, 'null' => false),
		'crn' => array('type' => 'string', 'null' => false),
		'crn_expire_date' => array('type' => 'date', 'null' => false),
		'fin' => array('type' => 'string', 'null' => false),
		'fin_last_four' => array('type' => 'string', 'length' => 4, 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
	public $records = array(
		array(
			'id' => 1,
			'license_id' => '1',
			'crn' => '4682971564',
			'crn_expire_date' => '2013-06-20',
			'fin' => '123456789',
			'fin_last_four' => '6789',
			'created' => '2007-03-18 10:39:23',
			'modified' => '2007-03-18 10:41:31'
		),
		array(
			'id' => 2,
			'license_id' => '2',
			'crn' => '345687198',
			'crn_expire_date' => '2013-06-20',
			'fin' => '224466882',
			'fin_last_four' => '6882',
			'created' => '2007-03-18 10:39:23',
			'modified' => '2007-03-18 10:41:31'
		),
		array(
			'id' => 3,
			'license_id' => '3',
			'crn' => '4671995645',
			'crn_expire_date' => '2013-06-20',
			'fin' => '659435761',
			'fin_last_four' => '5761',
			'created' => '2007-03-18 10:39:23',
			'modified' => '2007-03-18 10:41:31'
		),
	);
}