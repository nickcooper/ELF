<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportAppLicCreditHourTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportAppLicCreditHourTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.ApplicationType', 'Licenses.LicenseType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'AppLicCreditHour';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'AppLicCreditHour.application_type_id',
        1 => 'AppLicCreditHour.license_type_id',
        2 => 'AppLicCreditHour.code_hours',
        3 => 'AppLicCreditHour.total_hours'
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
        $application_type_id = $this->ApplicationType->field('id', array('label' => $row[0]));

        if (!$application_type_id)
        {
            throw new Exception(sprintf("`%s` not found in application types", $row[0]));
        }

        $row[0] = $application_type_id;

        $license_type_id = $this->LicenseType->field('id', array('abbr' => $row[1]));

        if (!$license_type_id)
        {
            throw new Exception(sprintf("`%s` not found in license types", $row[1]));
        }
        $row[1] = $license_type_id;

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
