<?php
/**
 * ReferenceFixture
 *
 */
class ReferenceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'account_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'notes' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'notes' => '1 Lorem ipsum dolor sit amet, aliquet feugiat. Nulla vestibulum massa neque ut et, id hendrerit convallis.',
			'created' => '2013-08-09 14:19:21',
			'modified' => '2013-08-09 14:19:21'
		),
		array(
			'id' => 2,
			'account_id' => 1,
			'notes' => '2 Lorem ipsum dolor sit amet, aliquet feugiat. Nulla vestibulum massa neque ut et, id hendrerit convallis.',
			'created' => '2013-08-09 14:19:21',
			'modified' => '2013-08-09 14:19:21'
		),
		array(
			'id' => 3,
			'account_id' => 1,
			'notes' => '3 Lorem ipsum dolor sit amet, aliquet feugiat. Nulla vestibulum massa neque ut et, id hendrerit convallis.',
			'created' => '2013-08-09 14:19:21',
			'modified' => '2013-08-09 14:19:21'
		),
	);

}
