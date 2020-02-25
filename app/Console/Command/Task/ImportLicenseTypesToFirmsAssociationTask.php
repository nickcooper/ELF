<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportLicenseTypesToFirmsAssociationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportLicenseTypesToFirmsAssociationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('AppModel', 'Licenses.LicenseType', 'LicenseTypesLicenseType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'LicenseTypesLicenseType';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'LicenseTypesLicenseType.parent_license_type_id',
        1 => 'LicenseTypesLicenseType.license_type_id'
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
        $firm_license_type = $this->LicenseType->findByAbbr($row[0]);

        if (!$firm_license_type)
        {
            throw new Exception(sprintf("`%s` not found in license types", $row[0]));
        }

        $row[0] = $firm_license_type['LicenseType']['id'];

        $license_type = $this->LicenseType->findByAbbr($row[1]);

        if (!$license_type)
        {
            throw new Exception(sprintf("`%s` not found in license types", $row[1]));
        }

        $row[1] = $license_type['LicenseType']['id'];

        parent::importRow($row);
    }
}
