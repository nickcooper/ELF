<?php
/**
 * WorkExperienceFixture
 *
 */
class WorkExperienceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'account_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'employer' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'position' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'supervisor_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 90, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'supervisor_phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'start_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'end_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'hrs_per_week' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'descr' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'employer' => 'Lorem ipsum dolor sit amet',
			'position' => 'Lorem ipsum dolor sit amet',
			'supervisor_name' => 'Lorem ipsum dolor sit amet',
			'supervisor_phone' => 'Lorem ipsum dolor sit amet',
			'start_date' => '2013-08-09',
			'end_date' => '2013-08-09',
			'hrs_per_week' => 1,
			'descr' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 13:59:04',
			'modified' => '2013-08-09 13:59:04'
		),
		// James Mckee work exp 1
		array(
			'id' => 8655,
			'account_id' => 3284,
			'employer' => 'Widgets Inc',
			'position' => 'Engineer',
			'supervisor_name' => 'Kevin Jones',
			'supervisor_phone' => '5556359482',
			'start_date' => '2009-08-09',
			'end_date' => '2012-08-09',
			'hrs_per_week' => 40,
			'descr' => 'Did engineering stuff',
			'created' => '2013-08-09 13:59:04',
			'modified' => '2013-08-09 13:59:04'
		),
		// James Mckee work exp 1
		array(
			'id' => 8671,
			'account_id' => 3284,
			'employer' => 'Thangs Corp',
			'position' => 'Technician',
			'supervisor_name' => 'Mark Lowe',
			'supervisor_phone' => '5554887984',
			'start_date' => '2012-08-09',
			'end_date' => null,
			'hrs_per_week' => 40,
			'descr' => 'Did technical stuff',
			'created' => '2013-08-09 13:59:04',
			'modified' => '2013-08-09 13:59:04'
		),
	);

}
