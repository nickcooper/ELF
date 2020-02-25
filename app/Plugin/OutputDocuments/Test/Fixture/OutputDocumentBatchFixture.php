<?php
class OutputDocumentBatchFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'label' => array('type' => 'string', 'length' => '255', 'null' => false),
		'batch_date' => 'datetime',
		'created' => 'datetime',
		'modified' => 'datetime'
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'label' => 'Initial Certification Letter',
			'batch_date' => '2014-05-01 09:00:00',
			'created' => '2014-05-01 09:00:00',
			'modified' => '2014-05-01 09:00:00'
		)
	);
}