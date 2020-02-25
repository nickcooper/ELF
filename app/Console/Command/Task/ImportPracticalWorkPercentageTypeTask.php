<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportPracticalWorkPercentageTypeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportPracticalWorkPercentageTypeTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('PracticalWorkPercentageType');
    
    /**
     * Primary save model
     * 
     * @var str
     * @access public
     */
    public $primary_model = 'PracticalWorkPercentageType';
    
    /**
     * Data mapping from csv to cake models
     * 
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'PracticalWorkPercentageType.label',
        1 => 'PracticalWorkPercentageType.enabled',
    );

    /**
     * Main process method
     *
     * @param array $row data provided by the import csv file
     *
     * @return true if successful
     * @access public
     */
    public function importRow($row)
    {
        $row[2] = $row[2] == 'Yes' ? 1 : 0;

        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        // save data to database
        $this->saveRecord();
        
        // passed
        return true;
    }
}
