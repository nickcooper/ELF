<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportCountyTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportCountyTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('County');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'County';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'County.county',
    );
}
