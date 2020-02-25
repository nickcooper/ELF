<?php
/**
 * Other licenses Controller
 *
 * @category Account
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class OtherLicensesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'OtherLicenses';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.OtherLicense');

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
     * 
     * @todo When adding the very first manager automatically assign 
     * them as primary manager.
     */
    public function add ()
    {
        $this->edit();
    }
    
    /**
     * edit method
     * 
     * @param int $id license ID
     *
     * @return void
     */
    public function edit ($id=null)
    {
        try
        {
            if (!$id)
            {
                $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);
            }
            else
            {
                $this->checkOwnerOrManager('Accounts.OtherLicense', $id);
            }

            // get the account info.
            if (!$account = $this->OtherLicense->Account->findById($this->foreign_key))
            {
                throw new Exception('Failed to find account data.');
            }
            $this->set('account', $account['Account']);

            if (!empty($this->data)) 
            {
                $data = $this->data;
                
                // add the foreign obj data
                $data['OtherLicense']['foreign_plugin'] = $this->foreign_plugin;
                $data['OtherLicense']['foreign_obj'] = $this->foreign_obj;
                $data['OtherLicense']['foreign_key'] = $this->foreign_key;
                 
                if ($this->OtherLicense->add($data)) 
                {
                    $this->Session->setFlash(__('The other license has been saved.', true));
                    
                    $this->redirect();
                }
            }
            
            $this->data = $this->OtherLicense->findById($id);

        }
        catch (Exception $exception)
        {
            // fail
            $this->Session->setFlash($exception->getMessage());
        }
    }
    
    /**
     * delete method
     * 
     * @param int $id license ID
     *
     * @return void
     * 
     * @todo When adding the very first manager automatically assign 
     * them as primary manager.
     */
    public function delete ($id = null)
    {
        // default message is failure
        $this->Session->setFlash(__('Failed to remove the other license record.', true));

        // check for appropriate ownership or managership of the contractor record
        if ($this->checkOwnerOrManager('Accounts.OtherLicense', $id))
        {
            if ($this->OtherLicense->delete($id))
            {
                $this->Session->setFlash(__('The other license record was removed.', true));
            }

            // return
            $this->redirect();
        }
    }
}