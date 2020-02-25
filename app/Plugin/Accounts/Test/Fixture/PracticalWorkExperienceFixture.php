<?php
/**
 * PracticalWorkExperienceFixture
 *
 */
class PracticalWorkExperienceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'account_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'practical_work_experience_type_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'months' => array('type' => 'integer', 'null' => true, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'account_id' => 1,
			'practical_work_experience_type_id' => 1,
			'months' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-08-09 14:03:13',
			'modified' => '2013-08-09 14:03:13'
		),
	);

}
