<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportWorkExperienceTypeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportWorkExperienceTypeTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.WorkExperienceType');
    
    /**
     * Primary save model
     * 
     * @var str
     * @access public
     */
    public $primary_model = 'WorkExperienceType';
    
    /**
     * Data mapping from csv to cake models
     * 
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'WorkExperienceType.label',
    );
}
