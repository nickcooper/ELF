<?php
/**
 * QuestionAnswers Controller
 *
 * @category License
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class QuestionAnswersController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'QuestionAnswers';

    /**
     * autoload models
     */
    public $uses = array('QuestionAnswer');

    /**
     * view method
     *
     * @var int $id Answer id
     */
    public function view ($id = null)
    {
        // did we get a valid answer record
        if (!$answer = $this->QuestionAnswer->findById($id))
        {
            throw new Exception('Invalid answer data.');
        }

        // is owner or manager?
        $this->checkOwnerOrManager('Licenses.Application', $answer['QuestionAnswer']['application_id']);

        // pass the answer data to the view
        $this->set('answer', $answer);
    }
}