<?php
/**
 * ExamScores Controller
 *
 * @category Account
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class ExamScoresController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'ExamScores';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Licenses.ExamScore'
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * add method
     *
     * @return void
     * @access public
     *
     * @todo Validate other text input when other is selected as degree type.
     */
    public function add()
    {
        // process form submit
        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                $this->request->data['ExamScore']['foreign_plugin'] = $this->foreign_plugin;
                $this->request->data['ExamScore']['foreign_obj'] = $this->foreign_obj;
                $this->request->data['ExamScore']['foreign_key'] = $this->foreign_key;

                // attempt to save the data
                if ($this->ExamScore->add($this->request->data))
                {
                    $this->Session->setFlash('The Exam Score has been saved.');
                    // redirect back to where we came from
                    $this->redirect($this->params['named']['return']);
                }

                throw new Exception('The Exam Score could not be saved.');
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }
    }

    /**
     * edit method
     *
     * @param int $id Expecting education record id
     *
     * @return void
     * @access public
     *
     * @todo Validate other text input when other is selected as degree type.
     */
    public function edit($id = null)
    {
        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                if ($this->ExamScore->edit($this->request->data))
                {
                    $this->Session->setFlash('The Exam Score has been updated.');
                    // redirect back to where we came from
                    $this->redirect($this->params['named']['return']);
                }

                throw new Exception('The Exam Score could not be updated.');
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }

        $this->request->data = $this->ExamScore->details($id);
    }

    /**
     * delete method
     *
     * @param int $id expecting record id
     *
     * @return void
     * @access public
     */
    public function delete($id)
    {
        if ($this->ExamScore->delete($id))
        {
            $this->Session->setFlash('Exam Score was removed.');
        }
        else
        {
            $this->Session->setFlash('Exam Score could not be removed. Please try again.');
        }

        // return to the previous page
        $this->redirect();
    }
}