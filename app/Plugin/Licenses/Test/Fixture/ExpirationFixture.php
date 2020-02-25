<?php
/**
 * ExpirationFixture
 *
 */
class ExpirationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_plugin' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100),
		'parent_obj' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100),
		'parent_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'foreign_plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'foreign_obj' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'expire_date' => array('type' => 'date', 'null' => false, 'default' => null),
		'label' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45),
		'descr' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
		'action' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);
}
