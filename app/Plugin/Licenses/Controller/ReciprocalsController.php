<?php
/**
 * Reciprocals Controller
 *
 * @package App.Controller
 * @author  Iowa Interactive, LLC.
 */
class ReciprocalsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Reciprocals';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.Reciprocal','AddressBook.Address');

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * add method.
     *
     * @return void
     */
    public function add()
    {
        try
        {
            // get the application data
            $application = $this->Reciprocal->Application->find(
                'first',
                array(
                    'conditions' => array(
                        'Application.id' => $this->params['named']['fk']
                    )
                )
            );

            // did we find an application
            if (!$application)
            {
                throw new exception('Application could not be found.');
            }

            // is the application open
            if (!$application['Application']['open'])
            {
                throw new exception('Application is not open for edits.');
            }

            // account id
            $this->set('account_id', ($this->params['named']['account'] ? $this->params['named']['account'] : null));

            // process form post
            if ($this->request->is('post'))
            {
                $data = $this->request->data;

                // format data
                $data['Reciprocal']['application_id'] = $this->foreign_key;

                $data['Address']['foreign_plugin'] = 'Licenses';
                $data['Address']['foreign_obj'] = 'Reciprocal';
                $data['Address']['primary_flag'] = 1;

                $data['Upload']['foreign_plugin'] = 'Licenses';
                $data['Upload']['foreign_obj'] = 'Reciprocal';

                // attempt to save data
                if (!$this->Reciprocal->saveAll($data))
                {
                    throw new Exception('Could not save reciprocal data.');
                }

                $reciprocal_id = $this->Reciprocal->getInsertID();
                $application = $this->Reciprocal->Application->findById($this->foreign_key);

                $this->Session->setFlash('Reciprocal data saved.');

                // redirect to return
                $this->redirect();
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
     * @param int $id reciprocal record id
     *
     * @return void
     */
    public function edit($id = null)
    {
        // account id
        $this->set('account_id', ($this->params['named']['account'] ? $this->params['named']['account'] : null));

        $data = array();
        if ($this->request->is('post'))
        {
            $data = $this->request->data;

            // format data
            $data['Reciprocal']['id'] = $id;
            $data['Reciprocal']['application_id'] = $this->foreign_key;

            $data['Address']['foreign_plugin'] = 'Licenses';
            $data['Address']['foreign_obj'] = 'Reciprocal';
            $data['Address']['primary_flag'] = 1;

            if (!$this->Reciprocal->edit($data))
            {
                $this->Session->setFlash('Failed to update Reciprocal data.');
            }
            else
            {
                $application = $this->Reciprocal->Application->findById($this->foreign_key);

                // passed - redirect
                $this->Session->setFlash('Reciprocal data was saved.');
                $this->redirect();
            }
        }

        // get the reciprocal record
        $this->data = $this->Reciprocal->find(
            'first',
            array(
                'conditions' => array('Reciprocal.id' => $id),
                'contain' => array('Address', 'Transcript')
            )
        );
    }

    /**
     * delete method
     *
     * @param int $id expecting record id
     *
     * @return void
     *
     * @todo Remove child upload records.
     */
    public function delete($id)
    {
        try
        {
            $reciprocal = $this->Reciprocal->findById($id);
            $application = $this->Reciprocal->Application->findById($reciprocal['Reciprocal']['application_id']);

            // validate the input and attempt to remove the record
            if (preg_match('/^[1-9]{1}[0-9]*$/', $id) && $this->Reciprocal->delete($id))
            {
                $this->Session->setFlash('Reciprocal hours were removed.');
                // return to the previous page
                $this->redirect();
            }
            else
            {
                $this->Session->setFlash('Reciprocal hours could not be removed.');
                // return to the previous page
                $this->redirect();
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            // return to the previous page
            $this->redirect();
        }
    }
}