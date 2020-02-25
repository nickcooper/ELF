<?php
/**
 * OutputDocumentBatchItem Model
 *
 * @package OutputDocuments.Model
 * @author  Iowa Interactive, LLC.
 */
class OutputDocumentBatchItem extends OutputDocumentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'OutputDocumentBatchItem';

    /**
     * belongsTo Relationships
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'OutputDocumentBatch' => array(
            'className'  => 'OutputDocuments.OutputDocumentBatch',
            'foreignKey' => 'output_document_batch_id'
        )
    );

    /**
     * adds batch item
     *
     * @param array  $params contains foreign plugin/object/key, trigger, doc_type, and custom mixed data
     * @param string $label  label for the record for display purposes
     * @param array  $data   placeholder data
     *
     * @return boolean
     * @access public
     */
    public function queue ($params, $label = null, $data = array())
    {
        if (empty($params['fp']) || empty($params['fo']) || empty($params['fk']) || empty($params['doc_type']) || !$label || count($data) == 0)
        {
            throw new Exception(__("Output Document: queue() missing arguments"));
        }

        // Build batch item record
        $batch_item = array(
            'OutputDocumentBatchItem' => array(
                'output_document_type' => $params['doc_type'],
                'foreign_plugin' => $params['fp'],
                'foreign_obj' => $params['fo'],
                'foreign_key' => $params['fk'],
                'label' => $label,
                'template_data' => serialize($data)
            )
        );

        // check to see if the batch item already exists but hasn't been batched yet
        $previous_batch_item = $this->find(
            'first',
            array(
                'conditions' => array(
                    'OutputDocumentBatchItem.output_document_type' => $params['doc_type'],
                    'OutputDocumentBatchItem.foreign_plugin' => $params['fp'],
                    'OutputDocumentBatchItem.foreign_obj' => $params['fo'],
                    'OutputDocumentBatchItem.foreign_key' => $params['fk'],
                    'OutputDocumentBatchItem.batch_date' => null,
                )
            )
        );

        // add previous batch item id to new batch item data so it's updated
        // instead of inserting a new record
        if ($previous_batch_item)
        {
            $batch_item['OutputDocumentBatchItem']['id'] = $previous_batch_item['OutputDocumentBatchItem']['id'];
        }

        // Insert/update record into database
        if ($this->saveAll($batch_item))
        {
            return true;
        }

        return false;
    }

    public function queue_by_event ($event = array())
    {
        if (!isset($event->data['queue_docs']) || !is_array($event->data['queue_docs']) || count($event->data['queue_docs']) == 0)
        {
            return;
        }

        foreach ($event->data['queue_docs'] as $params)
        {
            if (empty($params['fp']) || empty($params['fo']) || empty($params['fk']) || empty($params['trigger']))
            {
                throw new Exception(__("Output Document: queue() missing arguments"));
            }

            if (!empty($params['doc_type']))
            {
                $doc_types = array(array('type' => $params['doc_type']));
            }
            else
            {
                $doc_types = Configure::read(sprintf('OutputDocuments.triggers.%s', $params['trigger']));
            }
            foreach ($doc_types as $doc_type)
            {
                // get the foreign data for the document
                try
                {
                    $params['doc_type'] = $doc_type['type'];

                    $Model = ClassRegistry::init($params['fp'].'.'.$params['fo']);

                    $method = Configure::read(sprintf('OutputDocuments.docs.%s.data', $params['doc_type']));

                    if (!$method)
                    {
                        throw new Exception(__("Output Document: data method not found in settings."));
                    }
                    $data = $Model->{$method}($params);

                    if (!is_array($data))
                    {
                        throw new Exception(__("Output Document: problem getting foreign data."));
                    }
                }
                catch (Exception $e)
                {
                    throw new Exception($e->getMessage());
                }

                $trigger_label = isset($doc_type['label']) ? $doc_type['label'] : false;
                $doc_label = Configure::read(sprintf('OutputDocuments.docs.%s.label', $doc_type['type']));

                $label = 'Unknown Document Label';
                if ($doc_label)
                {
                    $label = $doc_label;
                }
                if ($trigger_label)
                {
                    $label = $trigger_label;
                }

                return $this->queue($params, $label, $data);
            }
        }
    }

    /**
     * gets count of queued items for label
     *
     * @param string $label label
     *
     * @return int
     * @access public
     */
    public function getQueueCount ($label)
    {
        return $this->find(
            'count', array(
                'conditions' => array(
                    'OutputDocumentBatchItem.label' => $label,
                    'OutputDocumentBatchItem.output_document_batch_id' => null
                )
            )
        );
    }

    /**
     * Generates batch
     *
     * @param string $label label
     *
     * @return void
     * @access public
     */
    public function batchItems ($label)
    {
        // get batch items
        if (!$batch_items = $this->getItemsForBatch($label))
        {
            return false;
        }

        // set the batch time
        $now = date('Y-m-d H:i:s');

        // create the batch record
        $batch_id = $this->OutputDocumentBatch->createBatch($label, $now);

        // update the batch items with the batch id
        foreach ($batch_items as &$batch_item)
        {
            $batch_item['OutputDocumentBatchItem']['output_document_batch_id'] = $batch_id;
            $batch_item['OutputDocumentBatchItem']['batch_date'] = $now;
        }

        // update the batch items
        $this->edit($batch_items);

        return $batch_id;
    }

    /**
     * gets queued items for batching
     *
     * @param string $label label
     *
     * @return array
     * @access public
     */
    public function getItemsForBatch ($label)
    {
        return $this->find(
            'all', array(
                'fields' => array(
                    'OutputDocumentBatchItem.id',
                    'OutputDocumentBatchItem.output_document_type',
                    'OutputDocumentBatchItem.label',
                    'OutputDocumentBatchItem.output_document_batch_id',
                    'OutputDocumentBatchItem.batch_date'),
                'conditions' => array(
                    'OutputDocumentBatchItem.label' => $label,
                    'OutputDocumentBatchItem.output_document_batch_id' => null,
                    'OutputDocumentBatchItem.batch_date' => null
                ),
                'order' => 'OutputDocumentBatchItem.id ASC',
                'limit' => 50
            )
        );
    }
}