<?php
App::uses('ApplicationType', 'Licenses.Model');

class ApplicationTypeTest extends CakeTestCase
{
	public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.Licenses.application_type',
        'plugin.licenses.application_status',
	);

    public function setUp()
    {
        parent::setUp();

        $this->ApplicationType = ClassRegistry::init('Licenses.ApplicationType');
        $this->License = ClassRegistry::init('Licenses.License');
    }

    /**
     * Test ApplicationType::getApplicationTypeList()
     *
     * @return void
     * @access public
     */
    public function testGetApplicationTypeList()
    {
        $result = $this->ApplicationType->getApplicationTypeList();

        $expected = array(3 => 'Conversion', 1 => 'Initial', 2 => 'Renewal');

        $this->assertEquals($expected, $result);
    }
}