<?php
/**
 * QuestionAnswer model
 *
 * Extends the AppModel. Responsible for managing non-screening question answers.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class QuestionAnswer extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'QuestionAnswer';

    var $belongsTo = array(
        'Question' => array(
            'className' => 'Licenses.Question',
            'foreignKey' => 'question_id',
        ),
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_id',
        ),
    );

    var $validate = array(
        'question' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );

    /**
     * getAnswer method
     *
     * @param int $id expecting primary key ID
     *
     * @return array
     */

    public function getAnswer($id = null)
    {
        $contain = array(
            'Application'
        );

        return $this->findById($id);
    }
}