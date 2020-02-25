<?php
App::uses('Firm', 'Firms.Model');

class FirmTest extends CakeTestCase
{
	public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'contact',
        'plugin.Accounts.account',
        'plugin.Accounts.manager',
        'plugin.AddressBook.address',
        'plugin.Firms.firm',
        'plugin.Firms.firm_type',
        'plugin.Licenses.license',
        'plugin.Licenses.license_status',
        'plugin.Licenses.license_type',
        'plugin.Notes.note',
    );

    public function setUp()
    {
        parent::setUp();

        $this->Firm = ClassRegistry::init('Firms.Firm');
    }

    public function testAdd()
    {
        $this->assertEquals(1, 1);
    }
}