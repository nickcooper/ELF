<?php
App::uses('OutputDocumentBatch', 'OutputDocuments.Model');

class OutputDocumentBatchTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.OutputDocuments.output_document_batch',
        'plugin.OutputDocuments.output_document_batch_item',
    );

    public function setUp()
    {
        parent::setup();
        $this->OutputDocumentBatch = ClassRegistry::init('OutputDocuments.OutputDocumentBatch');
    }

    /**
     * Test OutputDocumentBatch::getPreviousBatches()
     *
     * @return void
     * @access public
     */
    public function testGetPreviousBatches()
    {
        $expected_data = array(
            array(
                'id' => '1',
                'batch_date' => '2014-05-01 09:00:00',
                'count' => 2
            )
        );

        $previous_batches = $this->OutputDocumentBatch->getPreviousBatches('Initial Certification Letter');

        $this->assertEquals($expected_data, $previous_batches, 'Returned batch data does not match expected data.');
    }

    /**
     * Test OutputDocumentBatch::getBatchCount()
     *
     * @return void
     * @access public
     */
    public function testGetBatchCount()
    {
        $count = $this->OutputDocumentBatch->getBatchCount(1);

        $this->assertEquals(2, $count, 'Returned count does not match expected count.');
    }

    /**
     * Test OutputDocumentBatch::getLastBatchDate()
     *
     * @return void
     * @access public
     */
    public function testGetLastBatchDate()
    {
        $batch_date = $this->OutputDocumentBatch->getLastBatchDate('Initial Certification Letter');

        $this->assertEquals('05-01-2014', $batch_date, 'Returned batch date does not match expected date.');
    }

    /**
     * Test OutputDocumentBatch::createBatch()
     *
     * @return void
     * @access public
     */
    public function testCreateBatch()
    {
        $datetime = date('Y-m-d H:i:s');
        $date = date('m-d-Y');
        $insert_id = $this->OutputDocumentBatch->createBatch('Initial Certification Letter', $datetime);
        $pass = false;
        if ($date == $this->OutputDocumentBatch->getLastBatchDate('Initial Certification Letter'))
        {
            $pass = true;
        }
        $this->assertTrue($pass, 'Create Batch failed.');
    }

    /**
     * Test OutputDocumentBatch::getBatch()
     *
     * @return void
     * @access public
     */
    public function testGetBatch()
    {
        $batch = $this->OutputDocumentBatch->getBatch(1);
        $pass = false;
        if (isset($batch['OutputDocumentBatch']['id']) && $batch['OutputDocumentBatch']['id'] == 1)
        {
            $pass = true;
        }
        $this->assertTrue($pass, 'Get Batch failed.');
    }


}