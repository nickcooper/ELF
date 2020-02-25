<?php
/**
 * Instructors Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class InstructorsController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Instructors';

    /**
     * Default pagination options.
     *
     * @var Array
     * @access public
     */
    public $paginate = array(
        'contain' => array(
            'Account',
            'Program',
        ),
        'limit' => 10,
        'conditions' => array(),
        'order' => array('Account.last_name' => 'DESC'),
    );

    /**
     * Searchable index redirect
     *
     * @return void
     * @access public
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
                'fo'         => 'Instructor', // using the mysql view table for this action
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * Instructor pending queue
     *
     * Paginated list of pending licenses
     *
     * @return boolean
     * @access public
     */
    public function queue ()
    {
        $this->paginate['conditions'] = array('Instructor.pending' => true);
        $this->set('pending_count', $this->Instructor->pendingCount());
        $this->set('instructors', $this->paginate());
    }

    /**
     * add method
     *
     * @param int $program_id program record id
     *
     * @return void
     * @access public
     */
    public function add($program_id = null)
    {
        if (! $this->Instructor->Program->exists($program_id))
        {
            $this->Session->setFlash(__('Invalid Program'));
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
            // check if an account/program combination already exists
            $instructor = $this->Instructor->find(
                'first', array(
                    'conditions' => array(
                        'program_id' => $program_id,
                        'account_id' => $searchable_id,
                    )
                )
            );

            if (! empty($instructor))
            {
                $this->Session->setFlash(__('The chosen account is already an account in the system'));
                $this->redirect(array('action' => 'edit', $instructor['Instructor']['id']));
            }

            // format the data
            $this->Instructor->create();
            $this->Instructor->set('account_id', $searchable_id);
            $this->Instructor->set('program_id', $program_id);

            // add the account id to the managers table
            if (! $this->Instructor->save())
            {
                // fail
                $this->Session->setFlash(__('Failed to add instructor'));
                $this->redirect(array('action' => 'index'));
            }

            // redirect
            $this->redirect(
                array(
                    'plugin'     => 'continuing_education',
                    'controller' => 'instructors',
                    'action'     => 'edit',
                    $this->Instructor->id,
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
                'fp'         => 'Accounts',
                'fo'         => 'Account',
                'return'     => base64_encode($this->here),
            ),
            null,
            false,
            'skip'
        );
    }

    /**
     * View an instructor. If no value is passed in this will still work but it will basically be an add page
     *
     * @param int $instructor_id id of instructor
     *
     * @return void
     * @access public
     */
    public function view($instructor_id = null)
    {
        if (! empty($instructor_id))
        {
            $instructor = $this->Instructor->details($instructor_id);

            $this->set('instructor', $instructor);
            $this->set('educations', $instructor['Account']['EducationDegree']);
            $this->set('license_types', ClassRegistry::init('Licenses.LicenseType')->find('list'));
        }
    }

    /**
     * Edit instructor information
     *
     * @param string $id instruction record id
     *
     * @return void
     * @access public
     */
    public function edit($id = null)
    {
        $this->Instructor->id = $id;

        if (! $this->Instructor->exists())
        {
            $this->Session->setFlash(__('invalid instructor'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            if ($this->Instructor->edit($this->request->data))
            {
                $this->Session->setFlash(__('The course has been saved'));
                $this->redirect(array('action' => 'view', $id));
            }
            else
            {
                $this->Session->setFlash(__('The course could not be saved. Please, try again.'));
            }
        }
        else
        {
            $this->request->data = $this->Instructor->details($id);
        }
    }

    /**
     * delete method
     *
     * @param string $id instructor record id
     *
     * @return void
     * @access public
     */
    public function delete($id = null)
    {
        $this->Instructor->id = $id;

        if (!$this->Instructor->exists())
        {
            $this->Session->setFlash(__('invalid instructor'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->Instructor->delete())
        {
            $this->Session->setFlash(__('Instructor deleted'));
            $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('Instructor was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Approve instructor
     *
     * @param int $id Instructor id
     *
     * @return boolean
     * @access public
     */
    public function approve($id = null)
    {
        if (!$this->Instructor->exists($id))
        {
            $this->Session->setFlash(__('invalid instructor'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->Instructor->approve($id))
        {
            if ($this->Instructor->setNotPending($id))
            {
                $this->Session->setFlash(__('Instructor approved'));
            }
            else
            {
                $this->Session->setFlash(__('Error approving instructor'));
            }
        }

        $this->redirect(array('action' => 'view', $id));
    }

    /**
     * Enable an instructor using the AppController defined enable function
     *
     * @param int $id Instructor id
     *
     * @return void
     */
    public function enable($id = null)
    {
        return parent::enable($id);
    }

    /**
     * Disable an instructor using the AppController defined enable function
     *
     * @param int $id Instructor id
     *
     * @return void
     */
    public function disable($id = null)
    {
        return parent::disable($id);
    }
}