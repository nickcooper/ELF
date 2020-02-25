<?php
/**
 * FirmTypes controller
 *
 * @package Firms.Controller
 * @author  Iowa Interactive, LLC.
 */
class FirmTypesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'FirmTypes';

    /**
     * index method
     *
     * @return void
     * @access public
     */
    public function index()
    {
        $this->FirmType->recursive = 0;
        $this->set('firmTypes', $this->paginate());
    }

    /**
     * view method
     *
     * @param string $id ID
     *
     * @return void
     * @access public
     */
    public function view($id=null)
    {
        $this->FirmType->id = $id;
        if (! $this->FirmType->exists())
        {
            throw new NotFoundException(__('Invalid firm type'));
        }

        $this->set('firmType', $this->FirmType->details($id));
    }

    /**
     * add method
     *
     * @return void
     * @access public
     */
    public function add()
    {
        if ($this->request->is('post'))
        {
            try
            {
                $this->FirmType->create();
                if ($this->FirmType->addFirmType($this->request->data))
                {
                    $this->Session->setFlash(__('The firm type has been saved'));
                    $this->redirect(array('action' => 'index'));
                }
                else
                {
                    $this->Session->setFlash(__('The firm type could not be saved. Please, try again.'));
                }
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }
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
        $this->FirmType->id = $id;
        if (! $this->FirmType->exists())
        {
            throw new NotFoundException(__('Invalid firm type'));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                if ($this->FirmType->editFirmType($this->request->data))
                {
                    $this->Session->setFlash(__('The firm type has been saved'));
                    $this->redirect(array('action' => 'index'));
                }
                else
                {
                    $this->Session->setFlash(__('The firm type could not be saved. Please, try again.'));
                }
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }
        else
        {
            $this->request->data = $this->FirmType->details($id);
        }
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
        if (! $this->request->is('post'))
        {
            throw new MethodNotAllowedException();
        }

        $this->FirmType->id = $id;
        if (! $this->FirmType->exists())
        {
            throw new NotFoundException(__('Invalid firm type'));
        }

        try
        {
            if ($this->FirmType->delete())
            {
                $this->Session->setFlash(__('Firm type deleted'));
            }
            else
            {
                $this->Session->setFlash(__('Firm type was not deleted'));
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect(array('action' => 'index'));
    }
}