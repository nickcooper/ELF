<?php
class VariantFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'label' => array('type' => 'string', 'length' => '45', 'null' => false),
		'abbr' => array('type' => 'string', 'length' => '6', 'null' => false),
		'descr' => array('type' => 'string', 'length' => '250', 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
}