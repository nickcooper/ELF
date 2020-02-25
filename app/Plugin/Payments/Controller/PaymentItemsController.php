<?php
/**
* The PaymentItem Controller
*
* @package PaymentItems.Controller
* @author  Iowa Interactive, LLC.
*/
class PaymentItemsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'PaymentItems';
    
    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Payments.PaymentItem', 'Payments.Payment', 'Payments.ShoppingCart');
}