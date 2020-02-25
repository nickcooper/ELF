<?php
App::uses('License', 'Licenses.Model');

class ThirdPartyTestTest extends CakeTestCase
{
	public $fixtures = array(
        'plugin.Licenses.application',
        'plugin.logging.audit',
        'plugin.logging.audit_delta',
        'plugin.Licenses.third_party_test',
        'plugin.Uploads.upload',
    );

    public function setUp()
    {
        parent::setUp();

        $this->ThirdPartyTest = ClassRegistry::init('Licenses.ThirdPartyTest');
    }

    /**
     * Test ThirdPartyTest::testAdd()
     *
     * Adds a TPT record to the database
     *
     *      * @param int $foreign_key     expecting foreign record id
     * @return void
     * @access public
     */
    public function testAdd()
    {
        // find all existing TPTs in the fixture
        $tpt_count = $this->ThirdPartyTest->find('count');

        // build the test data array
        $data = array();

        $data['ThirdPartyTest']['foreign_plugin'] = 'Licenses';
        $data['ThirdPartyTest']['foreign_obj'] = 'Application';
        $data['ThirdPartyTest']['foreign_key'] = '33';
        $data['ThirdPartyTest']['testing_center'] = 'Testing Center 33';
        $data['ThirdPartyTest']['date'] = '2014-04-14 13:07:00';
        $data['ThirdPartyTest']['score'] = 33;
        $data['ThirdPartyTest']['pass'] = 1;
        $data['ThirdPartyTest']['created'] = '2014-04-14 13:07:00';
        $data['ThirdPartyTest']['modified'] = '2014-04-14 13:07:00';

        // add the new TPT
        $this->ThirdPartyTest->add($data);

        // get a new count of TPT records
        $tpt_count2 = $this->ThirdPartyTest->find('count');

        // confirm that the new record count
        $this->assertEquals($tpt_count2, $tpt_count+1, 'TPT add did not increase by 1');
    }

    /**
     * Test ThirdPartyTest::testDelete($id)
     *
     * Deletes a TPT record to the database
     *
     * @param int $id     the TPT record id to be deleted
     *
     * @return void
     * @access public
     */
    public function testDelete($id=null)
    {
        // find all existing TPTs in the fixture
        $tpt_count = $this->ThirdPartyTest->find('count');

        // delete a TPT
        $this->ThirdPartyTest->delete(1);

        // get a new count of TPT records
        $tpt_count2 = $this->ThirdPartyTest->find('count');

        // confirm that the new record count
        $this->assertEquals($tpt_count2, $tpt_count-1, 'TPT delete did not decrease by 1');
    }
}