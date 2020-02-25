<?php
/**
 * CourseRosterFixture
 *
 */
class CourseRosterFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'course_section_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'account_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'student_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'completed' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'expire_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
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
			'course_section_id' => 1,
			'account_id' => 1,
			'student_number' => 'Lorem ipsum dolor sit amet',
			'completed' => 1,
			'expire_date' => '2016-08-09 14:16:06',
			'created' => '2013-08-09 14:16:06',
			'modified' => '2013-08-09 14:16:06'
		),
		array(
			'id' => 2,
			'course_section_id' => 2,
			'account_id' => 1,
			'student_number' => 'Lorem ipsum dolor sit amet',
			'completed' => 0,
			'expire_date' => '2016-08-09 14:16:06',
			'created' => '2013-08-19 14:16:06',
			'modified' => '2013-08-19 14:16:06'
		),
	);

}
