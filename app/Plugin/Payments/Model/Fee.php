<?php
/**
 * Fee Model
 * Extends the AppModel.
 *
 * @package Payment.Model
 * @author  Iowa Interactive, LLC.
 */
class Fee extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Fee';

    public $actsAs = array(
        'Payments.Payable',
    );

    public $belongsTo = array(
        'Payments' => array(
            'className' => 'Accounts.Account',
            'foreignKey' =>'account_id',
        )
    );

    public $hasMany = array(
        'FeeModifier' => array(
            'className' => 'Payments.FeeModifier',
            'foreignKey' =>'fee_id',
        )
    );

    /**
    * getFeeByKey method
    *
    * @param str $fee_key string key to look up fee
    *
    * @return array returns an array of found fees
    */
    public function getFeeByKey($fee_key = null)
    {
        return $this->find(
            'first',
            array(
                'conditions' => array('Fee.fee_key' => $fee_key)
            )
        );
    }
}