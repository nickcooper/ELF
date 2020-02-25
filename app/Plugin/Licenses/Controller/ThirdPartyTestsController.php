<?php
/**
 * ThirdPartyTests Controller
 *
 * @package Licenses.Controller
 * @author  Iowa Interactive, LLC.
 */
class ThirdPartyTestsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'ThirdPartyTests';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Licenses.License',
        'Licenses.LicenseStatus',
        'Licenses.Application',
        'Licenses.ThirdPartyTest'
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * @var array
     * @access public
     */
    public $contain = array();

    /**
     * index method
     *
     * Paginated list of licenses
     *
     * @return bool
     * @access public
     *
     * @todo Update filter form inputs to use form helper
     * @todo Apply filter settings to results
     */

    public function index ()
    {
        // we're using the Searchable plugin index
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'index',
                'fp'         => 'Licenses',
                'fo'         => 'ThirdPartyTest',
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * view method
     *
     * @param int $id Third party test ID
     *
     * @return void
     * @access public
     */
    public function view ($id = null)
    {
        try
        {
            // get the third party test results
            $test = $this->ThirdPartyTest->find(
                'first',
                array(
                    'conditions' => array('ThirdPartyTest.id' => $id),
                    'contain' => array('Upload'),
                )
            );

            // display message if no test results are found
            if (!$test || empty($test))
            {
                throw new Exception(__('Failed to find third party test results.'));
            }

            // send tests to view
            $this->set('test', $test);
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect($this->referer());
        }
    }

    /**
     * add method
     *
     * @param int $id third party test record id
     *
     * @return void
     * @access public
     */
    public function add ()
    {
        try
        {
            // get the application data
            $application = $this->ThirdPartyTest->Application->find(
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

            // process submitted data
            if ($this->request->is('post') || $this->request->is('put'))
            {
                try
                {
                    // start transaction
                    $dataSource = $this->ThirdPartyTest->getDataSource();
                    $dataSource->begin();

                    // account id
                    $this->set('account_id', ($this->params['named']['account'] ? $this->params['named']['account'] : null));

                    // get the form data
                    $data = $this->request->data;

                    // format data to be saved
                    $data['ThirdPartyTest']['foreign_plugin'] = 'Licenses';
                    $data['ThirdPartyTest']['foreign_obj'] = 'Application';
                    $data['ThirdPartyTest']['foreign_key'] = $this->foreign_key;

                    // check for upload data and add to data array, if present
                    if (GenLib::isData($data, 'Upload.0.file', array('name')))
                    {
                        $data['Upload'][0]['foreign_plugin'] = 'Licenses';
                        $data['Upload'][0]['foreign_obj'] = 'ThirdPartyTest';
                        $data['Upload'][0]['identifier'] = 'Upload';
                        $data['Upload'][0]['label'] = 'Third Party Test';
                    }
                    else
                    {
                        unset($data['Upload']);
                    }

                    // attempt to save data
                    if (!$this->ThirdPartyTest->add($data))
                    {
                        throw new Exception('Could not save third party test data.');
                    }

                    // commit transaction
                    $dataSource->commit();

                    // redirect
                    $this->Session->setFlash('Third Party Test data saved.');
                    $this->redirect($this->referer());
                }
                catch (Exception $e)
                {
                    // rollback transaction and send up error message
                    $dataSource->rollback();

                    throw $e;
                }
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect();
        }
    }

    /**
     * delete method
     *
     * @param int $id third party test attempt record id
     *
     * @return void
     * @access public
     */
    public function delete ($id = null)
    {
        try
        {
            if (!$this->ThirdPartyTest->delete($id))
            {
                throw new Exception(__('Failed to remove Third Party Test results.'));
            }

            // redirect
            $this->Session->setFlash(__('The Third Party Test results were removed.'));
            $this->redirect($this->referer());
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect($this->referer());
        }
    }
}