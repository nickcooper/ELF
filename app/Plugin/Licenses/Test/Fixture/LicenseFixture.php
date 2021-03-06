<?php
class LicenseFixture extends CakeTestFixture
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'application_id' => array('type' => 'integer', 'length' => '10', 'null' => true),
        'foreign_plugin' => array('type' => 'string', 'length' => '45', 'null' => true),
        'foreign_obj' => array('type' => 'string', 'length' => '45', 'null' => false),
        'foreign_key' => array('type' => 'integer', 'length' => '10', 'null' => false),
        'license_type_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
        'license_variant_id' => array('type' => 'integer', 'length' => '10', 'null' => true),
        'license_status_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
        'issued_date' => 'datetime',
        'expire_date' => 'datetime',
        'not_renewing' => array('type' => 'integer', 'length' => '4', 'null' => false, 'default' => '0'),
        'pending' => array('type' => 'integer', 'length' => '4', 'null' => false, 'default' => '0'),
        'license_number' => array('type' => 'string', 'length' => '45', 'null' => false),
        'license_number_id' => array('type' => 'integer', 'length' => '11', 'null' => false),
        'label' => array('type' => 'string', 'length' => '150', 'null' => true),
        'legacy_number' => array('type' => 'string', 'length' => '45', 'null' => true),
        'manually_edited' => array('type' => 'integer', 'length' => '1', 'null' => false, 'default' => 0),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        // Active License
        array(
            'id' => 1,
            'application_id' => 1,
            'foreign_plugin' => 'Accounts',
            'foreign_obj' => 'Account',
            'foreign_key' => 1,
            'license_type_id' => 1,
            'license_variant_id' => null,
            'license_status_id' => 1,
            'issued_date' => '2013-06-24 16:44:45',
            'expire_date' => '2016-06-24 16:44:45',
            'not_renewing' => 0,
            'pending' => 0,
            'license_number' => '0000001-ML',
            'license_number_id' => 1,
            'label' => 'Doe, John',
            'legacy_number' => '00101-ML',
            'manually_edited' => 0,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        array(
            'id' => 2,
            'application_id' => 2,
            'foreign_plugin' => 'Firms',
            'foreign_obj' => 'Firm',
            'foreign_key' => 1,
            'license_type_id' => 4,
            'license_variant_id' => null,
            'license_status_id' => 1,
            'issued_date' => '2013-06-24 16:44:45',
            'expire_date' => '2016-06-24 16:44:45',
            'not_renewing' => 0,
            'pending' => 0,
            'license_number' => '0000001-ML',
            'license_number_id' => 1,
            'label' => 'Doe, John',
            'legacy_number' => '00101-ML',
            'manually_edited' => 0,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        // James Mckee License 1
        array(
            'id' => 6853,
            'application_id' => 0,
            'foreign_plugin' => 'Accounts',
            'foreign_obj' => 'Account',
            'foreign_key' => 3284,
            'license_type_id' => 1,
            'license_variant_id' => null,
            'license_status_id' => 1,
            'issued_date' => '2013-06-24 16:44:45',
            'expire_date' => '2016-06-24 16:44:45',
            'not_renewing' => 0,
            'pending' => 0,
            'license_number' => '0006853-ML',
            'license_number_id' => 6853,
            'label' => 'Mckee, James',
            'legacy_number' => '005093',
            'manually_edited' => 0,
            'created' => '2013-06-24 16:44:45',
            'modified' => '2013-06-24 16:44:45',
        ),
        // James Mckee License 2, incomplete pending
        array(
            'id' => 6972,
            'application_id' => 4370,
            'foreign_plugin' => 'Accounts',
            'foreign_obj' => 'Account',
            'foreign_key' => 3284,
            'license_type_id' => 2,
            'license_variant_id' => null,
            'license_status_id' => 1,
            'issued_date' => '2013-06-25 16:44:45',
            'expire_date' => '2016-06-25 16:44:45',
            'not_renewing' => 0,
            'pending' => 1,
            'license_number' => '0006972-ML',
            'license_number_id' => 6972,
            'label' => 'Mckee, James',
            'legacy_number' => '005093',
            'manually_edited' => 0,
            'created' => '2013-06-25 16:44:45',
            'modified' => '2013-06-25 16:44:45',
        ),
    );
}