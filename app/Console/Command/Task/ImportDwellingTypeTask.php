<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportDwellingTypeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportDwellingTypeTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('DwellingType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'DwellingType';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'DwellingType.label',
        1 => 'DwellingType.enabled',
    );

    /**
     * Import a single row of data
     *
     * @param arrary $row array of row data to import
     *
     * @return boolean
     * @access public
     */
    public function importRow($row)
    {
        $row[1] = $row[1] == 'Yes' ? 1 : 0;

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
