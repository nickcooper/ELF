<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportVariantTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportVariantTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.Variant');
    
    /**
     * Primary save model
     * 
     * @var str
     * @access public
     */
    public $primary_model = 'Variant';
    
    /**
     * Data mapping from csv to cake models
     * 
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Variant.label',
        1 => 'Variant.abbr',
        2 => 'Variant.descr',
    );
}
