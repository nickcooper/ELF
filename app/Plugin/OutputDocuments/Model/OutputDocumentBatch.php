<?php
/**
 * OutputDocumentBatch Model
 *
 * @package OutputDocuments.Model
 * @author  Iowa Interactive, LLC.
 */
class OutputDocumentBatch extends OutputDocumentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'OutputDocumentBatch';

    /**
     * hasMany Relationships
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'OutputDocumentBatchItem' => array(
            'className' => 'OutputDocuments.OutputDocumentBatchItem',
            'foreignKey' => 'output_document_batch_id'
        )
    );

    /**
     * gets previous batch dates and counts for label
     *
     * @param string $label label
     *
     * @return array
     * @access public
     */
    public function getPreviousBatches ($label)
    {
        $batches = $this->find(
            'all', array(
                'conditions' => array(
                    'OutputDocumentBatch.label' => $label
                ),
                'limit' => 10,
                'order' => 'OutputDocumentBatch.batch_date DESC'
            )
        );
        $data = array();
        foreach ($batches as $batch)
        {
            $data[] = array(
                'id' => $batch['OutputDocumentBatch']['id'],
                'batch_date' => $batch['OutputDocumentBatch']['batch_date'],
                'count' => $this->getBatchCount($batch['OutputDocumentBatch']['id'])
            );
        }
        return $data;
    }

    /**
     * gets batch count for label
     *
     * @param int $id batch id
     *
     * @return int
     * @access public
     */
    public function getBatchCount ($id = null)
    {
        if (!preg_match('/^[1-9]{1}[0-9]*$/', $id))
        {
            throw new NotFoundException(__('Invalid ID.'));
        }
        return $this->OutputDocumentBatchItem->find(
            'count', array(
                'conditions' => array(
                    'OutputDocumentBatchItem.output_document_batch_id' => $id
                )
            )
        );
    }

    /**
     * gets last batch date for label
     *
     * @param string $label label
     *
     * @return string
     * @access public
     */
    public function getLastBatchDate ($label)
    {
        $last_batch = $this->find(
            'first', array(
                'fields' => array('OutputDocumentBatch.batch_date'),
                'conditions' => array(
                    'OutputDocumentBatch.label' => $label),
                'order' => 'OutputDocumentBatch.batch_date DESC'
            )
        );

        if ($last_batch['OutputDocumentBatch']['batch_date'] != null)
        {
            return date('m-d-Y', strtotime($last_batch['OutputDocumentBatch']['batch_date']));
        }
        return false;
    }

    /**
     * creates batch record, returns new batch id
     *
     * @param string $label label
     * @param string $now   datetime
     *
     * @return int
     * @access public
     */
    public function createBatch($label, $now)
    {
        $data = array();
        $data['OutputDocumentBatch']['label'] = $label;
        $data['OutputDocumentBatch']['batch_date'] = $now;

        $batch = $this->add($data);
        if (!$batch)
        {
            throw new NotFoundException(__('Could not save batch.'));
        }
        return $this->getLastInsertID();

    }

    /**
     * gets batch and batch item records
     *
     * @param int $id batch id
     *
     * @return array
     * @access public
     */
    public function getBatch($id = null)
    {
        if (!preg_match('/^[1-9]{1}[0-9]*$/', $id))
        {
            throw new NotFoundException(__('Invalid ID.'));
        }
        return $this->find(
            'first', array(
                'contain' => array(
                    'OutputDocumentBatchItem'),
                'conditions' => array(
                    'OutputDocumentBatch.id' => $id
                )
            )
        );
    }
}