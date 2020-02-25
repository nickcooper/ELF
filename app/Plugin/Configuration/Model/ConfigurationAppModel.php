<?php
/**
 * ConfigurationAppModel
 *
 * @package Configuration.Model
 * @author  Iowa Interactive, LLC.
 */
class ConfigurationAppModel extends AppModel
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