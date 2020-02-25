<?php
/**
 * FirmsAppModel
 *
 * @package Firms.Model
 * @author  Iowa Interactive, LLC.
 */
class NotesAppModel extends AppModel
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