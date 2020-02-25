<?php
/**
 * OtherLicenseFixture
 *
 */
class OtherLicenseFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'foreign_plugin' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_obj' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null),
		'label' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'jurisdiction' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'license_number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'issue_date' => array('type' => 'date', 'null' => false, 'default' => null),
		'expire_date' => array('type' => 'date', 'null' => false, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'obtained_by_exam' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'foreign_plugin' => 'Lorem ipsum dolor sit amet',
			'foreign_obj' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 1,
			'label' => 'Lorem ipsum dolor sit amet',
			'jurisdiction' => 'Lorem ipsum dolor sit amet',
			'license_number' => 'Lorem ipsum dolor sit amet',
			'issue_date' => '2013-08-09',
			'expire_date' => '2013-08-09',
			'active' => 1,
			'created' => '2013-08-09 14:11:38',
			'modified' => '2013-08-09 14:11:38',
			'obtained_by_exam' => 1
		),
	);

}
