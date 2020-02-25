<?php
/**
 * ThirdPartyTestAssignmentFixture
 *
 */
class ExamScoreFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'foreign_plugin' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
		'foreign_obj' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'exam_date' => array('type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'index'),
		'score' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'pass' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'sponsored' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
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
			'foreign_plugin' => '',
			'foreign_obj' => 'CourseRoster',
			'foreign_key' => 1,
			'exam_date' => '2013-08-09 15:24:15',
			'score' => 89,
			'pass' => 1,
			'sponsored' => '',
			'created' => '2013-08-09 15:24:15',
			'modified' => '2013-08-09 15:24:15'
		),
	);

}
