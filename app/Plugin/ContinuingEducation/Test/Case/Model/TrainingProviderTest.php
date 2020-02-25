<?php
App::uses('TrainingProvider', 'ContinuingEducation.Model');

class TrainingProviderTest extends CakeTestCase
{
	public $fixtures = array(
        'plugin.continuing_education.training_provider',
        'plugin.logging.audit',
        'plugin.logging.audit_delta',
    );

    public function setUp()
    {
        parent::setUp();

        $this->TrainingProvider = ClassRegistry::init('ContinuingEducation.TrainingProvider');
    }

    /**
     * Test TrainingProvider::testAdd()
     *
     * Adds a TP record to the database
     *
     * @return void
     * @access public
     */
    public function testAdd()
    {
        // find all existing TPs in the fixture
        $tp_count = $this->TrainingProvider->find('count');

        // build the test data array
        $data = array();

        $data['TrainingProvider']['legacy_id'] = 'legacy id 2';
        $data['TrainingProvider']['label'] = 'Training Provider 2';
        $data['TrainingProvider']['abbr'] = 'TP2';
        $data['TrainingProvider']['website'] = null;
        $data['TrainingProvider']['training_plan'] = 'This is the training plan answer...';
        $data['TrainingProvider']['equipment'] = 'This is the equipment answer...';
        $data['TrainingProvider']['no_mail'] = 0;
        $data['TrainingProvider']['no_public_contact'] = 0;
        $data['TrainingProvider']['created'] = '2014-05-02 21:11:11';
        $data['TrainingProvider']['modified'] = '2014-05-02 21:11:11';

        // add the new TP
        $this->TrainingProvider->add($data);

        // get a new count of TP records
        $tp_count2 = $this->TrainingProvider->find('count');

        // confirm that the new record count increased by 1
        $this->assertEquals($tp_count2, $tp_count+1, 'TP add did not increase by 1');
    }
}