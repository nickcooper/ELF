<?php
/**
 * Common Checkout App model
 *
 * @package CommonCheckout.Model
 * @author  Iowa Interactive, LLC.
 */
class CommonCheckoutAppModel extends AppModel
{
    /**
     * Model behaviors.
     *
     * @var Array
     * @access public
     */
    public $actsAs = array(
        'Logging.Auditable',
    );
}