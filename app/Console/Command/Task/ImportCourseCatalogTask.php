<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportCourseCatalogTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportCourseCatalogTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Program', 'ContinuingEducation.CourseCatalog');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'CourseCatalog';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'CourseCatalog.program_id',
        1 => 'CourseCatalog.label',
        2 => 'CourseCatalog.abbr',
        3 => 'CourseCatalog.descr',
        4 => 'CourseCatalog.hours',
        5 => 'CourseCatalog.test_attempts',
        6 => 'CourseCatalog.cycle',
        7 => 'CourseCatalog.enabled',
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
        // program assignment
        $program = $this->Program->findByAbbr($row[0]);
        $row[0] = $program['Program']['id'];

        // enabled
        $row[7] = $row[7] == 'Yes' ? 1 : 0;

        parent::importRow($row);
    }
}
