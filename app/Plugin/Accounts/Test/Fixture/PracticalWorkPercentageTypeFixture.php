<?php
/**
 * PracticalWorkPercentageTypeFixture
 *
 */
class PracticalWorkPercentageTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'label' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'label' => 'Lorem ipsum dolor sit amet',
			'enabled' => 1,
			'created' => '2013-08-09 15:48:58',
			'modified' => '2013-08-09 15:48:58'
		),
	);

}
