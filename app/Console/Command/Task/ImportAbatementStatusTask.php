<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportAbatementStatusTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportAbatementStatusTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Abatements.AbatementStatus');
    
    /**
     * Primary save model
     * 
     * @var str
     * @access public
     */
    public $primary_model = 'AbatementStatus';
    
    /**
     * Data mapping from csv to cake models
     * 
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'AbatementStatus.label',
    );
}
