<?php
/**
 * Educations Controller
 *
 * @category Account
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class EducationsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Educations';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.Degree',
        'Accounts.EducationDegree',
        'Accounts.ProgramCertificate',
        'Accounts.EducationCertificate'
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

        // get a list of degrees
        $degrees = $this->Degree->find('list', array('order' => array('Degree.order' => 'ASC')));
        $this->set('degrees', $degrees);

        // process form post
        if ($this->request->is('post'))
        {
            $data = $this->request->data;
            // format data
            $data['EducationDegree']['account_id'] = $this->foreign_key;
            // add in the foreign obj data
            $data['Address']['foreign_plugin'] = 'Accounts';
            $data['Address']['foreign_obj'] = 'EducationDegree';
            $data['Address']['primary_flag'] = 1;
            if (isset($data['Upload'][0]))
            {
                $data['Upload'][0]['foreign_plugin'] = 'Accounts';
                $data['Upload'][0]['foreign_obj'] = 'EducationDegree';
            }

            // attempt to save data
            if ($this->EducationDegree->saveEducation($data))
            {
                // pass
                $this->Session->setFlash(__('Education information was saved.'));
                // redirect back to where we came from
                $this->redirect($this->params['named']['return']);
            }
            else
            {
                // fail
                $this->Session->setFlash(__('Failed to save education information.'));
            }
        }

        $this->set('parent', $this->foreign_key);
        $this->set('form_path', Configure::read('Configuration.education_form_path'));
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
    public function edit($id = null)
    {
        $this->checkOwnerOrManager('Accounts.EducationDegree', $id);

        // get a list of degrees
        $degrees = $this->Degree->find('list', array('order' => array('Degree.order' => 'ASC')));
        $this->set('degrees', $degrees);

        // process form post
        if ($this->request->is('post'))
        {
            $data = $this->request->data;

            // format data
            $data['EducationDegree']['account_id'] = $this->foreign_key;
            // add in the foreign obj data
            $data['Address']['foreign_plugin'] = 'Accounts';
            $data['Address']['foreign_obj'] = 'EducationDegree';
            $data['Address']['foreign_key'] = $id;
            $data['Address']['primary_flag'] = true;
            if (isset($data['Upload']))
            {
                $data['Upload']['foreign_plugin'] = 'Accounts';
                $data['Upload']['foreign_obj'] = 'EducationDegree';
                $data['Upload']['foreign_key'] = $id;
            }

            // attempt to save data
            if ($this->EducationDegree->saveEducation($data))
            {
                // pass
                $this->Session->setFlash(__('Education information was saved.'));
                // redirect back to where we came from
                $this->redirect($this->params['named']['return']);
            }
            else
            {
                // fail
                $this->Session->setFlash(__('Failed to save education information.'));
            }
        }

        $this->set('parent', $this->foreign_key);

        // get the degree data
        $this->data = $this->EducationDegree->find(
            'first',
            array(
                'conditions' => array(
                    'EducationDegree.id' => $id),
                    'contain' =>  array(
                        'Upload',
                        'Address'
                    ),
            )
        );

        $this->set('form_path', Configure::read('Configuration.education_form_path'));
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
        $this->checkOwnerOrManager('Accounts.EducationDegree', $id);

        // validate the input and attempt to remove the address record
        if (preg_match('/^[1-9]{1}[0-9]*$/', $id) && $this->EducationDegree->delete($id))
        {
            $this->Session->setFlash(__('Education was removed.'));
        }
        else
        {
            $this->Session->setFlash(__('Education could not be removed. Try again.'));
        }

        // return to the previous page
        // redirect would use the return param without us passing it, but this is more obivious
        $this->redirect($this->params['named']['return']);
    }
}