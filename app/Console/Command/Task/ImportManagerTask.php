<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportManagerTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportManagerTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Account', 'Accounts.Manager');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Manager';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Manager.foreign_obj',
        1 => 'Manager.account_id',
        2 => 'Manager.primary_flag',
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
        $license_type = $this->LicenseType->findByLabel($row[0]);
        if (!$license_type)
        {
            throw new Exception(sprintf("`%s` not found in license_types", $row[0]));
        }

        $account = $this->Account->findByUsername($row[1]);
        if (!$account)
        {
            throw new Exception(sprintf("`%s` not found in accounts", $row[1]));
        }

        $row[0] = $license_type['LicenseType']['foreign_obj'];
        $row[1] = $account['Account']['id'];
        $row[2] = $row[2] == 'Yes' ? 1 : 0;

        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        $this->data['Manager']['foreign_plugin'] = $license_type['LicenseType']['foreign_plugin'];
        $this->data['Manager']['foreign_key'] = $license_type['LicenseType']['id'];

        // save data to database
        $this->saveRecord();

        // passed
        return true;
    }
}
