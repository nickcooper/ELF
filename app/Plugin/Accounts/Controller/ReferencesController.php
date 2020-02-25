<?php
/**
 * References Controller
 *
 * @category Account
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class ReferencesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'References';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.Reference',
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
        
        // process form post
        if ($this->request->is('post') || $this->request->is('put'))
        {
            //$data = $this->request->data;
            $error = false;
            foreach ($this->request->data['Reference'] as $key => $reference)
            {
                $data = array();
                //$data['Reference']['id'] = $reference['id'];
                $data['Reference']['account_id'] = $this->foreign_key;
                $data['Reference']['notes'] = $reference['notes'];
                $data['Address'] = $reference['Address'];
                $data['Address']['foreign_plugin'] = 'Accounts';
                $data['Address']['foreign_obj'] = 'Reference';
                $data['Address']['primary_flag'] = 1;
                $data['Contact'] = $reference['Contact'];
                $data['Contact']['foreign_plugin'] = 'Accounts';
                $data['Contact']['foreign_obj'] = 'Reference';

                if (!$this->Reference->add($data, array('validate' => 'only')))
                {
                    $this->Reference->validationErrors = array($key => $this->Reference->validationErrors);
                    // fail
                    $this->Session->setFlash(__('Failed to save reference information.'));
                    $error = true;
                    break;
                }

                $references[] = $data;
            }
            if (!$error)
            {
                foreach ($references as $reference)
                {
                    $this->Reference->add($reference);
                }

                // pass
                $this->Session->setFlash(__('Reference information was saved.'));
                // redirect back to where we came from
                $this->redirect($this->params['named']['return']);
            }
        }
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
        $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);
        
        // process form post
        if ($this->request->is('post') || $this->request->is('put'))
        {
            //$data = $this->request->data;
            $error = false;
            foreach ($this->request->data['Reference'] as $key => $reference)
            {
                $data = array();
                $data['Reference']['id'] = $reference['id'];
                $data['Reference']['account_id'] = $this->foreign_key;
                $data['Reference']['notes'] = $reference['notes'];
                $data['Address'] = $reference['Address'];
                $data['Address']['foreign_plugin'] = 'Accounts';
                $data['Address']['foreign_obj'] = 'Reference';
                $data['Address']['primary_flag'] = 1;
                $data['Contact'] = $reference['Contact'];
                $data['Contact']['foreign_plugin'] = 'Accounts';
                $data['Contact']['foreign_obj'] = 'Reference';

                if (!$this->Reference->edit($data, array('validate' => 'only')))
                {
                    $this->Reference->validationErrors = array($key => $this->Reference->validationErrors);
                    // fail
                    $this->Session->setFlash(__('Failed to save reference information.'));
                    $error = true;
                    break;
                }

                $references[] = $data;
            }
            if (!$error)
            {
                foreach ($references as $reference)
                {
                    $this->Reference->edit($reference);
                }

                // pass
                $this->Session->setFlash(__('Reference information was saved.'));
                // redirect back to where we came from
                $this->redirect($this->params['named']['return']);
            }
        }

        $references = $this->Reference->find(
            'all',
            array(
                'contain' => array(
                    'Contact',
                    'Address'
                ),
                'conditions' => array(
                    'Reference.account_id' => $this->foreign_key
                )
            )
        );
        $formatted_references = array();
        foreach ($references as $key => $reference)
        {
            $formatted_references['Reference'][$key] = $reference['Reference'];
            $formatted_references['Reference'][$key]['Contact'] = $reference['Contact'];
            $formatted_references['Reference'][$key]['Address'] = $reference['Address'];
        }
        $this->request->data = $formatted_references;
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
        $this->checkOwnerOrManager('Accounts.Reference', $id);
        
        // validate the input and attempt to remove the address record
        if (preg_match('/^[1-9]{1}[0-9]*$/', $id) && $this->Reference->delete($id))
        {
            $this->Session->setFlash(__('Reference was removed.'));
        }
        else
        {
            $this->Session->setFlash(__('Reference could not be removed. Try again.'));
        }

        // return to the previous page
        // redirect would use the return param without us passing it, but this is more obivious
        $this->redirect($this->params['named']['return']);
    }
}