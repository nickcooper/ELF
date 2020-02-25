<?php
/**
 * GroupProgamsController
 *
 * @category Account
 * @package  Accounts.Controllers
 * @author   Iowa Interactive, LLC.
 */
class GroupProgramsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'GroupPrograms';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.GroupProgram', 'Accounts.Group', 'Accounts.Program');
    
    /**
     * index method
     * 
     * Paginated list of program groups
     * 
     * @return void
     */
    public function index() 
    {
        $this->set('group_programs', $this->paginate());
    }
    
    /**
     * view method
     * 
     * View single record data
     * 
     * @param int|string $id expecting program group ID
     * 
     * @return void
     */
    public function view($id = null) 
    {
        if (!$id) 
        {
            $this->Session->setFlash(__('Invalid program group.', true));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->set('group_program', $this->GroupProgram->getGroupProgramById($id));
    }
    
    /**
     * add method
     * 
     * @return void
     */
    public function add() 
    {
        if (!empty($this->data)) 
        {
            try
            {
                if ($this->GroupProgram->addGroupProgram($this->data)) 
                {
                    $this->Session->setFlash(
                        __('The program group has been saved.', true)
                    );
                    $this->redirect(array('action' => 'index'));
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
     * @param int|string $id expecting program group ID
     * 
     * @return void
     */
    public function edit($id = null) 
    {
        if (!$id && empty($this->data)) 
        {
            $this->Session->setFlash(__('Invalid program group.', true));
            $this->redirect(array('action' => 'index'));
        }
        
        if (!empty($this->data)) 
        {
            try
            {
                if ($this->GroupProgram->updateGroupProgram($this->data)) 
                {
                    $this->Session->setFlash(
                        __('The program group has been saved', true)
                    );
                    $this->redirect(array('action' => 'index'));
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
            $this->data = $this->GroupProgram->getGroupProgramById($id);
        }
    }
    
    /**
     * delete method
     * 
     * @param int|string $id expecting program group ID
     * 
     * @return boolean
     */
    public function delete($id = null) 
    {
        try
        {
            if ($this->GroupProgram->delete($id)) 
            {
                $this->Session->setFlash(
                    __('The program group has been deleted', true)
                );
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