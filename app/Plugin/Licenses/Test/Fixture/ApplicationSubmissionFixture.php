<?php
/**
 * ApplicationSubmissionFixture
 *
 */
class ApplicationSubmissionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'submit_paid_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'materials_received' => array('type' => 'date', 'null' => true, 'default' => null),
		'approved_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'application_data' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'app_id' => array('column' => 'application_id', 'unique' => 0),
			'approved_date' => array('column' => 'approved_date', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
        array(
            'id' => 1,
            'application_id' => 1,
            'submit_paid_date' => '2013-06-24 16:44:45',
            'materials_received' => '2013-06-24 16:44:45',
            'approved_date' => '2013-09-22 16:44:45',
            'application_data' => NULL,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        array(
            'id' => 2,
            'application_id' => 1,
            'submit_paid_date' => '2013-06-24 16:44:45',
            'materials_received' => '2013-06-24 16:44:45',
            'approved_date' => '2013-09-22 16:44:45',
            'application_data' => NULL,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        array(
            'id' => 3,
            'application_id' => 1,
            'submit_paid_date' => '2013-06-24 16:44:45',
            'materials_received' => '2013-06-24 16:44:45',
            'approved_date' => '2013-09-22 16:44:45',
            'application_data' => NULL,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        // James Mckee's pending app submission
        array(
            'id' => 7544,
            'application_id' => 4370,
            'submit_paid_date' => '2013-06-24 16:44:45',
            'materials_received' => null,
            'approved_date' => null,
            'application_data' => null,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
	);

}
