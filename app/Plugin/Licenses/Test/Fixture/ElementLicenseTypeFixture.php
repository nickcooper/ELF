<?php
class ElementLicenseTypeFixture extends CakeTestFixture
{
  public $fields = array(
    'id' => array('type' => 'integer', 'key' => 'primary'),
    'license_type_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
    'element_id' => array('type' => 'integer', 'length' => '10', 'null' => false),
    'label' => array('type' => 'string', 'length' => '150', 'null' => true),
    'order' => array('type' => 'integer', 'length' => '10', 'null' => false, 'default' => '999'),
    'initial_required' => array('type' => 'integer', 'length' => '1', 'null' => false, 'default' => '0'),
    'renewal_required' => array('type' => 'integer', 'length' => '1', 'null' => false, 'default' => '0'),
    'created' => 'datetime',
    'modified' => 'datetime'
  );
  
  public $records = array(
    // ElementLicenseType for license type 1
    array(
      'id' => 1,
      'license_type_id' => 1,
      'element_id' => 1,
      'label' => null,
      'order' => 999,
      'initial_required' => 1,
      'renewal_required' => 1,
      'created' => '2013-06-24 16:44:45',
      'modified' => '2013-06-24 16:44:45'
    ),
  );
}