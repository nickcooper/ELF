<?php
class ReciprocalFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'length' => '11', 'null' => false),
		'provider' => array('type' => 'string', 'length' => '150', 'null' => false),
		'course_title' => array('type' => 'string', 'length' => '150', 'null' => false),
		'hours' => array('type' => 'integer', 'length' => '11', 'default' => '0'),
		'pass' => array('type' => 'integer', 'length' => '1', 'default' => '0'),
		'score' => array('type' => 'integer', 'length' => '45', 'null' => true),
		'start_date' => array('type' => 'date', 'null' => false),
		'completed_date' => array('type' => 'date', 'null' => false),
		'expire_date' => 'date',
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	public $records = array(
		array(
			'id' => 1,
			'application_id' => 3,
			'provider' => 'TestProvider1',
			'course_title' => 'TestCourseTitle1',
			'hours' => 8,
			'pass' => 1,
			'score' => 97,
			'start_date' => '2016-07-09 14:16:06',
			'completed_date' => '2016-08-07 14:16:06',
			'expire_date' => '2015-08-09 14:16:06',
			'created' => '2016-08-09 14:16:06',
			'modified' => '2016-08-09 14:16:06'
		),
		array(
			'id' => 2,
			'application_id' => 3,
			'provider' => 'TestProvider2',
			'course_title' => 'TestCourseTitle2',
			'hours' => 4,
			'pass' => 1,
			'score' => 96,
			'start_date' => '2016-08-06 14:16:06',
			'completed_date' => '2016-08-07 14:16:06',
			'expire_date' => '2015-08-10 14:16:06',
			'created' => '2016-08-09 14:16:06',
			'modified' => '2016-08-09 14:16:06'
		),
	);
}