<?php
/**
 * ContinuingEducationAppModel
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class ContinuingEducationAppModel extends AppModel
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