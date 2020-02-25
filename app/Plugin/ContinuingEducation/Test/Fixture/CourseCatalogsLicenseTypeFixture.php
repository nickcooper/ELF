<?php
/**
 * CourseCatalogsLicenseTypeFixture
 *
 */
class CourseCatalogsLicenseTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'course_catalog_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'license_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'initial' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'renewal' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
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
			'license_type_id' => 1,
			'initial' => 1,
			'renewal' => 1,
			'created' => '2013-08-09 14:16:06',
			'modified' => '2013-08-09 14:16:06'
		),
		array(
			'id' => 2,
			'course_catalog_id' => 2,
			'license_type_id' => 1,
			'initial' => 1,
			'renewal' => 0,
			'created' => '2013-08-19 14:16:06',
			'modified' => '2013-08-19 14:16:06'
		),
	);

}
