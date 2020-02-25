<?php
/**
 * UploadsAppModel
 *
 * @package Uploads.Model
 * @author  Iowa Interactive, LLC.
 */
class UploadsAppModel extends AppModel
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