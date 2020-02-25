<?php
/**
 * Course Model
 *
 * Extends CourseAppModel. Responsible for managing course data.
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class Course extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Course';

    /**
     * Display Field
     *
     * @var String
     * @access public
     */
    public $displayField = 'course_catalog_id';

    /**
     * belongsTo Relationships
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'TrainingProvider' => array(
            'className'  => 'ContinuingEducation.TrainingProvider',
            'foreignKey' => 'training_provider_id'
        ),
        'CourseCatalog'  => array(
            'className'  => 'ContinuingEducation.CourseCatalog',
            'foreignKey' => 'course_catalog_id'
        ),
    );

    /**
     * afterSave Callback
     *
     * @param boolean $created Set to true if it's a newly created variable
     *
     * @return boolean
     */
    public function afterSave($created)
    {
        // get training_provider_id
        $training_provider_id = $this->field('training_provider_id', $this->id);

        // get the license id for training provider
        $license_id = $this->TrainingProvider->getTrainingProviderLicenseId($training_provider_id);

        // set license record to pending
        $this->TrainingProvider->License->setPending($license_id);

        return true;
    }

    /**
     * Approves all Courses for a given Training Provider
     *
     * @param int    $training_provider_id Training Provider Id
     * @param string $approved_date        Date the course was approved
     * @param string $app_type             Application Type
     *
     * @return boolean
     */
    public function approveCoursesForTrainingProvider($training_provider_id, $approved_date = null, $app_type = null)
    {
        if (empty($approved_date))
        {
            $approved_date = date('Y-m-d H:i:s');
        }
        else
        {
            $approved_date = date('Y-m-d H:i:s', strtotime($approved_date));
        }

        if ((!empty($app_type)) && $app_type != 'Renewal')
        {
            return $this->updateAll(
                array(
                    'Course.approved' => true,
                    'Course.approved_date' => "'$approved_date'",
                ),
                array(
                    'Course.training_provider_id' => $training_provider_id,
                )
            );
        }
        else
        {
            return $this->updateAll(
                array(
                    'Course.approved' => true,
                ),
                array(
                    'Course.training_provider_id' => $training_provider_id,
                )
            );
        }
    }

    /**
     * Returns a training provider id given a course id
     *
     * @param int $id training provider id
     *
     * @return int
     */
    public function getTrainingProviderIdForCourse($id)
    {
        return $this->field('training_provider_id', $id);
    }

    /**
     * Returns the label from the course catalog for a given course
     *
     * @param int $id course id
     *
     * @return string
     */
    public function getLabelForCourse($id)
    {
        if (!$this->exists($id))
        {
            throw new Exception('Invalid course');
        }

        $course = $this->details($id);

        return $course['CourseCatalog']['label'];
    }

    /**
     * Returns details of Course object
     *
     * @param int   $id       course id
     * @param array $contains contain array
     *
     * @return array
     */
    public function details($id, $contains = false)
    {
        return parent::details(
            $id,
            array(
                'CourseCatalog',
                'TrainingProvider'
            )
        );
    }

    /**
     * get listing of course catalog ids for a training provider
     *
     * @param int $training_provider_id TrainingProvider id
     *
     * @return array
     */
    public function getCoursesForTrainingProvider($training_provider_id)
    {
        return array_values(
            $this->find(
                'list',
                array(
                    'conditions' => array(
                        'Course.training_provider_id' => $training_provider_id,
                    ),
                    'fields' => array(
                        'id', 'course_catalog_id'
                    )
                )
            )
        );
    }

    /**
     * Overriding getList function to call CourseCatalog::getList containing only
     * course catalog items with course table entries. The returned array will be
     * indexed by course catalog ids rather than course ids because we don't want
     * to make relationships directly to this table and block deleting from the
     * license records
     *
     * @param array $conditions query conditions
     * @param array $options    options to be passed along to CourseCatalog::getList
     *
     * @return array
     */
    public function getList($conditions = null, $options = null)
    {
        if (empty($conditions))
        {
            $conditions = array();
        }

        // if Course.enabled isn't set in conditions set it to true
        if (empty($conditions['Course.enabled']))
        {
            $conditions['Course.enabled'] = true;
        }

        $courses = array_unique(parent::getList($conditions, $options));

        return $this->CourseCatalog->getList(array('CourseCatalog.id' => $courses), $options);
    }
}