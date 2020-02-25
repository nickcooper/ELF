<?php
/**
 * Firms controller
 *
 * @package Firms.Controller
 * @author  Iowa Interactive, LLC.
 */
class FirmsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Firms';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Firms.Firm',
        'Licenses.LicenseType',
        'Licenses.LicenseStatus',
        'AddressBook.Address',
    );

    /**
     * Default pagination options.
     *
     * @var array
     * @access public
     */
    public $paginate = array(
        'contain' => array(
            'License' => array('LicenseStatus'),
            'FirmType',
        ),
        'conditions' => array(),
        'order' => array('Firm.modified' => 'DESC')
    );

    /**
     * index method
     *
     * Paginated list of firms
     *
     * @return void
     */
    public function index()
    {
        // We're using the Searchble plugin index (it's pretty dope)
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'index',
                'fp'         => 'Firms',
                'fo'         => 'Firm'
            ),
            null,
            true,
            'skip'
        );

        $this->set('firms', $this->paginate());
    }

    /**
     * view method
     *
     * @param int $id the firm id
     *
     * @return void
     * @access public
     */
    public function view($id = null)
    {
        // using the dynamic entity view page
        $this->redirect(
            array(
                'plugin' => 'licenses',
                'controller' => 'licenses',
                'action' => 'entity',
                'fp' => 'Firms',
                'fo' => 'Firm',
                'fk' => $id
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * edit method
     *
     * @param string $id ID
     *
     * @return void
     * @access public
     */
    public function edit($id = null)
    {
        $this->checkOwnerOrManager('Firms.Firm', $id);

        $this->Firm->id = $id;
        if (! $this->Firm->exists())
        {
            throw new NotFoundException(__('Invalid firm'));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                $this->request->data['Firm']['id'] = $id;
                if ($this->Firm->editFirm($this->request->data))
                {
                    $this->Session->setFlash(__('The firm has been saved'));
                    $this->redirect(array('action' => 'view', $id));
                }
                else
                {
                    $this->Session->setFlash(__('The firm could not be saved. Please, try again.'));
                }
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }
        else
        {
            $this->request->data = $this->Firm->details($id);
        }

        $firm = $this->Firm->details($id);
        $firmTypes = $this->Firm->FirmType->getList();
        $firmManagers = $this->Firm->getFirmManagers($id);
        $notes = $this->Firm->Note->getNotesForObject('Firm', $id);

        $this->set(compact('firm', 'firmTypes', 'firmManagers', 'notes'));
    }

    /**
     * delete method
     *
     * @param string $id ID
     *
     * @return void
     * @access public
     */
    public function delete($id = null)
    {
        $this->checkOwnerOrManager('Firms.Firm', $id);

        $this->Firm->id = $id;
        if (! $this->Firm->exists())
        {
            throw new NotFoundException(__('Invalid firm'));
        }

        try
        {
            if ($this->Firm->deleteFirm($id))
            {
                $this->Session->setFlash(__('Firm deleted'));
            }
            else
            {
                $this->Session->setFlash(__('Firm was not deleted'));
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect(array('action' => 'index'));
    }
}