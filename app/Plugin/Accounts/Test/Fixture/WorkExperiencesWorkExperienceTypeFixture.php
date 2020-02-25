<?php
/**
 * WorkExperiencesWorkExperienceTypeFixture
 *
 */
class WorkExperiencesWorkExperienceTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'work_experience_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'work_experience_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
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
			'work_experience_id' => 1,
			'work_experience_type_id' => 1,
			'created' => '2013-08-09 15:48:34',
			'modified' => '2013-08-09 15:48:34'
		),
	);

}
