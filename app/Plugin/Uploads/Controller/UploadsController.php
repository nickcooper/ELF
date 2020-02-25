<?php
/**
 * Uploads Controller
 *
 * @package Uploads.Controller
 * @author  Iowa Interactive, LLC.
 */
class UploadsController extends UploadsAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Uploads';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Uploads.Upload');

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * @var array
     */
    public $contain = array('Upload');

    /**
     * add method
     *
     * Adds/replaces a single file associated to the foreign obj.
     *
     * A hasOne association set in the uploads configuration will
     * replace the previously saved file. Otherwise, this will
     * add to the list of files already uploaded.
     *
     * @return void
     * @access public
     */
    public function add ()
    {
        $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);

        try
        {
            // set the config key
            if (!$config_key = $this->params['named']['type'])
            {
                throw new Exception('Missing configuration setttings.');
            }

            // do we have a searchable id?
            if (!$this->foreign_key)
            {
                // check for searchable if no foreign_key provided
                if (isset($this->params['named']['searchable']))
                {
                    $this->foreign_key = $this->params['named']['searchable'];
                }
                elseif ($this->request->is('post') && isset($this->request->data['Searchable']))
                {
                    $this->foreign_key = $this->request->data['Searchable'];
                }
                elseif ($this->request->is('post') && isset($this->request->data[$this->foreign_obj]['id']))
                {
                    $this->foreign_key = $this->request->data[$this->foreign_obj]['id'];
                }
            }
            //debug($this->foreign_key); exit();

            // if no id then let searchable find one
            if (!$this->foreign_key)
            {
                // no Searchable id, let's go get one
                $this->redirect(
                    array(
                        'plugin' => 'searchable',
                        'controller' => 'searchable',
                        'action' => 'locator',
                        'fp' => $this->foreign_plugin,
                        'fo' => $this->foreign_obj,
                        'return' => base64_encode($this->here)
                    ),
                    null,
                    true,
                    'skip'
                );
            }

            $this->set('foreign_key', $this->foreign_key);

            // get the foreign obj uploads config
            $configuration = $this->ForeignModel->actsAs['Uploads.Upload'][$config_key];
            $this->set('configuration', $configuration);

            // process add form submit
            if ($this->request->data && isset($this->request->data[$config_key]))
            {
                // add additional data
                $this->request->data[$config_key][0]['foreign_plugin'] = $this->foreign_plugin;
                $this->request->data[$config_key][0]['foreign_obj'] = $this->foreign_obj;
                $this->request->data[$config_key][0]['identifier'] = $config_key;


                if (empty($this->request->data['Uploads']['label']))
                {
                    $this->Session->setFlash(__('Please enter a description when uploading a file.'));
                }
                else
                {
                    $this->request->data[$config_key][0]['label'] = $this->request->data['Uploads']['label'];


                    // attempt to save the file
                    if ($this->ForeignModel->edit($this->request->data))
                    {
                        // successful redirect
                        $this->Session->setFlash('The file was saved.');
                        $this->redirect();
                    }
                }
            }

            // get the fo data and file data
            $this->data = $this->ForeignModel->find(
                'first',
                array(
                    'conditions' => array(sprintf('%s.id', $this->foreign_obj) => $this->foreign_key),
                    'contain' => array($config_key)
                )
            );
            //debug($this->data);
        }
        catch (Exception $e)
        {
            throw $e;
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
        $this->checkOwnerOrManager('Uploads.Upload', $id);

        // validate the input and attempt to remove the address record
        if (preg_match('/^[1-9]{1}[0-9]*$/', $id) && $this->Upload->delete($id))
        {
            $this->Session->setFlash(__('Document was removed.'));
        }
        else
        {
            $this->Session->setFlash(__('Document could not be removed.'));
        }

        // return to the previous page
        // redirect would use the return param without us passing it, but this is more obivious
        $this->redirect($this->params['named']['return']);

    }
}