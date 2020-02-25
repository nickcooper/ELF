<?php
/**
 * EducationDegreeFixture
 *
 */
class EducationDegreeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'account_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'degree_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'other' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'school_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'start_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'end_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'certified_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'highest_earned' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'account_id' => 1,
			'degree_id' => 1,
			'other' => 'Lorem ipsum dolor sit amet',
			'school_name' => 'Lorem ipsum dolor sit amet',
			'start_date' => '2013-08-09',
			'end_date' => '2013-08-09',
			'certified_date' => '2013-08-09',
			'highest_earned' => 1,
			'created' => '2013-08-09 14:17:49',
			'modified' => '2013-08-09 14:17:49'
		),
	);
}
