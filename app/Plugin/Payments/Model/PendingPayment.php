<?php
/**
 * PendingPayment Model
 * Extends the AppModel.
 *
 * @package Payments.Model
 * @author  Iowa Interactive, LLC.
 */
class PendingPayment extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PendingPayment';

    /**
     * @var $hasMany
     * @access public
     */
    public $hasMany = array(
        'PendingPaymentItem' => array(
            'className' => 'Payments.PendingPaymentItem',
            'foreignKey' =>'pending_payment_id',
        ),
    );

    /**
     * @var $belongsTo
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
    );
}