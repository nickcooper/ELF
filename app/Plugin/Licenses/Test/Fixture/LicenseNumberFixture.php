<?php
class LicenseNumberFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'foreign_plugin' => array('type' => 'string', 'length' => '45', 'null' => true),
		'foreign_obj' => array('type' => 'string', 'length' => '45', 'null' => false),
		'foreign_key' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
}