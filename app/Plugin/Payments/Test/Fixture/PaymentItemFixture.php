<?php
/**
 * PaymentItemFixture
 *
 */
class PaymentItemFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'foreign_plugin' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_obj' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'payment_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'label' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 105, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'owner' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 105, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'fee' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2'),
		'fee_type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'fee_data' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_lined_items_transactions1' => array('column' => 'payment_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		// James Mckee's pending app
		array(
			'id' => 4169,
			'foreign_plugin' => 'Licenses',
			'foreign_obj' => 'Application',
			'foreign_key' => 4370,
			'payment_id' => 3427,
			'label' => 'License Type #2 Initial',
	        'owner' => 'Mckee, James F',
			'fee' => '60.00',
			'fee_type' => '',
			'fee_data' => 'test',
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45'
		)
	);
}
