<?php
/**
 * ProgramsController
 *
 * @category Account
 * @package  Accounts.Controllers
 * @author   Iowa Interactive, LLC.
 */ 
class ProgramsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Programs';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Program', 'Accounts.GroupProgram');
       
    /**
     * beforeFilter method
     * 
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        // whitelist the public auth stuff
        $this->Auth->allow(array('login', 'logout', 'register'));
    }
    
    /**
     * index method
     * 
     * Paginated list of programs
     * 
     * @return void
     */
    public function index() 
    {
        $this->set('programs', $this->paginate());
    }
    
    /**
     * view method
     * 
     * View single record data
     * 
     * @param int|string $id expecting program ID
     * 
     * @return void
     */
    public function view($id = null) 
    { 
        if (!$id) 
        {
            $this->Session->setFlash(__('Invalid program.', true));
            $this->redirect(array('action' => 'index'));
        }
        
        $program = $this->Program->getProgramById($id);
        $this->set('program', $program);
        
        // get a list of license types
        $this->loadModel('Accounts.LicenseType');
        $this->set('license_types', $this->LicenseType->findByProgramId($program['Program']['id']));
        
        // get a list of groups
        $this->loadModel('Accounts.Group');
        $this->set('groups', $this->Group->findByProgramId($program['Program']['id']));
    }
    
    /**
     * add method
     * 
     * @return void
     */
    public function add() 
    {
        // get a list of GroupPrograms
        $this->set('group_programs', $this->GroupProgram->getGroupProgramList());
        
        if ($this->request->is('post')) 
        {
            try
            {
                $data = $this->request->data;
                
                // process the group programs
                foreach ($this->request->data['GroupProgram']['id'] as $group_program_id)
                {
                    $group_program = $this->GroupProgram->getGroupProgramById($group_program_id);
                    
                    $data['Group'][] = array(
                        'name' => sprintf(
                            '%s (%s)', $group_program['GroupProgram']['label'],
                            $this->data['Program']['label']
                        ),
                        'descr' => sprintf(
                            '%s', $group_program['GroupProgram']['descr']
                        ),
                        'group_program_id          ' => 
                        $group_program['GroupProgram']['id'],
                    );
                }
                
                //debug($data); exit();
                
                if ($this->Program->add($data)) 
                {
                    $this->Session->setFlash(
                        __('The program has been saved. Don\'t forget to add groups to this program.', true)
                    );
                    
                    $this->redirect(
                        array(
                            'plugin' => 'accounts', 
                            'controller' => 'programs', 
                            'action' => 'view', 
                            $this->Program->getLastInsertId()
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
    }
    
    /**
     * edit method
     * 
     * @param int|string $id expecting program ID
     * 
     * @return void
     */
    public function edit($id = null) 
    {
        // get a list of program groups
        $this->loadModel('Accounts.GroupProgram');
        $this->set('group_programs', $this->GroupProgram->getGroupProgramList());
        
        if (!$id && empty($this->data)) 
        {
            $this->Session->setFlash(__('Invalid program.', true));
            $this->redirect($this->referer());
        }
        
        if (!empty($this->data)) 
        {
            //debug($this->data); exit();
            
            try
            {
                if ($this->Program->edit($this->data)) 
                {
                    $this->Session->setFlash(__('The program has been saved', true));
                    $this->redirect(array('action' => 'view', $id));
                }
            }
            catch (Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }
        
        if (empty($this->data)) 
        {
            $this->data = $this->Program->getProgramById($id);
        }
    }
    
    /**
     * delete method
     * 
     * @param int|string $id expecting program ID
     * 
     * @return boolean
     */
    public function delete($id = null) 
    {
        try
        {
            if ($this->Program->delete($id)) 
            {
                $this->Session->setFlash(__('The program has been deleted', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        catch (Exception $e)
        {
            // fail
            $this->Session->setFlash($exception->getMessage());
        }
    }
}