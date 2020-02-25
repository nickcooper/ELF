<?php
class ApplicationTypeFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary', 'null' => false),
		'label' => array('type' => 'string', 'length' => '32', 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	public $records = array(
		array(
			'id' => 1,
			'label' => 'Initial',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
		array(
			'id' => 2,
			'label' => 'Renewal',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
		array(
			'id' => 3,
			'label' => 'Conversion',
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
	);
}