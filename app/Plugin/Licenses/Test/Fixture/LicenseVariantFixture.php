<?php
class LicenseVariantFixture extends CakeTestFixture
{
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'license_id' => array('type' => 'integer', 'length' => '10', 'null' => true),
		'variant_id' => array('type' => 'integer', 'length' => '10', 'null' => true),
		'created' => 'datetime',
		'modified' => 'datetime'
	);
}