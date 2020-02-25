<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportCourseCatalogsToLicenseTypesAssociationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportCourseCatalogsToLicenseTypesAssociationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('ContinuingEducation.CourseCatalog', 'Licenses.LicenseType', 'ContinuingEducation.CourseCatalogsLicenseType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'CourseCatalogsLicenseType';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'CourseCatalogsLicenseType.license_type_id',
        1 => 'CourseCatalogsLicenseType.course_catalog_id',
        2 => 'CourseCatalogsLicenseType.initial',
        3 => 'CourseCatalogsLicenseType.renewal',
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

        $row[0] = $license_type['LicenseType']['id'];

        $course_catalog = $this->CourseCatalog->findByAbbr($row[1]);

        if (!$course_catalog)
        {
            throw new Exception(sprintf("`%s` not found in course_catalogs", $row[1]));
        }

        $row[1] = $course_catalog['CourseCatalog']['id'];
        $row[2] = $row[2] == 'Yes' ? 1 : 0;
        $row[3] = $row[3] == 'Yes' ? 1 : 0;

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
