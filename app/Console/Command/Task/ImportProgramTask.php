<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportProgramTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportProgramTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Program');
    
    /**
     * Primary save model
     * 
     * @var str
     * @access public
     */
    public $primary_model = 'Program';
    
    /**
     * Data mapping from csv to cake models
     * 
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Program.label',
        1 => 'Program.abbr',
        2 => 'Program.descr',
        3 => 'Program.merchant_code',
        4 => 'Program.service_code',
    );
}
