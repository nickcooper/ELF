<?php
/**
 * ScreeningAnswer model
 *
 * Extends the AppModel. Responsible for managing screening question answers.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class ScreeningAnswer extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ScreeningAnswer';

    var $belongsTo = array(
        'ScreeningQuestion' => array(
            'className' => 'Licenses.ScreeningQuestion',
            'foreignKey' => 'screening_question_id',
        ),
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_id',
        ),
    );

    /**
     * getAnswer method
     *
     * @param int $id expecting primary key ID
     *
     * @return array
     */

    public function getScreeningAnswer($id = null)
    {
        $contain = array(
            'Application'
        );

        return $this->findById($id);
    }

    /**
     * areAnswersCorrect method
     *
     * @param int $id application id
     *
     * @return bool true/false
     */

    public function areAnswersCorrect($id = null)
    {
        // default return value
        $correct_answers = true;

        // get the screening answers for the application id
        $answers = $this->find('all', array('conditions' => array('ScreeningAnswer.application_id' => $id)));

        // loop tha answers and check for corret answers
        foreach ($answers as $answer)
        {
            if ($answer['ScreeningAnswer']['answer'] !== $answer['ScreeningAnswer']['correct_answer'])
            {
                $correct_answers = false;
            }
        }

        // return
        return $correct_answers;
    }
}