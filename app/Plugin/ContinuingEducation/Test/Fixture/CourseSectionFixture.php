<?php
/**
 * CourseSectionFixture
 *
 */
class CourseSectionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'course_catalog_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'address_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'training_provider_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'account_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'label' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'course_section_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'start_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'end_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'enabled' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
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
			'course_catalog_id' => 1,
			'address_id' => 1,
			'training_provider_id' => 1,
			'account_id' => 1,
			'label' => 'Lorem ipsum dolor sit amet',
			'course_section_number' => 'Lorem ipsum dolor sit amet',
			'start_date' => '2013-08-09 15:54:54',
			'end_date' => '2013-08-09 15:54:54',
			'enabled' => 1,
			'created' => '2013-08-09 15:54:54',
			'modified' => '2013-08-09 15:54:54'
		),
		array(
			'id' => 2,
			'course_catalog_id' => 2,
			'address_id' => 1,
			'training_provider_id' => 1,
			'account_id' => 1,
			'label' => 'Lorem ipsum dolor sit amet',
			'course_section_number' => 'Lorem ipsum dolor sit amet',
			'start_date' => '2013-08-09 15:54:54',
			'end_date' => '2013-08-09 15:54:54',
			'enabled' => 1,
			'created' => '2013-08-09 15:54:54',
			'modified' => '2013-08-09 15:54:54'
		),
	);

}
