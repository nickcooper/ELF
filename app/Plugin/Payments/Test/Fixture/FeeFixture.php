<?php
class FeeFixture extends CakeTestFixture
{
    /**
     * Import the Fee model and table data
     *
     * Since Fees are defined in source/deployment/<agency>/deploy.csv
     * we can assume our fees table already has data. So let's use that
     * data for our unit tests.
     */

    // commented out 09/24/13. Was breaking the nightly unit test. (Nick Cooper)
    //public $import = array('model' => 'Payments.Fee', 'records' => true);

    public $fields = array(
    	'id' => array('type' => 'integer', 'key' => 'primary'),
    	'label' => array('type' => 'string', 'length' => 100, 'null' => false),
    	'foreign_plugin' => array('type' => 'string', 'length' => 45, 'null' => true),
    	'foreign_obj' => array('type' => 'string', 'length' => 45, 'null' => true),
    	'foreign_key' => array('type' => 'integer', 'length' => 10, 'null' => true),
    	'fee_key' => array('type' => 'string', 'length' => 150, 'null' => false),
    	'fee' => array('type' => 'float', 'length' => 10, 'null' => true),
    	'apply_tax' => array('type' => 'integer', 'length' => 1, 'null' => true),
    	'removable' => array('type' => 'integer', 'length' => 1, 'null' => true),
    	'created' => 'datetime',
    	'modified' => 'datetime',
	);
	public $records = array(
		array(
			'id' => 1,
	    	'label' => 'Initial License Type #1',
	    	'foreign_plugin' => 'Licenses',
	    	'foreign_obj' => 'LicenseType',
	    	'foreign_key' => 1,
	    	'fee_key' => 'lt1_initial',
	    	'fee' => '125.00',
	    	'apply_tax' => 1,
	    	'removable' => 1,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
		array(
			'id' => 2,
	    	'label' => 'Initial License Type #2',
	    	'foreign_plugin' => 'Licenses',
	    	'foreign_obj' => 'LicenseType',
	    	'foreign_key' => 1,
            'fee_key' => 'lt2_initial',
	    	'fee' => '25.00',
	    	'apply_tax' => 1,
	    	'removable' => 1,
			'created' => '2013-06-24 16:44:45',
			'modified' => '2013-06-24 16:44:45',
		),
	);
}