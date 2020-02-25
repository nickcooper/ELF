<?php
/**
 * WorkExperiences Controller
 * 
 * @package App.Controller
 * @author  Iowa Interactive, LLC.
 */
class WorkExperiencesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'WorkExperiences';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.WorkExperience');

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * beforeFilter method
     * 
     * @return void
     * @access public
     * 
     */
    public function beforeFilter ()
    {
        parent::beforeFilter();
        
        // get a list of work experience types
        $this->set('work_experience_types', $this->WorkExperience->WorkExperienceType->find('list'));
    }
    
    /**
     * add method
     * 
     * @return void
     */
    public function add()
    {
        $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);

        // process form submit
        if ($this->request->is('post'))
        {
            $data = $this->request->data;
            
            // add account id to work experience
            $data['WorkExperience']['account_id'] = $this->foreign_key;
            
            // add foriegn obj stuff to address
            $data['Address']['foreign_plugin'] = 'Accounts';
            $data['Address']['foreign_obj'] = 'WorkExperience';

            if ($data['WorkExperience']['current'])
            {
                $data['WorkExperience']['end_date'] = null;
            }
            
            // attempt to save the data
            if ($this->WorkExperience->add($data))
            {
                // passed
                $this->Session->setFlash('The work experience has been saved.');
                $this->redirect();
            }
            
            // failed
            $this->Session->setFlash('The work experience could not be saved.');
        }
    }
    
    /**
     * edit method
     * 
     * @param int $id expecting record id
     *
     * @return void
     */
    public function edit($id = null)
    {
        if (!$id) 
        {
            $this->Session->setFlash(__('Invalid experience.', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->checkOwnerOrManager('Accounts.WorkExperience', $id);
        
        if ($this->data) 
        {
            try
            {
                if ($this->WorkExperience->updateExperience($this->data)) 
                {
                    $this->Session->setFlash(__('The work experience has been saved', true));
                    $this->redirect();
                }
            }
            catch(Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
            $this->data = null;
        }
        
        if (empty($this->data)) 
        {
            $this->WorkExperience->contain('Address', 'WorkExperienceType');
            $this->data = $this->WorkExperience->getExperienceById($id);
        }
    }
    
    /**
     * delete method
     * 
     * @param int $id expecting record id
     * 
     * @return void
     */
    public function delete($id)
    {
        $this->checkOwnerOrManager('Accounts.WorkExperience', $id);

        // validate the input and attempt to remove the manager record
        if (preg_match('/^[1-9]{1}[0-9]*$/', $id) && $this->WorkExperience->delete($id))
        {
            $this->Session->setFlash('Work experience was removed.');
        }
        else
        {
            $this->Session->setFlash('Work experience could not be removed. Try again.');
        }
        
        // return to the previous page
        $this->redirect();
        
    }
}