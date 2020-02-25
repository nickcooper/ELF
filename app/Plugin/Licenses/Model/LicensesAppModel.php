<?php
/**
 * License App model
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class LicensesAppModel extends AppModel
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