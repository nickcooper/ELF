<?php
/**
 * Abatements App model
 *
 * @package Abatements.Model
 * @author  Iowa Interactive, LLC.
 */
class AbatementsAppModel extends AppModel
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