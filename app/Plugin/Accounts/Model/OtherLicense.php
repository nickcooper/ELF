<?php
/**
 * OtherLicense model
 *
 * @package Model
 * @author  Iowa Interactive, LLC.
 */
class OtherLicense extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'OtherLicense';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'license_number';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'foreign_key',
            'conditions' => array('foreign_obj' => 'Account')
        )
    );

    /**
     * getManager method
     *
     * @param int $account_id expecting account record id
     *
     * @return array
     * @access public
     */
    public function getOtherLicenses($account_id = null)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array('OtherLicense.account_id' => $account_id)
            )
        );
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id Account id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        // get the Other License record
        $record = $this->find(
            'first',
            array(
                'conditions' => array('OtherLicense.id' => $id),
                'contain' => array('Account')
            )
        );

        if ($record)
        {
            if (CakeSession::read("Auth.User.id") == $record['Account']['id'])
            {
                return true;
            }
        }

        return false;
    }
}