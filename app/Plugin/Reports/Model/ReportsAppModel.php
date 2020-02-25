<?php
/**
 * ReportsAppModel
 *
 * @package Reports.Model
 * @author  Iowa Interactive, LLC.
 */
class ReportsAppModel extends AppModel
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