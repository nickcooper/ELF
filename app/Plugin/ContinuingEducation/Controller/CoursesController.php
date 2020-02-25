<?php
/**
 * Courses Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class CoursesController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Courses';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'ContinuingEducation.Course',
        'ContinuingEducation.CourseCatalog',
        'ContinuingEducation.TrainingProvider',
    );

    /**
     * View individual course
     *
     * @param int $id course record id
     *
     * @return void
     */
    public function view($id = null)
    {
        $this->Course->id = $id;

        if (!$this->Course->exists())
        {
            throw new NotFoundException(__('Invalid course'));
        }

        $this->set('course', $this->Course->details($id));
    }

    /**
     * add method
     *
     * @param int $training_provider_id training provider record id
     *
     * @return void
     */
    public function add($training_provider_id = null)
    {
        if (!$this->TrainingProvider->exists($training_provider_id))
        {
            throw new NotFoundException(__('Invalid Training Provider'));
            $this->redirect(array('action' => 'index'));
        }

        // do we have a searchable id?
        $searchable_id = null;

        if (isset($this->params['named']['searchable']))
        {
            $searchable_id = $this->params['named']['searchable'];
        }
        elseif ($this->request->is('post') && isset($this->request->data['Searchable']))
        {
            $searchable_id = $this->request->data['Searchable'];
        }

        // check for Searchable id
        if ($searchable_id)
        {
            // format the data
            $this->Course->create();
            $this->Course->set('training_provider_id', $training_provider_id);
            $this->Course->set('course_catalog_id', $searchable_id);

            // add the account id to the managers table
            if ($this->Course->save())
            {
                // success
                $this->Session->setFlash(__('The new course has been added'));
            }
            else
            {
                // fail
                $this->Session->setFlash(__('Failed to add course'));
            }

            // redirect
            $this->redirect(
                array(
                    'plugin'     => 'continuing_education',
                    'controller' => 'courses',
                    'action'     => 'edit',
                    $this->Course->id,
                    'return'     => $this->params['named']['return'],
                ),
                null,
                true,
                'forward'
            );
        }

        // no Searchable id, let's go get one
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'locator',
                'fp'         => 'ContinuingEducation',
                'fo'         => 'CourseCatalog',
                'return'     => base64_encode($this->here),
            ),
            null,
            false,
            'skip'
        );
    }

    /**
     * Edit course
     *
     * @param int $id Course id
     *
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->Course->exists($id))
        {
            throw new NotFoundException(__('Invalid Course'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            if ($this->Course->edit($this->data))
            {
                $this->Session->setFlash(__('Course information saved successfully'));
                $this->redirect(array('action' => 'view', $id));
            }
            else
            {
                $this->Session->setFlash(__('Failed to save course information'));
            }
        }
        else
        {
            $this->data = $this->Course->details($id);
        }
    }

    /**
     * Delete Course
     *
     * @param string $id Course Id
     *
     * @return void
     */
    public function delete($id = null)
    {
        $this->Course->id = $id;

        if (!$this->Course->exists())
        {
            throw new NotFoundException(__('Invalid course'));
        }

        if ($this->Course->delete($id))
        {
            $this->Session->setFlash(__('Course deleted'));
            $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('Course was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Enable a course using the AppController defined enable function
     *
     * @param string $id Course Id
     *
     * @return void
     */
    public function enable($id = null)
    {
        return parent::enable($id);
    }

    /**
     * Disable a course using the AppController defined enable function
     *
     * @param string $id Course Id
     *
     * @return void
     */
    public function disable($id = null)
    {
        return parent::disable($id);
    }
}