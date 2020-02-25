<?php
App::uses('Configuration', 'Configuration.Model');

class ConfigurationTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.configuration.configuration',
    );

    public function setUp()
    {
        parent::setUp();

        $this->Configuration = ClassRegistry::init('Configuration');
    }

    public function testSave()
    {
    	$pass = false;
    	$record = $this->Configuration->find('first');

        if($this->Configuration->save($record))
    	{
    		$pass = true;
    	}

        $this->assertTrue($pass);
    }
}