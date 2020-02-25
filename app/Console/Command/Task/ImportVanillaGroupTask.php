<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportVanillaGroupTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportVanillaGroupTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('AppModel', 'Accounts.Group');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Group';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Group.label',
        1 => 'Group.descr',
        2 => 'Group.home',
        3 => 'Group.enabled',
        4 => 'Group.admin'
    );

    /**
     * Import a single row of data
     *
     * @param arrary $row array of row data to import
     *
     * @return void
     * @access public
     */
    public function importRow($row)
    {
        $row[3] = $row[3] == 'Yes' ? 1 : 0;
        $row[4] = $row[4] == 'Yes' ? 1 : 0;

        parent::importRow($row);
    }
}
