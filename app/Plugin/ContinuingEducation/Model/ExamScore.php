<?php
/**
 * ExamScore Model
 *
 * Responsible for managing ExamScore data.
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class ExamScore extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ExamScore';

    /**
     * belongsTo relationships
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'CourseRoster' => array(
            'className' => 'ContinuingEducation.CourseRoster',
            'foreignKey' => 'foreign_key',
            'conditions' => array('ExamScore.foreign_obj' => 'CourseRoster')
        )
    );

    /**
     * Display Field
     *
     * @var String
     * @access public
     */
    public $displayField = 'label';

    /**
     * Model validation rules
     *
     * @var Array
     * @access public
     */
    public $validate = array(
        'exam_date' => array(
            'rule' => 'notEmpty',
            'message' => 'Please Enter the Exam Date'
        ),
        'pass' => array(
            'rule' => 'notEmpty',
            'message' => 'Please indicate if this was a passing score'
        ),
        'score' => array(
            'rule' => 'notEmpty',
            'message' => 'Please enter a score'
        ),
    );

    /**
     * Deletes all exam scores recorded for a course roster item
     *
     * @param int $course_roster_id Id for course roster record
     *
     * @return boolean
     */
    public function deleteExamScoresForStudent($course_roster_id)
    {
        return $this->deleteAll(
            array(
                'ExamScore.foreign_key' => $course_roster_id,
                'ExamScore.foreign_obj' => 'CourseRoster'
            )
        );
    }
}