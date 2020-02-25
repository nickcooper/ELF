<?php
class UploadFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'parent_plugin' => array('type' => 'string', 'length' => '45', 'null' => true),
		'parent_object' => array('type' => 'string', 'length' => '45', 'null' => true),
		'parent_key' => array('type' => 'integer', 'length' => '10', 'null' => true),
		'foreign_plugin' => array('type' => 'string', 'length' => '45', 'null' => true),
		'foreign_obj' => array('type' => 'string', 'length' => '45', 'null' => true),
		'foreign_key' => array('type' => 'integer', 'length' => '10', 'null' => true),
		'parent_plugin' => array('type' => 'string', 'length' => '45', 'null' => true),
		'identifier' => array('type' => 'string', 'length' => '45', 'null' => true),
		'label' => array('type' => 'string', 'length' => '150', 'null' => false),
		'file_path' => array('type' => 'string', 'length' => '255', 'null' => false),
		'file_name' => array('type' => 'string', 'length' => '500', 'null' => false),
		'file_size' => array('type' => 'integer', 'length' => '10', 'null' => false),
		'file_ext' => array('type' => 'string', 'length' => '45', 'null' => false),
		'mime_type' => array('type' => 'string', 'length' => '45', 'null' => false),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
}