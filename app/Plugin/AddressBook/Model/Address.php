<?php
/**
 * Address model
 *
 * Extends AppModel. Responsible for managing address data.
 *
 * @package App.Model
 * @author  Iowa Interactive, LLC.
 */
class Address extends AddressBookAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Address';

    /**
     * Display Field
     *
     * @var String
     * @access public
     */
    public $displayField = 'label';

    /**
     * virtual field for full address in one field
     */
    public $virtualFields = array(
        'full_address' => '(CASE TRUE WHEN addr2 REGEXP "[a-zA-Z0-9]" THEN CONCAT(addr1, "<br />", addr2, "<br />", city, ", ", state, " ", postal) ELSE CONCAT(addr1, "<br />", city, ", ", state, " ", postal) END)'
    );

    /**
     * Validation Rules
     *
     * @var Array
     * @access public
     */
    public $validate = array(
        'addr1'  => array('notempty'   => array('rule' => array('notempty'))),
        'city'   => array('notempty'   => array('rule' => array('notempty'))),
        'county' => array(
            'iowacounty' => array(
                'rule' => array('iowaCounty'),
                'message' => 'County is required when state is Iowa'
            )
        ),
        'state'  => array('notempty'   => array('rule' => array('notempty'))),
        'postal' => array('notempty'   => array('rule' => array('notempty'))),
        'phone1' => array(
            'formatting' => array(
                'rule' => 'phone',
                'message' => 'Please enter a valid phone number',
                'allowEmpty' => true,
            ),
        ),
        'fax' => array(
            'formatting' => array(
                'rule' => 'phone',
                'message' => 'Please enter a valid fax number',
                'allowEmpty' => true,
            ),
        ),
    );

    /**
     * beforeSave callback
     *
     * @param array $options Options
     *
     * @return void
     * @access public
     */
    public function beforeSave($options = array())
    {
        // set county value to null if state value is not IA
        if (!empty($this->data[$this->alias]['state']) && $this->data[$this->alias]['state'] != 'IA')
        {
            $this->data[$this->alias]['county'] = null;
        }
    }

    /**
     * getAddressById method
     *
     * @param int $id expecting record id
     *
     * @return bool
     */
    public function getAddressById($id = null)
    {
        // fetch the data
        return $result = $this->findById($id);
    }

    /**
     * iowaCounty validation
     *
     * County is required if state is Iowa.
     *
     * @param array $county The text label for the county
     *
     * @return bool
     */
    public function iowaCounty ($county = null)
    {
        if (strtolower(trim($this->data['Address']['state'])) == 'ia' && preg_match('/^$/', trim($county['county'])))
        {
            return false;
        }

        return true;
    }

    /**
     * getAddressesByForeignObj method
     *
     * @param int $foreign_key    expecting foreign record id
     * @param str $foreign_obj    expecting foreign model name
     * @param str $foreign_plugin expecting foreign plugin name
     *
     * @return bool
     */
    public function getAddressesByForeignObj($foreign_key = null , $foreign_obj = null, $foreign_plugin = null)
    {
        // fetch the data
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'Address.foreign_plugin' => $foreign_plugin,
                    'Address.foreign_obj' => $foreign_obj,
                    'Address.foreign_key' => $foreign_key,
                )
            )
        );
    }

    /**
     * saveAddress method
     *
     * @param array $data expecting Address model data
     *
     * @return bool
     */
    public function saveAddress($data = array())
    {
        // get address count for foreign object
        $count = $this->getCount(
            $data['Address']['foreign_key'],
            $data['Address']['foreign_obj'],
            $data['Address']['foreign_plugin']
        );

        // if no existing addresses, set new address to primary
        if ($count == 0)
        {
            $data['Address']['primary_flag'] = '1';
        }

        // if existing addresses and new address is set to primary, unset primary for all associated addresses
        if ($count > 0 && $data['Address']['primary_flag'] == '1')
        {
            $this->unsetAllPrimary(
                $data['Address']['foreign_key'],
                $data['Address']['foreign_obj'],
                $data['Address']['foreign_plugin']
            );
        }

        // save the data
        if (!empty($data['Address']['id']))
        {
            if ($this->edit($data))
            {
                return true;
            }
        }
        else
        {
            if ($this->add($data))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * getCount method
     *
     * @param int $foreign_key    expecting foreign record id
     * @param str $foreign_obj    expecting foreign model name
     * @param str $foreign_plugin expecting foreign plugin name
     *
     * @return bool
     */
    public function getCount($foreign_key = null , $foreign_obj = null, $foreign_plugin = null)
    {
        // fetch the data
        return $this->find(
            'count',
            array(
                'conditions' => array(
                    'Address.foreign_plugin' => $foreign_plugin,
                    'Address.foreign_obj' => $foreign_obj,
                    'Address.foreign_key' => $foreign_key
                )
            )
        );
    }

    /**
     * setPrimaryAddress method
     *
     * @param int $address_id     expecting address record id
     * @param int $foreign_key    expecting foreign record id
     * @param str $foreign_obj    expecting foreign model name
     * @param str $foreign_plugin expecting foreign plugin name
     *
     * @return bool
     */
    public function setPrimaryAddress($address_id=null, $foreign_key=null, $foreign_obj=null, $foreign_plugin=null)
    {
        //check for any currently existing primary addresses for this account
        if ($this->getPrimaryAddress($foreign_key, $foreign_obj, $foreign_plugin))
        {
            $this->unsetAllPrimary($foreign_key, $foreign_obj, $foreign_plugin);
        }

        // set the new address as the primary
        $this->create();
        $this->set('id', $address_id);
        $this->set('foreign_key', $foreign_key);
        $this->set('foreign_obj', $foreign_obj);
        $this->set('foreign_plugin', $foreign_plugin);
        $this->set('primary_flag', true);

        // attempt to set the new primary manager
        if ($this->save())
        {
            return true;
        }

        return false;
    }

    /**
     * getPrimaryAddress method
     *
     * @param int $foreign_key    expecting foreign record id
     * @param str $foreign_obj    expecting foreign model name
     * @param str $foreign_plugin expecting foreign plugin name
     *
     * @return bool
     */
    public function getPrimaryAddress($foreign_key = null , $foreign_obj = null, $foreign_plugin = null)
    {
        // fetch the data
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'Address.foreign_plugin' => $foreign_plugin,
                    'Address.foreign_obj'    => $foreign_obj,
                    'Address.foreign_key'    => $foreign_key,
                    'Address.primary_flag'   => true,
                )
            )
        );
    }

    /**
     * unsetAllPrimary method
     *
     * @param int $foreign_key    expecting foreign record id
     * @param str $foreign_obj    expecting foreign model name
     * @param str $foreign_plugin expecting foreign plugin name
     *
     * @return bool
     */
    public function unsetAllPrimary($foreign_key = null , $foreign_obj = null, $foreign_plugin = null)
    {
        // find all addresses currently set as primary
        $addresses = $this->find(
            'all',
            array(
                'conditions' => array(
                    'Address.foreign_plugin' => $foreign_plugin,
                    'Address.foreign_obj'    => $foreign_obj,
                    'Address.foreign_key'    => $foreign_key,
                    'Address.primary_flag'   => '1',
                )
            )
        );

        //loop through the addresses
        foreach ($addresses as $address)
        {
            $this->create();
            $this->id = $address['Address']['id'];

            // save each updated primary flag
            if (!$this->saveField('primary_flag', 0))
            {
                $this->Session->setFlash(__('Failed to remove existing primary address designation.'));
            }
        }

        return true;
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id Address id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        $address = $this->findById($id, array('foreign_plugin', 'foreign_obj', 'foreign_key'));

        $ForeignModel = ClassRegistry::init(
            sprintf(
                '%s.%s',
                $address['Address']['foreign_plugin'],
                $address['Address']['foreign_obj']
            )
        );

        return $ForeignModel->isOwnerOrManager($address['Address']['foreign_key']);
    }
}