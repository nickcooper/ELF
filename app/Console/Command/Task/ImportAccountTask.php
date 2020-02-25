<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportAccountTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportAccountTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Group', 'Accounts.Account');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Account';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Account.group_id',
        1 => 'Account.username',
        2 => 'Account.title',
        3 => 'Account.first_name',
        4 => 'Account.last_name',
        5 => 'Account.middle_initial',
        7 => 'Account.email',
        8 => 'Account.dob',
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
        $group = $this->Group->findByLabel($row[0]);
        $row[0] = $group['Group']['id'];
        $row[8] = date('Y-m-d', strtotime($row[8]));

        parent::importRow($row);
    }
}
