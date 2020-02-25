<?php
class CourseCatalogFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'label' => array('type' => 'string', 'length' => '100', 'null' => false),
		'abbr' => array('type' => 'string', 'length' => '5'),
		'descr' => array('type' => 'string', 'length' => '250'),
		'code_hours' => array('type' => 'integer', 'null' => true, 'length' => '10'),
		'non_code_hours' => array('type' => 'integer', 'null' => true, 'length' => '10'),
		'test_attempts' => array('type' => 'integer', 'length' => '10'),
		'enabled' => array('type' => 'integer', 'length' => '1', 'null' => false, 'default' => '1'),
		'cycle' => array('type' => 'integer', 'length' => '10'),
		'course_group_id' => array('type' => 'integer', 'null' => false, 'length' => '4'),
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	public $records = array(
        array(
        	'id' => 1,
			'program_id' => 1,
			'label' => 'Course Catalog #1',
			'abbr' => 'CC1',
			'descr' => 'Course Catalog #1 Description',
			'code_hours' => 8,
			'non_code_hours' => 8,
			'test_attempts' => 3,
			'enabled' => 1,
			'cycle' => 365,
			'course_group_id' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
        	'id' => 2,
			'program_id' => 1,
			'label' => 'Course Catalog #2',
			'abbr' => 'CC2',
			'descr' => 'Course Catalog #2 Description',
			'code_hours' => 1,
			'non_code_hours' => 1,
			'test_attempts' => 3,
			'enabled' => 1,
			'cycle' => 365,
			'course_group_id' => 1,
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
    );
}