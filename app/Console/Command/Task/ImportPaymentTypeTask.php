<?php

/**
 * ImportPaymentTypeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportPaymentTypeTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Payments.PaymentType');
    
    /**
     * Primary save model
     * 
     * @var str
     * @access public
     */
    public $primary_model = 'PaymentType';
    
    /**
     * Data mapping from csv to cake models
     * 
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'PaymentType.label',
    );
}
