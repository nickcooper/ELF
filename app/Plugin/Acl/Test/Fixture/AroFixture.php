<?php
/* Aro Fixture generated on: 2012-03-20 11:39:26 : 1332261566 */

/**
 * AroFixture
 *
 */
class AroFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index', 'collate' => NULL, 'comment' => ''),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => NULL, 'comment' => ''),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index', 'collate' => NULL, 'comment' => ''),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => NULL, 'comment' => ''),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'parent_id' => array('column' => 'parent_id', 'unique' => 0), 'model' => array('column' => array('model', 'foreign_key'), 'unique' => 0), 'left' => array('column' => array('lft', 'rght'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'parent_id' => NULL,
			'model' => 'Group',
			'foreign_key' => '1',
			'alias' => 'General User',
			'lft' => '1',
			'rght' => '2'
		),
		array(
			'id' => '2',
			'parent_id' => NULL,
			'model' => 'Group',
			'foreign_key' => '2',
			'alias' => 'Super Admin',
			'lft' => '3',
			'rght' => '6'
		),
		array(
			'id' => '45',
			'parent_id' => '2',
			'model' => 'Account',
			'foreign_key' => '1',
			'alias' => 'jacob.grady@iowaid',
			'lft' => '4',
			'rght' => '5'
		),
		array(
			'id' => '43',
			'parent_id' => NULL,
			'model' => 'Group',
			'foreign_key' => '10',
			'alias' => 'Program Manager (Test Program)',
			'lft' => '13',
			'rght' => '14'
		),
		array(
			'id' => '42',
			'parent_id' => NULL,
			'model' => 'Group',
			'foreign_key' => '35',
			'alias' => 'Program Manager (Lead Poisoning Prevention)',
			'lft' => '11',
			'rght' => '12'
		),
		array(
			'id' => '41',
			'parent_id' => NULL,
			'model' => 'Group',
			'foreign_key' => '9',
			'alias' => 'Program Administrator (Test Program)',
			'lft' => '9',
			'rght' => '10'
		),
		array(
			'id' => '40',
			'parent_id' => NULL,
			'model' => 'Group',
			'foreign_key' => '34',
			'alias' => 'Program Administrator (Lead Poisoning Prevention)',
			'lft' => '7',
			'rght' => '8'
		),
	);
}
