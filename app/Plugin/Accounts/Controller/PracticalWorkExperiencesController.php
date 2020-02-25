<?php
/**
 * PracticalWorkExperience Controller
 *
 * @category Account
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class PracticalWorkExperiencesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'PracticalWorkExperiences';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.PracticalWorkExperience',
        'Accounts.PracticalWorkExperienceType'
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * add method
     *
     * @return void
     * @access public
     *
     * @todo Validate other text input when other is selected as degree type.
     */
    public function add()
    {
        $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);
        
        // process form submit
        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                // add account id
                $this->request->data['PracticalWorkExperience']['account_id'] = $this->foreign_key;

                // attempt to save the data
                if ($this->PracticalWorkExperience->savePracticalWorkExperience($this->request->data))
                {
                    $this->Session->setFlash('The Practical Work Experience has been saved.');
                    // redirect back to where we came from
                    $this->redirect($this->params['named']['return']);
                }

                throw new Exception('The Practical Work Experience could not be saved.');
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }
        $this->set('practical_work_experience_types', $this->PracticalWorkExperienceType->getList());
    }

    /**
     * edit method
     *
     * @param int $id Expecting education record id
     *
     * @return void
     * @access public
     *
     * @todo Validate other text input when other is selected as degree type.
     */
    public function edit($id=null)
    {
        $this->checkOwnerOrManager('Accounts.PracticalWorkExperience', $id);
        
        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                if ($this->PracticalWorkExperience->savePracticalWorkExperience($this->request->data))
                {
                    $this->Session->setFlash('The Practical Work Experience has been updated.');
                    // redirect back to where we came from
                    $this->redirect($this->params['named']['return']);
                }

                throw new Exception('The Practical Work Experience could not be updated.');
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }

        $this->request->data = $this->PracticalWorkExperience->details($id);

        $this->set('practical_work_experience_types', $this->PracticalWorkExperienceType->getList());
    }

    /**
     * delete method
     *
     * @param int $id expecting record id
     *
     * @return void
     * @access public
     */
    public function delete($id)
    {
        $this->checkOwnerOrManager('Accounts.PracticalWorkExperience', $id);
        
        if ($this->PracticalWorkExperience->delete($id))
        {
            $this->Session->setFlash('Practical Work Experience was removed.');
        }
        else
        {
            $this->Session->setFlash('Practical Work Experience could not be removed. Please try again.');
        }

        // return to the previous page
        $this->redirect();
    }
}