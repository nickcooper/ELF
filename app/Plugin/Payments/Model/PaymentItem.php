<?php
/**
* PaymentItem Model
*
* Extends the AppModel. Responsible for the payment data.
* Will represent all the data we need to create, edit or delete.
*
* @package Payment.Model
* @author  Iowa Interactive, LLC.
*/
class PaymentItem extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PaymentItem';

    public $belongsTo = array(
        'Payment' => array(
            'className' => 'Payments.Payment',
            'foreignKey' => 'payment_id'
        ),
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'PaymentItem.foreign_plugin' => 'Licenses', 'PaymentItem.foreign_obj' => 'Application'
            )
        )
    );

    public $hasMany = array(
        'Modifier' => array(
            'className' => 'Payments.PaymentItem',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Modifier.foreign_plugin' => 'Payments',
                'Modifier.foreign_obj' => 'PaymentItem'
            )
        )
    );

    public function getPaymentDataForApplication($application_id)
    {
        return $this->find(
            'all',
            array(
                'contain' => array(
                    'Payment'
                ),
                'conditions' => array(
                    'PaymentItem.foreign_plugin' => 'Licenses',
                    'PaymentItem.foreign_obj' => 'Application',
                    'PaymentItem.foreign_key' => $application_id
                )
            )
        );
    }
}