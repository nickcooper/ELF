<?php
/**
 * Addresses Controller
 * 
 * @package App.Controller
 * @author  Iowa Interactive, LLC.
 */
class AddressesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Addresses';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('AddressBook.Address');

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');
    
    /**
     * beforeFilter callback
     *
     * @return void
     */
    public function beforeFilter()
    {
        // Need to remove primary flag because if there isn't one chosen it causes a black hole
        $this->Security->unlockedFields = array('Address.primary_flag');
        return parent::beforeFilter();
    }
    
    /**
     * address_book method
     * 
     * @return void
     * @access public
     * 
     * @todo
     * - Add a google map image for the primary address
     */
    public function address_book()
    {
        // process form submit
        if ($this->request->data && isset($this->request->data['Address']['primary_flag']))
        {
            $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);
            
            $msg = __('The primary address could not be updated. Try again?');
            if ($this->Address->setPrimaryAddress(
                $this->request->data['Address']['primary_flag'],
                $this->foreign_key, $this->foreign_obj,
                $this->foreign_plugin
            ))
            {
                $msg = __('The primary address has been updated.');
            }
            
            // report the outcome
            $this->Session->setFlash($msg);
        }
        
        // get the primary address
        $primary_address = $this->Address->getPrimaryAddress(
            $this->foreign_key,
            $this->foreign_obj,
            $this->foreign_plugin
        );

        $this->set('primary_address', $primary_address);
        
        // get a list of addresses
        $addresses = $this->Address->getAddressesByForeignObj(
            $this->foreign_key,
            $this->foreign_obj,
            $this->foreign_plugin
        );

        $this->set('addresses', $addresses);
    }
       
    /**
     * add method
     * 
     * @return void
     * @access public
     * 
     * @todo
     * - replace county/state fields with selects using db values
     */
    public function add()
    {
        $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);

        // process form submit
        if ($this->request->data)
        {
            // add in the foreign obj data
            $foreign_obj_data = array(
                'foreign_plugin' => $this->foreign_plugin,
                'foreign_obj' => $this->foreign_obj,
                'foreign_key' => $this->foreign_key
            );
            
            $data['Address'] = array_merge($foreign_obj_data, $this->request->data['Address']);

            // attempt to save the data
            if ($this->Address->saveAddress($data))
            {
                // pass
                $this->Session->setFlash('Address information was saved.');

                // redirect back to where we came from
                $this->redirect(
                    array(
                        'action' => 'address_book',
                        'fp' => $this->foreign_plugin,
                        'fo' => $this->foreign_obj,
                        'fk' => $this->foreign_key,
                    )
                );
            }
            else 
            {
                // fail
                $this->Session->setFlash('Failed to save address information.');
            }
        }
    }
       
    /**
     * edit method
     * 
     * @param int $id expecting record id
     * 
     * @return void
     * @access public
     * 
     * @todo
     * - replace county/state fields with selects using db values
     */
    public function edit($id)
    {
        $this->checkOwnerOrManager('AddressBook.Address', $id);

        // pull in address
        $address = $this->Address->getAddressById($id);

        // process form submit
        if ($this->request->is('post') || $this->request->is('put'))
        {
            // attempt to save the data
            $this->request->data['Address']['id'] = $id;
            // add in the foreign obj data
            $foreign_obj_data = array(
                'foreign_plugin' => $this->foreign_plugin,
                'foreign_obj' => $this->foreign_obj,
                'foreign_key' => $this->foreign_key
            );

            $data['Address'] = array_merge($foreign_obj_data, $this->request->data['Address']);

            if ($this->Address->saveAddress($data))
            {
                // pass
                $this->Session->setFlash(__('Address information was saved.'));
                // redirect back to where we came from
                $this->redirect($this->params['named']['return']);
            }
            else 
            {
                // fail
                $this->Session->setFlash(__('Failed to save address information.'));
            }
        }
        else
        {
            // get the address data
            $this->data = $address;
        }

        // set the foreign obj view vars
        $this->ForeignObject->init(
            $this,
            $address['Address']['foreign_plugin'],
            $address['Address']['foreign_obj'],
            $address['Address']['foreign_key']
        );
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
        $this->checkOwnerOrManager('AddressBook.Address', $id);
        
        // validate the input and attempt to remove the address record
        if (preg_match('/^[1-9]{1}[0-9]*$/', $id) && $this->Address->delete($id))
        {
            $this->Session->setFlash(__('Address was removed.'));
        }
        else
        {
            $this->Session->setFlash(__('Address could not be removed. Try again.'));
        }
        
        // return to the previous page
        // redirect would use the return param without us passing it, but this is more obivious
        $this->redirect($this->params['named']['return']);
    }
    
    /**
     * primary method
     * 
     * Sets a new primary address for foreign obj
     * 
     * @param int $address_id     expecting address record id
     * @param int $foreign_key    expecting foreign record id
     * @param str $foreign_obj    expecting foreign model name
     * @param str $foreign_plugin expecting foreign plugin name
     * 
     * @return void
     */
    public function primary($address_id = null, $foreign_key = null, $foreign_obj = null, $foreign_plugin = null)
    {
        if ($this->Address->setPrimaryAddress($address_id, $foreign_key, $foreign_obj, $foreign_plugin))
        {
            $this->Session->setFlash(__('Primary address was updated.'));
        }
        else
        {
            $this->Session->setFlash(__('Failed to update primary address.'));
        }
        
        $this->redirect($this->params['named']['return']);
    }
}