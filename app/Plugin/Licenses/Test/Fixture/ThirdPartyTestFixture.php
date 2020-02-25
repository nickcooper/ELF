<?php
/**
 * ThirdPartyTestFixture
 *
 */
class ThirdPartyTestFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
 	public $fields = array(
 		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
 		'foreign_plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
 		'foreign_obj' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
 		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
 		'testing_center' => array('type' => 'string', 'null' => true, 'default' => null),
 		'date' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
 		'score' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3),
 		'pass' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
 		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
 		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
 	);

/**
 * Records
 *
 * @var array
 */
 	public $records = array(
 		array(
	 		'id' => 1,
	 		'foreign_plugin' => 'Licenses',
	 		'foreign_obj' => 'Application',
	 		'foreign_key' => 1,
	 		'testing_center' => 'Testing Center 1',
	 		'date' => '2014-04-14 13:07:00',
	 		'score' => 89,
	 		'pass' => 1,
	 		'created' => '2014-04-14 13:07:00',
	 		'modified' => '2014-04-14 13:07:00',
	 	),
 		array(
	 		'id' => 2,
	 		'foreign_plugin' => 'Licenses',
	 		'foreign_obj' => 'Application',
	 		'foreign_key' => 2,
	 		'testing_center' => 'Testing Center 2',
	 		'date' => '2014-04-14 13:07:00',
	 		'score' => 22,
	 		'pass' => 1,
	 		'created' => '2014-04-14 13:07:00',
	 		'modified' => '2014-04-14 13:07:00',
	 	),
 		array(
	 		'id' => 3,
	 		'foreign_plugin' => 'Licenses',
	 		'foreign_obj' => 'Application',
	 		'foreign_key' => 3,
	 		'testing_center' => 'Testing Center 3',
	 		'date' => '2014-04-14 13:07:00',
	 		'score' => 33,
	 		'pass' => 1,
	 		'created' => '2014-04-14 13:07:00',
	 		'modified' => '2014-04-14 13:07:00',
	 	),
	 	array(
	 		'id' => 4,
	 		'foreign_plugin' => 'Licenses',
	 		'foreign_obj' => 'Application',
	 		'foreign_key' => 4,
	 		'testing_center' => 'Testing Center 4',
	 		'date' => '2014-04-14 13:07:00',
	 		'score' => 44,
	 		'pass' => 1,
	 		'created' => '2014-04-14 13:07:00',
	 		'modified' => '2014-04-14 13:07:00',
	 	),
 	);
}