<?php
/**
 * FeeModifier model
 *
 * Extends the AppModel.
 *
 * @package Payments.Model
 * @author  Iowa Interactive, LLC.
 */
class FeeModifier extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'FeeModifier';

    /**
     * display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    public $belongsTo = array(
        'Fee' => array(
            'className' => 'Payments.Fee',
            'foreignKey' =>'fee_id',
        )
    );
}