<?php
/**
 * PracticalWorkPercentages Controller
 *
 * @package App.Controller
 * @author  Iowa Interactive, LLC.
 */
class PracticalWorkPercentagesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'PracticalWorkPercentages';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.PracticalWorkPercentage',
        'Accounts.PracticalWorkPercentageType',
    );

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
     */
    public function beforeFilter ()
    {
        parent::beforeFilter();

        $this->set(
            'practical_work_percentage_types',
            $this->PracticalWorkPercentage->PracticalWorkPercentageType->getList()
        );
    }

    /**
     * add method
     *
     * @return void
     * @access public
     */
    public function add()
    {
        $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);
        
        $label = $this->PracticalWorkPercentage->getAlias();

        // process form submit
        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                // add account id
                $this->request->data['PracticalWorkPercentage']['account_id'] = $this->foreign_key;

                // attempt to save the data
                if ($this->PracticalWorkPercentage->addPercentage($this->request->data))
                {
                    $this->Session->setFlash(sprintf(__('The %s has been saved.'), strtolower($label)));
                    $this->redirect();
                }

                throw new Exception(sprintf(__('The %s could not be saved.'), strtolower($label)));
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }

        $this->set('label', $label);
        $this->set('practical_work_percentage_types', $this->PracticalWorkPercentageType->getList());
    }

    /**
     * edit method
     *
     * @param int $id expecting record id
     *
     * @return void
     * @access public
     */
    public function edit($id=null)
    {
        $this->checkOwnerOrManager('Accounts.PracticalWorkPercentage', $id);
        
        $label = $this->PracticalWorkPercentage->getAlias();

        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                if ($this->PracticalWorkPercentage->updatePercentage($this->request->data))
                {
                    $this->Session->setFlash(sprintf(__('The %s has been updated.'), strtolower($label)));
                    $this->redirect();
                }

                throw new Exception(sprintf(__('The %s could not be updated.'), strtolower($label)));
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }

        $this->request->data = $this->PracticalWorkPercentage->details($id);

        $this->set('label', $label);
        $this->set('practical_work_percentage_types', $this->PracticalWorkPercentageType->getList());
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
        $this->checkOwnerOrManager('Accounts.PracticalWorkPercentage', $id);
        
        $label = $this->PracticalWorkPercentage->getAlias();

        if ($this->PracticalWorkPercentage->delete($id))
        {
            $this->Session->setFlash(sprintf(__('%s was removed.'), $label));
        }
        else
        {
            $this->Session->setFlash(sprintf(__('%s could not be removed. Please try again.'), $label));
        }

        // return to the previous page
        $this->redirect();
    }
}