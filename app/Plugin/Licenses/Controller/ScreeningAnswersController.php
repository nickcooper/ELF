<?php
/**
 * ScreeningAnswers Controller
 *
 * @category License
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class ScreeningAnswersController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'ScreeningAnswers';

    /**
     * autoload models
     */
    public $uses = array('ScreeningAnswer');

    /**
     * view method
     *
     * @var int $id Answer id
     */
    public function view ($id = null)
    {
        // did we get a valid answer record
        if (!$answer = $this->ScreeningAnswer->findById($id))
        {
            throw new Exception('Invalid screening answer data.');
        }

        // is owner or manager?
        $this->checkOwnerOrManager('Licenses.Application', $answer['ScreeningAnswer']['application_id']);

        // pass the answer data to the view
        $this->set('answer', $answer);
    }
}