<?php
/**
 * ApplicationStatusFixture
 *
 */
class ApplicationStatusFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'label' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'descr' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
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
			'label' => 'Undefined',
			'descr' => 'An application in an unknown state.',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => '2',
			'label' => 'Incomplete',
			'descr' => 'An incomplete application that has not been submitted or approved.',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => '3',
			'label' => 'Pending',
			'descr' => 'An application that has been submitted for approval.',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => '4',
			'label' => 'Approved',
			'descr' => 'An application that has been approved for licensing.',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => '5',
			'label' => 'Denied',
			'descr' => 'An application that has been denied by administration.',
			'created' => null,
			'modified' => null
		),
	);

}
