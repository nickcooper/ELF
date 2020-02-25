<?php
class QuestionFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'license_type_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'question' => array('type' => 'string', 'length' => '255', 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
}