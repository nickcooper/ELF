<?php
class QuestionAnswerFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'question_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'answer' => array('type' => 'text', 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
}