<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportReplacedCourseCatalogAssociationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportReplacedCourseCatalogAssociationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('ContinuingEducation.CourseCatalog', 'CourseCatalogsCourseCatalog');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'CourseCatalogsCourseCatalog';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'CourseCatalogsCourseCatalog.course_catalog_id',
        1 => 'CourseCatalogsCourseCatalog.parent_course_catalog_id',
        2 => 'CourseCatalogsCourseCatalog.replaced_course_catalog_id',
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
        // get the course catalog id
        $course_catalog = $this->CourseCatalog->findByAbbr($row[0]);

        // get the parent course catalog ids
        $parent_course_catalog_id = array();

        foreach (explode(',', $row[1]) as $parent)
        {
              $record = $this->CourseCatalog->findByAbbr(trim($parent));
              $parent_course_catalog_id[] = $record['CourseCatalog']['id'];
        }

        // get the replacement course catalog ids
        $replaced_course_catalog_id = array();

        foreach (explode(',', $row[2]) as $replaced)
        {
              $record = $this->CourseCatalog->findByAbbr(trim($replaced));
              $replaced_course_catalog_id[] = $record['CourseCatalog']['id'];
        }

        // format the data for parsing
        $row[0] = $course_catalog['CourseCatalog']['id'];
        $row[1] = implode(',', $parent_course_catalog_id);
        $row[2] = implode(',', $replaced_course_catalog_id);

        parent::importRow($row);
    }
}
