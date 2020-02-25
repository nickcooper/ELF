<?php
/**
 * PracticalWorkExperienceTypeFixture
 *
 */
class PracticalWorkExperienceTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'label' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'order' => array('type' => 'integer', 'null' => true, 'default' => null),
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
			'program_id' => 1,
			'label' => 'Lorem ipsum dolor sit amet',
			'order' => 1,
			'created' => '2013-08-09 15:48:48',
			'modified' => '2013-08-09 15:48:48'
		),
	);

}
