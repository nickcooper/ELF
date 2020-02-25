<?php
/**
 * LicenseTypesController
 * 
 * @package Licenses.Controller
 * @author  Iowa Interactive, LLC.
 */
class LicenseTypesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'LicenseTypes';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.LicenseType');
    
    /**
     * index method
     * 
     * Paginated list of license types
     * 
     * @return bool
     */
     
    public function index () 
    {
        $this->set('license_types', $this->paginate());
        
        return true;
    }
    
    /**
     * view method
     * 
     * @param int|string $id license type ID expected.
     * 
     * @return bool
     */
     
    public function view ($id = null) 
    {
        if (!$id) 
        {
            $this->Session->setFlash(__('Invalid license type.', true));
            $this->redirect(array('action' => 'index'));
            return false;
        }
        
        $this->set('license_type', $this->LicenseType->getLicenseTypeById($id));
        
        return true;
    }
    
    /**
     * add method
     * 
     * @return bool
     */
     
    public function add () 
    {
        if ($this->request->is('post')) 
        {
            try
            {
                $data = $this->request->data;
                
                if (!isset($data['LicenseType']['slug']) || empty($data['LicenseType']['slug']))
                {
                    $data['LicenseType']['slug'] = GenLib::makeSlug($data['LicenseType']['label']);
                }
                
                if ($this->LicenseType->add($data)) 
                {
                    $this->Session->setFlash(__('The license type has been saved.', true));
                    $this->redirect(array('action' => 'index'));
                }
            }
            catch (Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }
        
        return true;
    }
    
    /**
     * edit method
     * 
     * @param int|string $id license type ID expected.
     * 
     * @return bool
     */
     
    public function edit ($id = null) 
    {
        if (!$id && empty($this->data)) 
        {
            $this->Session->setFlash(__('Invalid license type.', true));
            $this->redirect(array('action' => 'index'));
            return false;
        }
        
        if (!empty($this->data)) 
        {
            $data = $this->request->data;
            
            if (!isset($data['LicenseType']['slug']) || empty($data['LicenseType']['slug']))
            {
                $data['LicenseType']['slug'] = GenLib::makeSlug($data['LicenseType']['label']);
            }
            
            try
            {
                if ($this->LicenseType->edit($data)) 
                {
                    $this->Session->setFlash(__('The license type has been saved', true));
                    $this->redirect(array('action' => 'index'));
                    return false;
                }
            }
            catch(Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }
        
        if (empty($this->data)) 
        {
            $this->data = $this->LicenseType->getLicenseTypeById($id);
        }
        
        // action title
        $this->set('title', 'Edit License Type');
        
        return true;
    }
    
    /**
     * delete method
     * 
     * @param int|string $id license type ID expected.
     * 
     * @return bool
     */
     
    public function delete ($id = null) 
    {
        try
        {
            if ($this->LicenseType->delete($id)) 
            {
                $this->Session->setFlash(__('The license type has been deleted', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        catch(Exception $e)
        {
            // fail
            $this->Session->setFlash($exception->getMessage());
        }
    }
}