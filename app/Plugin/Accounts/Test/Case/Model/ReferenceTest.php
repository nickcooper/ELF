<?php
App::uses('Reference', 'Accounts.Model');

class ReferenceTest extends CakeTestCase
{
	public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

		'contact',
		'plugin.Accounts.reference',
		'plugin.AddressBook.address',
	);

	public function setUp()
	{
		$this->Reference = ClassRegistry::init('Accounts.Reference');
	}

	public function testAdd($id = null)
	{
		$references = $this->Reference->find(
			'all',
			array(
				'contain' => array(
					'Contact',
					'Address'
				),
				'conditions' => array(
					'Reference.account_id' => 1
				)
			)
		);
		$passed = false;
		if ($this->Reference->saveAll($references))
		{
			$passed = true;
		}
		$this->assertTrue($passed);
	}
}