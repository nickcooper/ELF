<?php
/**
 * FirmFixture
 *
 */
class FirmFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'legacy_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'firm_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'label' => array('type' => 'string', 'null' => false, 'default' => null),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null),
		'no_mail' => array('tinyint(1)', 'null' => false, 'default' => '0'),
        'no_public_contact' => array('tinyint(1)', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
	);

	public $records = array(
		array(
        	'id' => 1,
			'legacy_id' => 1,
			'firm_type_id' => 2,
			'label' => 'Test Firm Alpha',
			'slug' => 'test-firm-alpha',
			'alias' => null,
            'created' => '2013-03-18 10:39:23',
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
        	'id' => 2,
			'legacy_id' => 1,
			'firm_type_id' => 1,
			'label' => 'Test Firm Baker',
			'slug' => 'test-firm-baker',
			'alias' => null,
            'created' => '2013-03-18 10:39:23',
            'modified' => '2013-03-18 10:41:31'
        ),
	);
}
