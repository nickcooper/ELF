<?php
App::uses('OutputDocumentBatchItem', 'OutputDocuments.Model');
App::uses('CakeEvent', 'Event');

class OutputDocumentBatchItemTest extends CakeTestCase
{
	public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'contact',
        'plugin.Accounts.account',
        'plugin.Accounts.education_degree',
        'plugin.Accounts.degree',
        'plugin.Accounts.manager',
        'plugin.Accounts.other_license',
        'plugin.Accounts.reference',
        'plugin.Accounts.program',
        'plugin.Accounts.practical_work_experience',
        'plugin.Accounts.practical_work_experience_type',
        'plugin.Accounts.practical_work_percentage',
        'plugin.Accounts.practical_work_percentage_type',
        'plugin.Accounts.work_experience',
        'plugin.Accounts.work_experience_type',
        'plugin.Accounts.work_experiences_work_experience_type',
        'plugin.AddressBook.address',
        'plugin.ContinuingEducation.course_catalog',
        'plugin.ContinuingEducation.course_catalogs_license_type',
        'plugin.ContinuingEducation.course_roster',
        'plugin.ContinuingEducation.course_section',
        'plugin.ContinuingEducation.exam_score',
        'plugin.ContinuingEducation.training_provider',
        'plugin.Firms.firm',
        'plugin.Firms.firm_type',
        'plugin.Licenses.application',
        'plugin.licenses.application_status',
        'plugin.Licenses.application_submission',
        'plugin.Licenses.application_type',
        'plugin.Licenses.app_lic_credit_hour',
        'plugin.Licenses.contractor',
        'plugin.Licenses.element',
        'plugin.Licenses.element_license_type',
        'plugin.Licenses.insurance_information',
        'plugin.Licenses.license',
        'plugin.Licenses.license_expire_reason',
        'plugin.Licenses.license_gap',
        'plugin.Licenses.license_number',
        'plugin.Licenses.license_status',
        'plugin.Licenses.license_type',
        'plugin.Licenses.license_type_conversion',
        'plugin.Licenses.license_type_variant',
        'plugin.Licenses.license_variant',
        'plugin.Licenses.question',
        'plugin.Licenses.question_answer',
        'plugin.Licenses.reciprocal',
        'plugin.Licenses.screening_question',
        'plugin.Licenses.screening_answer',
        'plugin.Licenses.variant',
        'plugin.Notes.note',
        'plugin.Payments.fee',
        'plugin.Payments.payment',
        'plugin.Payments.payment_item',
        'plugin.Uploads.upload',

        'plugin.OutputDocuments.output_document_batch',
        'plugin.OutputDocuments.output_document_batch_item',
	);

    public function setUp()
    {
        parent::setup();
        $this->OutputDocumentBatchItem = ClassRegistry::init('OutputDocuments.OutputDocumentBatchItem');
        $this->OutputDocumentBatch = ClassRegistry::init('OutputDocuments.OutputDocumentBatch');
    }

    /**
     * Test OutputDocumentBatchItem::queue()
     *
     * @return void
     * @access public
     */
    public function testQueue()
    {
    	$params = array(
    		'fp' => 'Accounts',
    		'fo' => 'Account',
    		'fk' => 1,
    		'doc_type' => 'initial_certification_letter',
            'trigger' => 'initial'
    	);
    	$data = array(
			'License' => array(
				'id' => 1
			)
		);
    	$this->OutputDocumentBatchItem->queue($params, 'Letter', $data);

    	$batch_item = $this->OutputDocumentBatchItem->find(
    		'first',
    		array(
    			'conditions' => array(
    				'OutputDocumentBatchItem.output_document_type' => 'initial_certification_letter',
    				'OutputDocumentBatchItem.foreign_plugin' => 'Accounts',
    				'OutputDocumentBatchItem.foreign_obj' => 'Account',
    				'OutputDocumentBatchItem.foreign_key' => '1',
    			)
    		)
    	);

    	$passed = (is_array($batch_item) && count($batch_item) > 0) ? true : false;

        $this->assertTrue($passed);
    }

    /**
     * Test OutputDocumentBatchItem::queue_by_event()
     *
     * @return void
     * @access public
     */
    public function testQueue_by_event()
    {
        $event = new CakeEvent(
            'fake_event', 
            $this, 
            array(
                'queue_docs' => array(
                    array(
                        'fp' => 'Accounts',
                        'fo' => 'Account',
                        'fk' => 3284,
                        'doc_type' => 'initial_certification_letter',
                        'trigger' => 'initial',
                        'license_id' => 6972
                    )
                )
            )
        );

        $this->assertTrue($this->OutputDocumentBatchItem->queue_by_event($event), 'Failed to queue by event.');
    }

    /**
     * Test OutputDocumentBatchItem::getQueueCount()
     *
     * @return void
     * @access public
     */
    public function testGetQueueCount()
    {
        $this->assertEqual($this->OutputDocumentBatchItem->getQueueCount('Initial Certification Letter'), 1, 'Returned count is not the expected count');
    }

    /**
     * Test OutputDocumentBatchItem::batchItems()
     *
     * @return void
     * @access public
     */
    public function testBatchItems()
    {
        $batch_id = $this->OutputDocumentBatchItem->batchItems('Initial Certification Letter');
        $this->assertTrue($this->OutputDocumentBatch->exists($batch_id));
    }

    /**
     * Test OutputDocumentBatchItem::getItemsForBatch()
     *
     * @return void
     * @access public
     */
    public function testGetItemsForBatch()
    {
        $batch_items = $this->OutputDocumentBatchItem->getItemsForBatch('Initial Certification Letter');

        $this->assertEqual(count($batch_items), 1, 'Failed getting items for batch.');
    }
}