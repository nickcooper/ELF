<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportQuestionsToLicenseTypesAssociationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportQuestionsToLicenseTypesAssociationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.Question');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Question';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Question.license_type_id',
        1 => 'Question.question',
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
