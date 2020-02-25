<?php
/**
 * PagesAppModel
 *
 * @package Pages.Model
 * @author  Iowa Interactive, LLC.
 */
class PagesAppModel extends AppModel
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