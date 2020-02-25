<?php
/**
 * CourseLocations Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class CourseLocationsController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseLocations';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'ContinuingEducation.CourseLocation',
        'Licenses.License'
    );

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        // we're using the Searchable plugin index
        $this->redirect(
            array(
                'plugin' => 'searchable',
                'controller' => 'searchable',
                'action' => 'index',
                'fp' => 'ContinuingEducation',
                'fo' => 'CourseLocation'
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * view method
     *
     * @param String $id expecting location record id
     *
     * @return void
     * @access public
     */
    public function view($id = null)
    {
        $this->set('course_location', $this->CourseLocation->details($id));
    }

    /**
     * Add new course location
     *
     * @param int $training_provider_id Training Provider id
     *
     * @return void
     */
    public function add($training_provider_id = null)
    {
        if (!$this->CourseLocation->TrainingProvider->exists($training_provider_id))
        {
            $this->Session->setFlash('Invalid training provider');
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post'))
        {
            // set the training provider id
            $this->request->data['CourseLocation']['training_provider_id'] = $training_provider_id;

            if ($this->CourseLocation->add($this->request->data))
            {
                $this->Session->setFlash(__('The course location has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The course location could not be saved. Please, try again.'));
            }
        }

        $this->set('training_providers', $this->CourseLocation->TrainingProvider->getList());

        $license = $this->License->getForeignObjLicense(
            $training_provider_id,
            'TrainingProvider',
            'ContinuingEducation'
        );
        $this->set('license', $license);
    }

    /**
     * edit method
     *
     * @param string $id location record id
     *
     * @return void
     */
    public function edit($id = null)
    {
        $this->CourseLocation->id = $id;

        if (!$this->CourseLocation->exists())
        {
            $this->Session->setFlash('Invalid course location');
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            if ($this->CourseLocation->edit($this->request->data))
            {
                $this->Session->setFlash(__('The course location has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The course location could not be saved. Please, try again.'));
            }
        }
        else
        {
            $this->request->data = $this->CourseLocation->details($id);
        }

        $this->set('training_providers', $this->CourseLocation->TrainingProvider->getList());

        $license = $this->License->getForeignObjLicense(
            $this->request->data['TrainingProvider']['id'],
            'TrainingProvider',
            'ContinuingEducation'
        );
        $this->set('license', $license);
    }

    /**
     * Delete a course location
     *
     * @param int $id course location id
     *
     * @return void
     */
    public function delete($id = null)
    {
        $this->CourseLocation->id = $id;

        if (!$this->CourseLocation->exists())
        {
            throw new NotFoundException(__('Invalid course'));
        }

        if ($this->CourseLocation->delete($id))
        {
            $this->Session->setFlash(__('Course location deleted'));
            $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('Course location was not deleted'));
        $this->redirect(array('action' => 'view', $id));
    }

    /**
     * Enable a course location using the AppController defined enable function
     *
     * @param string $id expecting catalog location id
     *
     * @return void
     */
    public function enable($id = null)
    {
        return parent::enable($id);
    }

    /**
     * Disable a course location using the AppController defined enable function
     *
     * @param string $id expecting catalog location id
     *
     * @return void
     */
    public function disable($id = null)
    {
        return parent::disable($id);
    }
}