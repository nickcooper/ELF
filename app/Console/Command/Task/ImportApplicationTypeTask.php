<?php

/**
 * ImportApplicationTypeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportApplicationTypeTask extends ImportTask
{
     /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('ApplicationType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'ApplicationType';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'ApplicationType.label',
    );
}
