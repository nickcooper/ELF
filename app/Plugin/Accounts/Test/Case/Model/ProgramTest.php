<?php
App::uses('Program', 'Accounts.Model');
App::uses('Group', 'Accounts.Model');
App::uses('GroupProgram', 'Accounts.Model');

class ProgramTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.accounts.program',
        'plugin.accounts.group_program',
        'plugin.accounts.group',
    );

    public $dropTables = true;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // load the Program model
        $this->Program = ClassRegistry::init('Accounts.Program');
    }

    /**
     * testAdd method
     *
     * @return void
     */
    public function testAdd($id = null)
    {
        // get a data record
        $data = $this->Program->find('first');

        // remove the record id for testing the add method
        $data['Program']['id'] = null;
        $data['Program']['label'] = 'My New Program Name';

        // attempt to add new record
        $this->Program->create();
        $this->Program->add($data);

        //generic validation for building the unit testing framework for each plugin
        $this->assertEquals($this->Program->getAffectedRows(), 1);
    }

    /**
     * testEdit method
     *
     * @return void
     */
    public function testEdit($id = null)
    {
        // get a data record
        $orig_data = $this->Program->find('first');
        $data = $orig_data;

        // change a field value
        $data['Program']['enabled'] = ($orig_data['Program']['enabled'] ? 0 : 1);

        // attempt to add new record
        $this->Program->create();
        $this->Program->edit($data);

        // get the newly updated record
        $result = $this->Program->find(
            'first',
            array(
                'conditions' => array('Program.id' => $orig_data['Program']['id']),
                'fields' => array('enabled')
            )
        );

        // define the expected outcome
        $expected = array('Program' => array('enabled' => $data['Program']['enabled']));

        //generic validation for building the unit testing framework for each plugin
        $this->assertEquals($expected, $result);
    }

    /**
     * getProgramById method
     *
     * @return void
     */
    public function testGetProgramById()
    {
        $result = $this->Program->getProgramById(1);
        $this->assertEquals(1, $result['Program']['id']);
    }

    /**
     * getProgramByAbbr method
     *
     * @return void
     */
    public function testGetProgramByAbbr()
    {
        $result = $this->Program->getProgramByAbbr('PRG1');
        $this->assertEquals(1, $result['Program']['id']);
    }
}