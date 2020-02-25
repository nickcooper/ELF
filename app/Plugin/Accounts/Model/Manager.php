<?php
/**
 * Manager model
 *
 * Extends AppModel. Responsible for managing manager data.
 *
 * @package App.Model
 * @author  Iowa Interactive, LLC.
 */
class Manager extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Manager';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'foreign_obj';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
    );

    public $hasOne = array(
    );

    /**
     * beforeSave method
     *
     * @param array $options options array
     *
     * @return bool
     * @access public
     */
    public function beforeSave($options = array())
    {
        // if primary flag
        if (isset($this->data['Manager']['foreign_obj'])
            && isset($this->data['Manager']['primary_flag'])
            && $this->data['Manager']['primary_flag'] == true
        )
        {
            $conditions = array(
                'Manager.foreign_obj'    => $this->data['Manager']['foreign_obj'],
                'Manager.foreign_plugin' => $this->data['Manager']['foreign_plugin'],
                'Manager.foreign_key' => $this->data['Manager']['foreign_key'],
            );

            // unset primary on all foreign obj related records
            if (! $this->updateAll(array('Manager.primary_flag' => false), $conditions))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * beforeDelete method
     *
     * @param bool $cascade true/false if cascade delete
     *
     * @return bool
     * @access public
     */
    public function beforeDelete($cascade = true)
    {
        // prevent deleting primary managers
        if ($this->data['Manager']['primary_flag'])
        {
            return false;
        }

        return parent::beforeDelete($cascade);
    }

    /**
     * getManager method
     *
     * @param int    $account_id  expecting account record id
     * @param string $foreign_obj expecting foreign model name
     * @param int    $foreign_key expecting foreign record id
     *
     * @return array
     * @access public
     */
    public function getManager($account_id = null, $foreign_obj = null, $foreign_key = null)
    {
        return $this->find(
            'first',
            array(
                'conditions' =>
                array('Manager.account_id' => $account_id,
                'Manager.foreign_obj' => $foreign_obj, 'Manager.foreign_key' => $foreign_key),
                'contain' => array('Account'),
                'order' => array('Account.label' => 'ASC'),
            )
        );
    }

    /**
     * getManagers method
     *
     * @param string $foreign_obj expecting foreign model name
     * @param int    $foreign_key expecting foreign record id
     *
     * @return array
     * @access public
     */
    public function getManagers($foreign_obj = null, $foreign_key = null)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'Manager.foreign_obj' => $foreign_obj,
                    'Manager.foreign_key' => $foreign_key
                ),
                'contain' => array(
                    'Account' => array(
                        'PrimaryAddress'
                    )
                ),
                'order' => array(
                    'Account.label' => 'ASC'
                ),
            )
        );
    }

    /**
     * getManagedLicenses method
     *
     * @param int    $account_id  expecting account id
     * @param string $foreign_obj expecting foreign model name
     *
     * @return array
     * @access public
     */
    public function getManagedLicenses($account_id = null, $foreign_obj = null)
    {
        // get a list of managed firms
        if (!$managed_items = $this->getManagedItems($account_id, 'Firm'))
        {
            return array();
        }

        // get associated licenses
        foreach ($managed_items as &$item)
        {
            extract($item['Manager']);

            // load the model
            $ForiegnModel = ClassRegistry::init(sprintf('%s.%s', $foreign_plugin, $foreign_obj));

            $license = $ForiegnModel->find(
                'first',
                array(
                    'conditions' => array(sprintf('%s.id', $foreign_obj) => $foreign_key),
                    'contain' => array('License' => array('LicenseType', 'LicenseStatus'))
                )
            );

            // add the license data to the managed items array
            if ($license)
            {
                $item = array_merge($item, $license);
            }
        }

        return $managed_items;
    }

    /**
     * getManagedItems method
     *
     * @param int    $account_id  expecting account id
     * @param string $foreign_obj expecting foreign model name
     *
     * @return array
     * @access public
     */
    public function getManagedItems($account_id = null, $foreign_obj = null)
    {
        // get $managing records
        $managed_items = array();

        if ($foreign_obj)
        {
            $managed_items = $this->find(
                'all',
                array(
                    'conditions' => array(
                        'Manager.account_id'  => $account_id,
                        'Manager.foreign_obj'  => $foreign_obj,
                    ),
                )
            );
        }
        else
        {
            $managed_items = $this->findAllByAccountId($account_id);
        }

        return $managed_items;
    }

    /**
     * getPrimaryManager method
     *
     * @param string $foreign_obj expecting foreign model name
     * @param int    $foreign_key expecting foreign record id
     *
     * @return array
     * @access public
     */
    public function getPrimaryManager($foreign_obj = null, $foreign_key = null)
    {
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'Manager.foreign_obj'  => $foreign_obj,
                    'Manager.foreign_key'  => $foreign_key,
                    'Manager.primary_flag' => true,
                ),
                'contain' => array('Account' => array('PrimaryAddress')),
                'order' => array('Account.label' => 'ASC'),
            )
        );
    }

    /**
     * setPrimaryManager method
     *
     * @param int    $manager_id     expecting manager record id
     * @param int    $foreign_key    expecting foreign record id
     * @param string $foreign_obj    expecting foreign model name
     * @param string $foreign_plugin expecting foreign plugin name
     *
     * @return bool
     * @access public
     */
    public function setPrimaryManager($manager_id=null, $foreign_key=null, $foreign_obj=null, $foreign_plugin=null)
    {
        // format the data
        $this->create();
        $this->set('id', $manager_id);
        $this->set('primary_flag', true);
        $this->set('foreign_key', $foreign_key);
        $this->set('foreign_obj', $foreign_obj);
        $this->set('foreign_plugin', $foreign_plugin);

        // attempt to set the new primary manager
        if ($this->save())
        {
            return true;
        }

        // something went wrong
        return false;
    }
}