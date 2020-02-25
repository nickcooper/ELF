<?php
/**
 * Reference model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class Reference extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Reference';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className'  => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
    );

    /**
     * hasOne
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Address.foreign_obj' => 'Reference'),
        ),
        'Contact' => array(
            'className' => 'Contact',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Contact.foreign_obj' => 'Reference'),
        ),
    );

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id record id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        // get the work experience percentage record
        return $record = $this->find(
            'first',
            array(
                'conditions' => array(
                    'Reference.id' => $id,
                    'Reference.account_id' => CakeSession::read('Auth.User.id')
                )
            )
        );
    }

    /**
     * Returns details
     *
     * @param int   $id       record id
     * @param array $contains contains array
     *
     * @return array
     */
    public function details($id, $contains = false)
    {
        return parent::details(
            $id,
            array(
                'Contact',
                'Address'
            )
        );
    }
}