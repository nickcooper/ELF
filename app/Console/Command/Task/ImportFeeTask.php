<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportFeeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportFeeTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Payments.Fee', 'Licenses.ApplicationType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Fee';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Fee.label',
        1 => 'Fee.foreign_obj',
        2 => 'Fee.fee_key',
        4 => 'Fee.fee',
        5 => 'Fee.apply_tax',
        6 => 'Fee.removable',
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
        $license_type = $this->LicenseType->findByAbbr($row[1]);

        if (!$license_type)
        {
            throw new Exception(sprintf("`%s` not found in license_types", $row[1]));
        }

        $row[5] = $row[5] == 'Yes' ? 1 : 0;
        $row[6] = $row[6] == 'Yes' ? 1 : 0;

        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        $this->data['Fee']['foreign_plugin'] = 'Licenses';
        $this->data['Fee']['foreign_obj'] = 'LicenseType';
        $this->data['Fee']['foreign_key'] = $license_type['LicenseType']['id'];

        // save data to database
        $this->saveRecord();

        // passed
        return true;
    }
}
