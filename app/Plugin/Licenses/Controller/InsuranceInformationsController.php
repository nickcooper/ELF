<?php
/**
 * Insurance Informations Controller
 * 
 * @package App.Controller
 * @author  Iowa Interactive, LLC.
 */
class InsuranceInformationsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'InsuranceInformations';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.InsuranceInformation', 'Upload');

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
     * @param int $license_id expecting record id
     *
     * @return void
     */
    public function add($license_id = null)
    {
        $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);

        $this->set('insurances', null);

        try
        {
            // process form submit
            if ($this->data)
            {
                $data = $this->request->data;

                // add account id to insurance information
                $data['InsuranceInformation']['foreign_key'] = $this->foreign_key;
                $data['InsuranceInformation']['foreign_plugin'] = 'Licenses';
                $data['InsuranceInformation']['foreign_obj'] = 'License';

                if (GenLib::isData($data, 'Upload.0.file', array('name'))  )
                {
                    $data['Upload'][0]['foreign_plugin'] = 'Licenses';
                    $data['Upload'][0]['foreign_obj'] = 'InsuranceInformation';

                    // set a default label value for the upload if no label value provided
                    if (empty($data['Upload'][0]['label']) && !empty($data['Upload'][0]['file']))
                    {
                        $data['Upload'][0]['label'] = $data['Upload'][0]['file']['name'];
                    }
                }
                else 
                {
                    unset($data['Upload']);
                }

                // attempt to save the data
                if ($this->InsuranceInformation->add($data))
                {
                    // passed
                    $this->Session->setFlash('The insurance information has been saved.');
                    $this->redirect();
                }
            }
        }
        catch(Exception $exception)
        { 
            // fail
            $this->Session->setFlash($exception->getMessage());
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
        $this->checkOwnerOrManager('Licenses.InsuranceInformation', $id);

        if (!$id) 
        {
            $this->Session->setFlash(__('Invalid insurance information.', true));
            $this->redirect(array('action' => 'index'));
        }

        $associated_uploads = $this->Upload->find(
            'all',
            array(
                'conditions' => array(
                    'identifier' => 'Upload',
                    'foreign_plugin' => 'Licenses',
                    'foreign_obj' => 'InsuranceInformation',
                    'foreign_key' => $id
                )
            )
        );

        $this->set('insurances', $associated_uploads);

        if ($this->data)
        {
            try
            {
                // get form data
                $data = $this->data;

                // add account id and foreign key info to insurance information
                $data['InsuranceInformation']['id'] = $id;
                $data['InsuranceInformation']['foreign_key'] = $this->foreign_key;
                $data['InsuranceInformation']['foreign_plugin'] = 'Licenses';
                $data['InsuranceInformation']['foreign_obj'] = 'License';

                if (GenLib::isData($data, 'Upload.0.file', array('name')))    
                {
                    $data['Upload'][0]['foreign_plugin'] = 'Licenses';
                    $data['Upload'][0]['foreign_obj'] = 'InsuranceInformation';
                }

                if ($this->InsuranceInformation->edit($data))
                {
                    $this->Session->setFlash(__('The insurance information has been saved', true));
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
            $this->data = $this->InsuranceInformation->getInsuranceInformationById($id);
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
        $this->checkOwnerOrManager('Licenses.InsuranceInformation', $id);

        // validate the input and attempt to remove the manager record
        if (preg_match('/^[1-9]{1}[0-9]*$/', $id) && $this->InsuranceInformation->delete($id))
        {
            $this->Session->setFlash('Insurance information was removed.');
        }
        else
        {
            $this->Session->setFlash('Insurance information could not be removed. Try again.');
        }
        
        // return to the previous page
        $this->redirect();
        
    }
}