<?php
class ScreeningAnswerFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'screening_question_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'answer' => array('type' => 'integer', 'length' => '4', 'null' => false, 'default' => '0'),
		'comment' => array('type' => 'text'),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
	
	public $records = array(
		// Answer for app 1
		array(
			'id' => 1,
			'application_id' => 1,
			'screening_question_id' => 1,
			'answer' => 0,
			'comment' => null,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
		// Answer for app 1
		array(
			'id' => 2,
			'application_id' => 1,
			'screening_question_id' => 2,
			'answer' => 0,
			'comment' => null,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
		// Answer for app 2
		array(
			'id' => 3,
			'application_id' => 2,
			'screening_question_id' => 1,
			'answer' => 0,
			'comment' => null,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
		// Answer for app 2
		array(
			'id' => 4,
			'application_id' => 2,
			'screening_question_id' => 2,
			'answer' => 0,
			'comment' => null,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
		// Answer for app 3
		array(
			'id' => 5,
			'application_id' => 3,
			'screening_question_id' => 1,
			'answer' => 0,
			'comment' => null,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
		// Answer for app 3
		array(
			'id' => 6,
			'application_id' => 3,
			'screening_question_id' => 2,
			'answer' => 0,
			'comment' => null,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
	);
}