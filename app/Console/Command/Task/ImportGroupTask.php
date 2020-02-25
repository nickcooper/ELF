<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportGroupTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportGroupTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('AppModel', 'Accounts.GroupProgram');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'GroupProgram';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'GroupProgram.label',
        1 => 'GroupProgram.descr',
        2 => 'GroupProgram.home',
        3 => 'GroupProgram.enabled',
        4 => 'GroupProgram.admin'
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
        $row[3] = $row[3] == 'Yes' ? 1 : 0;
        $row[4] = $row[4] == 'Yes' ? 1 : 0;

        parent::importRow($row);
    }
}
