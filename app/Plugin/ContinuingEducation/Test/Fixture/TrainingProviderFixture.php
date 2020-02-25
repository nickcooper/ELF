<?php
/**
 * TrainingProviderFixture
 *
 */
class TrainingProviderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
 	public $fields = array(
 		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
 		'legacy_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
 		'label' => array('type' => 'string', 'null' => false, 'unique' => 1, 'length' => 150),
 		'abbr' => array('type' => 'string', 'null' => true, 'unique' => 1, 'default' => null, 'length' => 5),
 		'website' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255),
 		'training_plan' => array('type' => 'text', 'null' => false),
 		'equipment' => array('type' => 'text', 'null' => false),
 		'no_mail' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
 		'no_public_contact' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
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
	 		'legacy_id' => 'legacy id 1',
	 		'label' => 'Training Provider 1',
	 		'abbr' => 'TP1',
	 		'website' => null,
	 		'training_plan' => 'This is the training plan answer...',
	 		'equipment' => 'This is the equipment answer...',
	 		'no_mail' => 1,
	 		'no_public_contact' => 1,
	 		'created' => '2014-05-02 13:37:00',
	 		'modified' => '2014-05-02 13:37:00',
	 	),
 	);
}