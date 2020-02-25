<?php
App::uses('License', 'Licenses.Model');
App::uses('Contractor', 'Licenses.Model');

class ContractorTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.licenses.contractor'
    );

    public function setUp()
    {
        parent::setUp();
        $this->License = ClassRegistry::init('License');
        $this->Contractor = ClassRegistry::init('Contractor');
    }

    public function testSave()
    {
        $data = $this->Contractor->findById(1);

        if ($this->Contractor->save($data))
        {
            $this->assertTrue(true);
        }
    }
}