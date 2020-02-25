<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportProgramsToRegisteredPluginsAssociationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportProgramsToRegisteredPluginsAssociationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Program', 'Accounts.RegisterPlugin', 'ProgramPlugin');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'ProgramPlugin';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'ProgramPlugin.plugin_id',
        1 => 'ProgramPlugin.program_id',
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
        $program = $this->Program->findByAbbr($row[0]);
        if (!$program)
        {
            throw new Exception(sprintf("`%s` not found in programs", $row[0]));
        }
        $row[0] = $program['Program']['id'];

        $plugin = $this->RegisterPlugin->findByLabel($row[1]);
        if (!$plugin)
        {
            throw new Exception(sprintf("`%s` not found in register_plugins", $row[1]));
        }
        $row[1] = $plugin['RegisterPlugin']['id'];

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
