<?php
/**
 * Contractors Controller
 *
 * @category Account
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class ContractorsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Contractors';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.Contractor');

    /**
     * add method
     *
     * @param int $license_id Expecting license record id
     *
     * @return void
     */
    public function add($license_id = null)
    {
        try
        {
            if (!$license_id)
            {
                throw new Exception(__('Invalid license ID. Unable to add contractor info.'));
            }

            // process form post
            if ($this->request->is('post') || $this->request->is('put'))
            {
                $data = $this->request->data;
                $data['Contractor']['license_id'] = $license_id;

                // add contractor info to license
                if (!$this->Contractor->save($data))
                {
                    throw new Exception(__('Unable to update license number with contractor info.'));
                }

                // passed
                $this->Session->setFlash(__('Contractor info was added to license.'));
                $this->redirect(base64_decode($this->request->params['named']['return']));
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }
    }

    /**
     * edit method
     *
     * @param int $license_id Expecting license record id
     *
     * @return void
     */
    public function edit($license_id = null)
    {
        try
        {
            // get the associated license for the specified contractor record
            $record = $this->Contractor->find(
                'first',
                array(
                    'conditions' => array('Contractor.license_id' => $license_id),
                    'contain' => array('License')
                )
            );

            // deny access if not the owner or manager of the associated license record
            $this->checkOwnerOrManager('Licenses.License', $record['License']['id']);

            if (!$license_id)
            {
                throw new Exception(__('Invalid license ID. Unable to edit contractor info.'));
            }

            // process form post
            if ($this->request->is('post') || $this->request->is('put'))
            {
                $data = $this->request->data;
                $data['Contractor']['license_id'] = $license_id;

                $this->Contractor->validate['fin']['length']['allowEmpty'] = true;

                // edit contractor info for license
                if (!$this->Contractor->save($data))
                {
                    throw new Exception(__('Unable to update license number with contractor info.'));
                }

                // passed
                $this->Session->setFlash(__('Contractor info was updated.'));
                $this->redirect(base64_decode($this->request->params['named']['return']));
            }
            $this->request->data = $this->Contractor->findByLicenseId($license_id);
            $this->request->data['Contractor']['fin'] = '';
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }
    }
}