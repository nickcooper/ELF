<?php
/**
 * CourseSection Model
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class CourseSection extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseSection';

    /**
     * Model Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Searchable.Searchable',
    );

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'CourseCatalog' => array(
            'className' => 'ContinuingEducation.CourseCatalog',
            'foreignKey' => 'course_catalog_id'
        ),
        'TrainingProvider' => array(
            'className' => 'ContinuingEducation.TrainingProvider',
            'foreignKey' => 'training_provider_id',
        ),
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'address_id'
        ),
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id'
        ),
    );

    /**
     * hasMany Relationships
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'CourseRoster' => array(
            'className' => 'ContinuingEducation.CourseRoster',
            'foreignKey' => 'course_section_id'
        )
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'account_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select an instructor'
        ),
        'course_catalog_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a course'
        ),
        'address_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a course location',
        ),
        'start_date' => array(
            'startDateBeforeEndDate' => array(
                'rule' => array('startDateBeforeEndDate'),
                'message' => 'Course start date must be before course end date.'
            ),
            'priorCourseApproval' => array(
                'rule' => array('priorCourseApproval')
            )
        )
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['dates'] = sprintf(
            'CONCAT(%s.start_date, " ", %s.end_date)', $this->alias, $this->alias
        );
    }

    /**
     * beforeSave Callback
     *
     * @param string $options options array
     *
     * @return boolean
     */
    public function beforeSave($options = array())
    {
        // fill in the label from the course catalog
        if (!empty($this->data['CourseSection']['course_catalog_id']))
        {
            $this->data['CourseSection']['label'] = $this->CourseCatalog->field(
                'label',
                array(
                    'id' => $this->data['CourseSection']['course_catalog_id'],
                )
            );
        }

        return parent::beforeSave($options);
    }

    /**
     * afterSave Callback
     *
     * @param boolean $created true if this is a new record
     *
     * @return boolean
     */
    public function afterSave($created)
    {
        // pull in all the data for the newly saved record
        $this->read();

        // Create a course number if one doesn't already exist and we have enough information to do it
        if (empty($this->data['CourseSection']['course_section_number'])
            and !empty($this->data['CourseSection']['training_provider_id'])
            and !empty($this->data['CourseSection']['course_catalog_id'])
        )
        {
            // Get the training provider abbreviation
            $training_provider_abbr = $this->TrainingProvider->field(
                'abbr',
                array(
                    'id' => $this->data['CourseSection']['training_provider_id']
                )
            );

            // Get the course catalog abbreviation
            $course_catalog_abbr = $this->CourseCatalog->field(
                'abbr',
                array(
                    'id' => $this->data['CourseSection']['course_catalog_id']
                )
            );

            $this->saveField('course_section_number', "{$training_provider_abbr}-{$course_catalog_abbr}-{$this->id}");
        }

        return true;
    }

    /**
     * priorCourseApproval
     *
     * validation method to ensure the course section
     * start date is 30 or 90 after the course approval
     * depending on the course materials.
     *
     * @return bool true if start date is <= end date, false otherwise.
     * @access public
     */
    public function priorCourseApproval()
    {
        $retVal = true;

        // this if for custom error messages defined below
        $validator = $this->validator();

        // If we're allowing them to edit the course we need to make sure the start date is far enough off
        if (!empty($this->data['CourseSection']['start_date']))
        {
            // get the training provider
            $training_provider_id = empty($this->data['CourseSection']['training_provider_id']) ?
                $this->field('training_provider_id', array('id' => $this->data['CourseSection']['id'])) :
                $this->data['CourseSection']['training_provider_id'];

            // pull the course data
            $this->Course = ClassRegistry::init('ContinuingEducation.Course');

            $course = $this->Course->find(
                'first',
                array(
                    'conditions' => array(
                        'training_provider_id' => $training_provider_id,
                        'course_catalog_id' => $this->data['CourseSection']['course_catalog_id'],
                    ),
                )
            );

            $approval = new DateTime($course['Course']['approved_date']);
            $start_date = new DateTime($this->data['CourseSection']['start_date']);
            $date_diff = date_diff($approval, $start_date)->days;

            /**
             * If this course is using approved materials the course must be
             * approved on the license 30 days prior to the start date
             * If the course is NOT using approved materials it has to
             * start at least 90 days after the license is approved
             */
            if ($course['Course']['provider_materials'] && $date_diff < 30)
            {
                $validator['start_date']['priorCourseApproval']->message = 'Start date must be at least 30 days after course approval date';
                $retVal = false;
            }
            else if (empty($course['Course']['provider_materials']) && $date_diff < 90)
            {
                $validator['start_date']['priorCourseApproval']->message = 'Start date must be at least 90 days after course approval date';
                $retVal = false;
            }
        }

        return $retVal;
    }

    /**
     * startDateBeforeEndDate
     *
     * validation method to ensure the course section
     * start date is equal to or before the course section
     * end date.
     *
     * @param str $start_date the course section start date
     *
     * @return bool true if start date is <= end date, false otherwise.
     * @access public
     */
    public function startDateBeforeEndDate($start_date = null)
    {
        $retVal = false;

        // dates
        $start_date = $start_date['start_date'];
        $end_date = $this->data['CourseSection']['end_date'];

        // can we convert the dates to timestamps?
        if ($start = strtotime($start_date) && $end = strtotime($end_date))
        {
            // compare the dates
            $retVal = ($start <= $end ? true : false);
        }

        return $retVal;
    }

    /**
     * Gets a list of course catalog items
     *
     * @return array
     */
    public function getCourseCatalog()
    {
        return $this->CourseCatalog->getList(
            array(),
            array('empty' => '')
        );
    }

    /**
     * Gets a count of pending instructors
     *
     * @return int
     */
    public function getPendingInstructorCount()
    {
        $this->Instructor = ClassRegistry::init('ContinuingEducation.Instructor');
        return $this->Instructor->pendingCount();
    }

    // Replacing AppController functions

    /**
     * Course Section Object Details
     *
     * @param int    $id       course section id
     * @param string $contains contains array
     *
     * @return array
     */
    public function details($id, $contains = false)
    {
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'CourseSection.id' => $id
                ),
                'contain' => array(
                    'Account',
                    'TrainingProvider',
                    'Address.label',
                    'CourseRoster' => array(
                        'Account.id',
                        'Account.label',
                        'ExamScore'),
                    'CourseCatalog',
                )
            )
        );
    }

    /**
     * Overriding delete to throw an exception if there are students in the course
     *
     * @param int  $id      CourseSection id
     * @param bool $cascade true/false cascade
     *
     * @return boolean
     */
    public function delete($id = null, $cascade = true)
    {
        $students = $this->CourseRoster->find(
            'count',
            array(
                'conditions' => array(
                    'CourseRoster.course_section_id' => $id,
                )
            )
        );

        if (!empty($students))
        {
            throw new Exception('Cannot delete a course unless all students are deleted first');
        }

        return parent::delete($id);
    }
}