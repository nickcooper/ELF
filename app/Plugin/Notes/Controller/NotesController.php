<?php
/**
 * NotesController 
 *
 * @category Notes
 * @package  Notes.Controller
 * @author   Iowa Interactive, LLC.
 */
class NotesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Notes';
    
    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Notes.Note');
    
    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * This will pass the
     *
     * @return  int|string Could be int, could be string
     * @access public
     */
    public function beforeFilter ()
    {
        $this->account_id = $this->Auth->user('id');
        
        if (count($this->params['pass']) == 0)
        {
            // foreign_plugin
            $foreign_plugin = $this->params['named']['fp'];
            $this->set('foreign_plugin', $this->foreign_plugin);
            
            // foreign_obj
            $foreign_obj = $this->params['named']['fo'];
            $this->set('foreign_obj', $this->foreign_obj);
            
            // foreign_key
            $foreign_key = $this->params['named']['fk'];
            $this->set('foreign_key', $this->foreign_key);
            
        }

        parent::beforeFilter();
    }

    /**
     * add method
     *
     * @access public
     * 
     * @return bool
     */
    public function add ()
    {
        if (!empty($this->request->data)) 
        {
            try
            {
                /**
                * Passing the data for the add.
                */
                $foreign_obj = Inflector::humanize($foreign_obj);
                $this->request->data['Note']['account_id'] = $this->account_id;
                $this->request->data['Note']['foreign_plugin'] = $this->foreign_plugin;
                $this->request->data['Note']['foreign_obj'] = $this->foreign_obj;
                $this->request->data['Note']['foreign_key'] = $this->foreign_key;
                
                if ($this->Note->add($this->request->data)) 
                {
                    $this->Session->setFlash(__('The note has been saved.'));
                    
                    $this->redirect(
                        array(
                            'plugin' => 'notes', 
                            'controller' => 'notes', 
                            'action' => 'index',
                            'fo' => $this->foreign_obj,
                            'fk' => $this->foreign_key
                        )
                    );
                }
            }
            catch (Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }

        // Load foreign model
        $ForeignModel = ClassRegistry::init(sprintf("%s.%s", $this->foreign_plugin, $this->foreign_obj));
        $foreign_label = $ForeignModel->findById($this->foreign_key);
        $this->set('foreign_label', $foreign_label[$this->foreign_obj]['label']);
    }
    
    /**
     * view method
     * This view will display the Notes for the Foreign key
     * $id is the id of the note which will pull the individula note for that user.
     *
     * @param int $id The id is the id in the note table which will
     * return this user individual note.
     *
     * @access public
     *
     * @return   void
     */
    public function view ($id)
    {
        // get me a note yo
        $this->set('note', $this->Note->details($id));
    }
   
    /**
     * index method
     * The index will call the getNote 
     * which passed the $foreign_obj and the $foreign_key
     *
     * is which plugin we are in.
     *
     * @access public
     *
     * @return   void
     */
    public function index ()
    {
        $this->set('notes', $this->Note->getNotesForObject($this->foreign_obj, $this->foreign_key));

        // Load foreign model
        $ForeignModel = ClassRegistry::init(sprintf("%s.%s", $this->foreign_plugin, $this->foreign_obj));
        $foreign_label = $ForeignModel->findById($this->foreign_key);
        $this->set('foreign_label', $foreign_label[$this->foreign_obj]['label']);
    }
}