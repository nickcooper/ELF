<?php
/**
 * Groups Controller
 *
 * @category Account
 * @package  Accounts.Controllers
 * @author   Iowa Interactive, LLC.
 */
class GroupsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Groups';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Group');

    /**
     * index method
     * 
     * Paginated list of groups
     * 
     * @return void
     * 
     * @todo Limit results if user's group is assigned to a single program
     */
    public function index() 
    {
        $this->set('groups', $this->paginate());
    }
    
    /**
     * view method
     * 
     * View single record data
     * 
     * @param int|string $id expecting group ID
     * 
     * @return void
     * 
     * @todo restrict access if group does not belong to users program
     */
    public function view($id = null) 
    {
        if (!$id) 
        {
            $this->Session->setFlash(__('Invalid group.', true));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->Group->contain('Program');
        $this->set('group', $this->Group->getGroupById($id));
    }
    
    /**
     * add method
     * 
     * @return void
     * 
     * @todo Need to remove the program select if user belongs to a group 
     * that is assigned to a particular program already. Any new groups 
     * will automatically be assigned to that program.
     */
    public function add() 
    {
        // get a list of programs
        $this->loadModel('Accounts.Program');
        $programs = $this->Program->find('list');
        $this->set('programs', $programs);
        
        if (!empty($this->data)) 
        {
            try
            {
                if ($this->Group->add($this->data)) 
                {
                    $this->Session->setFlash(__('The group has been saved.', true));
                    
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
     * @param int|string $id expecting group ID
     * 
     * @return void
     * 
     * @todo restrict access if group does not belong to user's program
     */
    public function edit($id = null) 
    {
        if (!$id && empty($this->data)) 
        {
            $this->Session->setFlash(__('Invalid group.', true));
            $this->redirect(array('action' => 'index'));
        }
        
        if (!empty($this->data)) 
        {
            try
            {
                if ($this->Group->edit($this->data)) 
                {
                    $this->Session->setFlash(__('The group has been saved', true));
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
            $this->data = $this->Group->getGroupById($id);
        }
    }
    
    /**
     * delete method
     * 
     * @param int|string $id expecting group ID
     * 
     * @return boolean
     * 
     * @todo restrict access if group does not belong to users program
     */
    public function delete($id = null) 
    {
        try
        {
            if ($this->Group->delete($id)) 
            {
                $this->Session->setFlash(__('The group has been deleted', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        catch (Exception $e)
        {
            // fail
            $this->Session->setFlash($exception->getMessage());
        }
    }

    /**
     * Enable a group using the AppController defined enable function
     *
     * @param int $id group ID
     *
     * @return void
     */
    public function enable($id = null)
    {
        return parent::enable($id);
    }

    /**
     * Disable a group using the AppController defined enable function
     *
     * @param int $id group ID
     *
     * @return void
     */
    public function disable($id = null)
    {
        return parent::disable($id);
    }
}