<?php

/**
 * ImportLicenseStatusTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportLicenseStatusTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('AppModel', 'Licenses.LicenseStatus', 'Licenses.LicenseStatusLevel');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'LicenseStatus';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'LicenseStatus.status',
        1 => 'LicenseStatus.license_status_level_id',
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
        // get the status level id
        $license_level_status = $this->LicenseStatusLevel->findByLevel($row[1]);
        $row[1] = $license_level_status['LicenseStatusLevel']['id'];
        
        parent::importRow($row);
    }
}
