<?php
/**
 * InsuranceInformationFixture
 *
 */
class InsuranceInformationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'label' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'expire_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'insurance_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'foreign_plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'foreign_obj' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);
}
