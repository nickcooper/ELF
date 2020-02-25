<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportLicenseTypesToVariantsAssociationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportLicenseTypesToVariantsAssociationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.LicenseType', 'Licenses.Variant', 'Licenses.LicenseTypeVariant');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'LicenseTypeVariant';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'LicenseTypeVariant.license_type_id',
        1 => 'LicenseTypeVariant.variant_id',
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
        $license_type = $this->LicenseType->findByAbbr($row[0]);
        if (!$license_type)
        {
            throw new Exception(sprintf("`%s` not found in license_types", $row[0]));
        }
        $variant = $this->Variant->findByAbbr($row[1]);
        if (!$variant)
        {
            throw new Exception(sprintf("`%s` not found in variants", $row[1]));
        }

        $row[0] = $license_type['LicenseType']['id'];
        $row[1] = $variant['Variant']['id'];

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
