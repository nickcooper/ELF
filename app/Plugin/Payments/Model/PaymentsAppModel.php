<?php
/**
 * PaymentsAppModel
 *
 * @package Payments.Model
 * @author  Iowa Interactive, LLC.
 */
class PaymentsAppModel extends AppModel
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