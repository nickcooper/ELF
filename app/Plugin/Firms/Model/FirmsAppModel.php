<?php
/**
 * FirmsAppModel
 *
 * @package Firms.Model
 * @author  Iowa Interactive, LLC.
 */
class FirmsAppModel extends AppModel
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