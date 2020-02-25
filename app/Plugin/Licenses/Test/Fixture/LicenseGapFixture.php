<?php
class LicenseGapFixture extends CakeTestFixture
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'license_id' => array('type' => 'integer', 'length' => '11', 'null' => false),
        'application_id' => array('type' => 'integer', 'length' => '11', 'null' => false),
        'effective_date' => 'date',
        'previous_application_id' => array('type' => 'integer', 'length' => '11', 'null' => false),
        'previous_expire_date' => 'date',
        'diff_days' => array('type' => 'integer', 'length' => '11', 'null' => true),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1,
            'license_id' => 1,
            'application_id' => 2,
            'effective_date' => '2016-06-22 16:44:45',
            'previous_application_id' => 1,
            'previous_expire_date' => '2016-06-20 16:44:45',
            'diff_days' => 2,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        )
    );
}