<?php
/**
 * FirmTypeFixture
 *
 */
class FirmTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'label' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);

	public $records = array(
		array(
        	'id' => 1,
			'label' => 'Corporation',
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
        	'id' => 2,
			'label' => 'Sole Proprietorship',
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
	);
}
