<?php
/**
 * InstructorAssignments Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class InstructorAssignmentsController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'InstructorAssignments';

    /**
     * add method
     *
     * @param int $training_provider_id training provider record id
     *
     * @return void
     */
    public function add($training_provider_id=null)
    {
        if (!$this->InstructorAssignment->TrainingProvider->exists($training_provider_id))
        {
            $this->Session->setFlash(__('Invalid training provider'));
            $this->redirect(
                array(
                    'controller' => 'instructors',
                    'action'     => 'index',
                )
            );
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
            $instructor = ClassRegistry::init('ContinuingEducation.Instructor');
            $instructor->id = $searchable_id;
            $account_id = $instructor->field('account_id');

            // check if an account/program combination already exists
            $instructor_assignment = $this->InstructorAssignment->find(
                'first',
                array(
                    'conditions' => array(
                        'training_provider_id' => $training_provider_id,
                        'account_id'           => $account_id,
                    ),
                )
            );

            if (!empty($instructor_assignment))
            {
                $this->Session->setFlash(__('The chosen account is already an instructor for this training provider'));
                $this->redirect(
                    array(
                        'plugin'     => 'continuing_education',
                        'controller' => 'instructors',
                        'action'     => 'index',
                    )
                );
            }

            // format the data
            $this->InstructorAssignment->create();
            $this->InstructorAssignment->set('account_id', $account_id);
            $this->InstructorAssignment->set('training_provider_id', $training_provider_id);

            // add the account id to the managers table
            if ($this->InstructorAssignment->save())
            {
                // success
                $this->Session->setFlash(__('The instructor has been added'));
            }
            else
            {
                // fail
                $this->Session->setFlash(__('Failed to add instructor'));
            }

            // redirect
            $this->redirect(
                array(
                    'plugin'     => 'continuing_education',
                    'controller' => 'instructors',
                    'action'     => 'index',
                )
            );
        }

        // no Searchable id, let's go get one
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'locator',
                'fp'         => 'ContinuingEducation',
                'fo'         => 'Instructor',
                'return'     => base64_encode($this->here),
            ),
            null,
            false,
            'skip'
        );
    }

    /**
     * delete method
     *
     * @param string $id instructor assignment record id
     *
     * @return void
     */
    public function delete($id = null)
    {
        $this->InstructorAssignment->id = $id;

        if (!$this->InstructorAssignment->exists())
        {
            $this->Session->setFlash(__('Invalid instructor'));
            $this->redirect(
                array(
                    'controller' => 'instructors',
                    'action'     => 'index',
                )
            );
        }

        if ($this->InstructorAssignment->delete())
        {
            $this->Session->setFlash(__('Instructor deleted'));
        }
        else
        {
            $this->Session->setFlash(__('Instructor was not deleted'));
        }

        $this->redirect(
            array(
                'plugin'     => 'continuing_education',
                'controller' => 'instructors',
                'action'     => 'index',
            )
        );
    }
}