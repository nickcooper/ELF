<?php
/**
 * CourseSections Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class CourseSectionsController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseSections';

    /**
     * Searchable index redirect
     *
     * @return void
     */
    public function index ()
    {
        // we're using the Searchable plugin index
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'index',
                'fp'         => 'ContinuingEducation',
                'fo'         => 'CourseSection',
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * view method
     *
     * @param string $id course section record id
     *
     * @return void
     */
    public function view($id = null)
    {
        $this->CourseSection->id = $id;
        $this->set('courseSection', $this->CourseSection->details($id));;
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
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
            $this->CourseSection->create();
            $this->CourseSection->set('training_provider_id', $searchable_id);
            $this->CourseSection->set('enabled', 1);

            if (!$this->CourseSection->save())
            {
                // fail
                $this->Session->setFlash(__('Course section creation failed. Please try again'));
                $this->redirect(array('action' => 'index'));
            }

            // redirect
            $this->redirect(
                array(
                    'plugin'     => 'continuing_education',
                    'controller' => 'course_sections',
                    'action'     => 'edit',
                    $this->CourseSection->id,
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
                'fo'         => 'TrainingProvider',
                'return'     => base64_encode($this->here),
            ),
            null,
            false,
            'skip'
        );
    }

    /**
     * edit method
     *
     * @param string $id course section record id
     *
     * @return void
     *
     * @todo make cutoff time for editing start/end dates configurable
     */
    public function edit($id = null)
    {
        $this->CourseSection->id = $id;

        if (!$this->CourseSection->exists())
        {
            $this->Session->setFlash(__('Invalid Course Section'));
        }

        $display_course_title_only = false;

        $training_provider_id = $this->CourseSection->field('training_provider_id');

        $conditions = array('training_provider_id' => $training_provider_id);
        $courses = $this->CourseSection->TrainingProvider->Course->getList($conditions);

        if (!empty($this->data['CourseSection']['course_catalog_id']))
        {
            $display_course_title_only = true;
            $this->set('course_title', $courses[$this->data['CourseSection']['course_catalog_id']]);
        }

        try
        {
            if ($this->request->is('post') || $this->request->is('put'))
            {
                if ($this->CourseSection->edit($this->request->data))
                {
                    $this->Session->setFlash(__('The course section has been saved'));
                    $this->redirect(array('action' => 'index'));
                }
                else
                {
                    $this->Session->setFlash(__('The course section could not be saved. Please, try again.'));
                }
            }
            else
            {
                $this->request->data = $this->CourseSection->details($id);
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->set('display_course_title_only', $display_course_title_only);

        // We've got to go by the start date that's in the database for this in case the user
        // chooses a date in the next 7 days so they can fix it
        $start_date = $this->CourseSection->field('start_date', array('id' => $id));
        $dates_editable = empty($start_date) or strtotime('+7 days') < strtotime($start_date);
        $this->set('dates_editable', $dates_editable);

        $this->set('courses', $courses);

        $this->loadModel('ContinuingEducation.CourseLocation');
        $this->set('course_locations', $this->CourseLocation->getList($conditions));

        $this->loadModel('ContinuingEducation.InstructorAssignment');
        $this->set('instructors', $this->InstructorAssignment->getList($training_provider_id));
    }

    /**
     * Delete a course section
     *
     * @param string $id Course section id
     *
     * @return void
     */
    public function delete($id = null)
    {
        try
        {
            $this->CourseSection->id = $id;

            if (!$this->CourseSection->exists())
            {
                $this->Session->setFlash(__('Invalid course section'));
            }

            if ($this->CourseSection->delete($id))
            {
                $this->Session->setFlash(__('Course section deleted'));
            }
            else
            {
                $this->Session->setFlash(__('Error deleting course section'));
                $this->redirect(array('action' => 'view', $id));
            }

            $this->redirect(array('action' => 'index'));
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect(array('action' => 'view', $id));
        }
    }

    /**
     * Enable a course section using the AppController defined enable function
     *
     * @param string $id Course section id
     *
     * @return void
     */
    public function enable($id = null)
    {
        return parent::enable($id);
    }

    /**
     * Disable a course section using the AppController defined enable function
     *
     * @param string $id Course section id
     *
     * @return void
     */
    public function disable($id = null)
    {
        return parent::disable($id);
    }
}