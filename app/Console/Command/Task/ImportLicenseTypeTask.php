<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportLicenseTypeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportLicenseTypeTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Program', 'Licenses.LicenseType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'LicenseType';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'LicenseType.program_id',
        1 => 'LicenseType.foreign_obj',
        2 => 'LicenseType.label',
        3 => 'LicenseType.descr',
        4 => 'LicenseType.abbr',
        5 => 'LicenseType.cycle',
        6 => 'LicenseType.avail_for_initial',
        7 => 'LicenseType.avail_for_renewal',
        8 => 'LicenseType.reciprocal',
        9 => 'LicenseType.renew_before',
        10 => 'LicenseType.renew_after',
        11 => 'LicenseType.month_calc',
        12 => 'LicenseType.static_expiration'
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

        $row[6] = $row[6] == 'Yes' ? 1 : 0;   // avail_for_initial
        $row[7] = $row[7] == 'Yes' ? 1 : 0;   // avail_for_renewal
        $row[8] = $row[8] == 'Yes' ? 1 : 0; // reciprocal
        $row[11] = $row[11] == 'Yes' ? 1 : 0; // month_calc

        $row[12] = strtotime($row[12]) ? date('Y-m-d', strtotime($row[12])) : null; //static_expiration

        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        $this->data['LicenseType']['foreign_plugin'] = $this->getPluginNameFromObj($this->data['LicenseType']['foreign_obj']);


        // save data to database
        $this->saveRecord();

        // passed
        return true;
    }
}
