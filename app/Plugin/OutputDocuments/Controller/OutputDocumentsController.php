<?php
/**
 * OutputDocumentsController
 * 
 * @package OutputDocuments.Controller
 * @author  Iowa Interactive, LLC.
 */
class OutputDocumentsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'OutputDocuments';
    
    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'OutputDocuments.OutputDocumentBatchItem',
        'OutputDocuments.OutputDocumentBatch'
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * @var array
     */
    public $helpers = array('Pdf' => array('className' => 'IiPdf'));

    /**
     * Default pagination options
     *
     * @var array
     * 
     * @access public
     */
    public $paginate = array(
        'limit' => 50,
    );

    /**
     * Generates single document and forces download
     * 
     * @access public
     * 
     * @return void
     */
    public function download () 
    {
        // get the file type extension
        $ext = $this->RequestHandler->responseType();

        $params = $this->request->params['named'];
        $params['filename'] = $this->request->params['pass'][0];

        if (empty($params['filename']) || empty($params['trigger']) || empty($params['doc_type']))
        {
            throw new Exception(__("Output Documents: Missing request parameters."));
        }

        $data = array();

        // Check settings for trigger/doc_type match
        if (!isset($this->settings[$this->ForeignModel->alias][$params['trigger']][$params['doc_type']]))
        {
            $data = $this->ForeignModel->getForeignData($params);
        }
        else
        {
            throw new Exception(__("Output Document: model method does not exist. ({$Model->alias}->{$func})"));
        }
        // Make sure the debug is off
        //Configure::write('debug', 0);

        // Set template data to view
        $this->setTemplateVars(array($data));

        $element = sprintf(
            'agency/%s/%s', 
            $ext,
            Configure::read(sprintf('OutputDocuments.docs.%s.types.%s.elements', $params['doc_type'], $ext))
        );

        $this->set('element', $element);
        $this->set('filename', $params['filename']);

        // Load type specific template
        $this->layout = false; // haven't add layout functionality yet
        $this->render('download');
    }

    /**
     * Generates batch file and forces download
     * 
     * @param int $batch_id batch id
     * @param str $slug     batch item slug
     * 
     * @access public
     * 
     * @return void
     */
    public function downloadBatch ($batch_id = null, $filename = null) 
    {
        // get the file type extension
        $ext = $this->RequestHandler->responseType();
        
        // assign the batch id
        $this->OutputDocumentBatch->id = $batch_id;
        
        // double check we have a batch to download
        if (!$this->OutputDocumentBatch->exists())
        {
            throw new NotFoundException(__('Invalid batch ID'));
        }
        
        // get the batch and batch items
        $batch = $this->OutputDocumentBatch->getBatch($batch_id);
        
        // unserialize the batch item data
        $data = array();
        foreach ($batch['OutputDocumentBatchItem'] as $batch_item)
        {
            $data[] = unserialize($batch_item['template_data']);
        }
        
        // get the doc type key
        $doc_type = $batch['OutputDocumentBatchItem'][0]['output_document_type'];
        
        // get the correct view element
        $element = sprintf(
            'agency/%s/%s', 
            $ext,
            Configure::read(sprintf('OutputDocuments.docs.%s.types.%s.elements', $doc_type, $ext))
        );

        // set template data to view
        $this->set('batch', $batch['OutputDocumentBatch']);
        $this->setTemplateVars($data);
        $this->set('element', $element);
        $this->set('filename', $filename);
        
        
        // set the layout and view
        $this->layout = false; // haven't add layout functionality yet
        $this->render('download');
    }

    /**
     * lists document types with number of unbatched items and last batch date
     * 
     * @access public
     * 
     * @return void
     */
    public function index () 
    {
        $labels = $this->OutputDocumentBatchItem->find('list',
            array(
                'fields' => array('OutputDocumentBatchItem.label'),
                'group' => array('OutputDocumentBatchItem.label'),
                'order' => array('OutputDocumentBatchItem.label')
            )
        );
        $data = array();
        foreach ($labels as $label)
        {
            $count = $this->OutputDocumentBatchItem->getQueueCount($label);
            $last_batch = $this->OutputDocumentBatch->getLastBatchDate($label);
            $data[] = array(
                'label' => $label,
                'count' => $count,
                'last_batch_date' => ($last_batch) ? $last_batch : "N/A"
            );

        }

        $this->set('page_name', 'Print Queue');
        $this->set('rows', $data);
    }

    /**
     * shows buttons to batch or review and lists previous batches
     * 
     * @param string $label $label
     * 
     * @access public
     * 
     * @return void
     */
    public function queue ($slug)
    {
        // reverse the slug
        $label = GenLib::reverseSlug($slug);
        
        // set some general view vars
        $this->set('slug', $slug);
        $this->set('label', $label);
        $this->set('page_name', $label).' Documents';
        
        // set the docs configuration view var
        $docs_config = Configure::read('OutputDocuments.docs');
        $this->set('docs_config', $docs_config);
        
        // set current unbatched document count view var
        $this->set('queue_count', $this->OutputDocumentBatchItem->getQueueCount($label));
        
        // set the batches data veiw var
        $this->set('batches', $this->OutputDocumentBatch->getPreviousBatches($label));
    }
    
    /**
     * batchItems
     */
    public function batchItems ($slug)
    {
        // reverse the slug
        $label = GenLib::reverseSlug($slug);
        
        // batch the items
        $this->OutputDocumentBatchItem->batchItems($label);
        
        // redirect to the view queue page
        return $this->redirect(
            array(
                'action' => 'queue',
                $slug
            )
        );
    }

    /**
     * sets merged default and defined view variables
     * 
     * @param array $data document placeholder array(s)
     * 
     * @access private
     * 
     * @return void
     */
    private function setTemplateVars($data)
    {
        foreach ($data as &$letter)
        {
            $letter['current_date'] = date("F j, Y");
        }
        $this->set('data', $data);

    }
}