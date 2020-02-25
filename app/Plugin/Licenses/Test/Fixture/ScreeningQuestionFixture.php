<?php
class ScreeningQuestionFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'license_type_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'question' => array('type' => 'string', 'length' => '300', 'null' => false),
		'correct_answer' => array('type' => 'integer', 'length' => '4', 'null' => false, 'default' => '0'),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
	
	public $records = array(
		// Question for license type 1
		array(
			'id' => 1,
			'license_type_id' => 1,
			'question' => 'Question One',
			'correct_answer' => 0,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
		// Question for license type 1
		array(
			'id' => 2,
			'license_type_id' => 1,
			'question' => 'Question Two',
			'correct_answer' => 0,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45'
		),
	);
}