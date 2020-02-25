<?php
/**
 * PracticalWorkPercentageFixture
 *
 */
class PracticalWorkPercentageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'practical_work_percentage_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'account_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'percentage' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3),
		'descr' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'enabled' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
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
			'practical_work_percentage_type_id' => 1,
			'account_id' => 1,
			'percentage' => 100,
			'descr' => 'Lorem ipsum dolor sit amet',
			'enabled' => 1,
			'created' => '2013-08-09 14:05:57',
			'modified' => '2013-08-09 14:05:57'
		),
	);

}
