<?php
/**
* PendingPaymentItem Model
*
* Extends the AppModel. Responsible for the payment data.
* Will represent all the data we need to create, edit or delete.
*
* @package Payment.Model
* @author  Iowa Interactive, LLC.
*/
class PendingPaymentItem extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PendingPaymentItem';

    public $belongsTo = array(
        'Payment' => array(
            'className' => 'Payments.PendingPayment',
            'foreignKey' => 'payment_id'
        ),
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'PendingPaymentItem.foreign_plugin' => 'Licenses',
                'PendingPaymentItem.foreign_obj' => 'Application'
            )
        )
    );


    public $hasMany = array(
        'Modifier' => array(
            'className' => 'Payments.PendingPaymentItem',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Modifier.foreign_plugin' => 'Payments',
                'Modifier.foreign_obj' => 'PendingPaymentItem'
            )
        )
    );
}