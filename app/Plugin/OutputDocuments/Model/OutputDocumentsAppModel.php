<?php
/**
 * OutputDocumentsAppModel
 *
 * @package OutputDocuments.Controller
 * @author  Iowa Interactive, LLC.
 */
class OutputDocumentsAppModel extends AppModel
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