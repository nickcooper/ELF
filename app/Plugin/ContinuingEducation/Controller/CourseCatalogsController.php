<?php
/**
 * Course Catalog Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class CourseCatalogsController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseCatalogs';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'ContinuingEducation.CourseCatalog',
        'Accounts.Program',
    );

    /**
     * Pagination options
     *
     * @var array
     * @access public
     */
    public $paginate = array(
        'fields' => array(
            'CourseCatalog.id',
            'CourseCatalog.label',
            'CourseCatalog.program_id',
            'CourseCatalog.code_hours',
            'CourseCatalog.non_code_hours',
            'CourseCatalog.test_attempts',
            'CourseCatalog.enabled',
            'Program.label',
            'Program.abbr'
        ),
        'contain' => array('Program'),
    );

    /**
     * beforeFilter method.
     *
     * @return void
     * @access public
     */
    public function beforeRender()
    {
        parent::beforeRender();

        $this->set('max_test_attempts', 10);
        $this->set('programs', $this->Program->getList());
    }

    /**
     * index method
     *
     * @return void
     * @access public
     */
    public function index()
    {
        $this->set('course_catalogs', $this->paginate());
        $this->set('pending_count', ClassRegistry::init('ContinuingEducation.Instructor')->pendingCount());

        // We're using the Searchble plugin index
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'index',
                'fp'         => 'ContinuingEducation',
                'fo'         => 'CourseCatalog'
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * view method
     *
     * @param string $id expecting course catalog record id
     *
     * @return void
     * @access public
     */
    public function view($id = null)
    {
        if (! $this->CourseCatalog->exists($id))
        {
            throw new NotFoundException(__('Invalid course'));
        }

        $this->set('course', $this->CourseCatalog->details($id));
    }

    /**
     * Add new item to course catalog
     *
     * @return void
     * @access public
     */
    public function add()
    {
        if ($this->request->is('post') || $this->request->is('put'))
        {
            if ($this->CourseCatalog->add($this->request->data))
            {
                $this->Session->setFlash(__('The course has been saved'));
                $this->redirect(array('action' => 'view', $this->CourseCatalog->getLastInsertId()));
            }
            else
            {
                $this->Session->setFlash(__('The course could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * edit method
     *
     * @param string $id expecting catalog record id
     *
     * @return void
     * @access public
     */
    public function edit($id = null)
    {
        $this->CourseCatalog->id = $id;

        if (! $this->CourseCatalog->exists())
        {
            throw new NotFoundException(__('Invalid course'));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            if ($this->CourseCatalog->edit($this->request->data))
            {
                $this->Session->setFlash(__('The course has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The course could not be saved. Please, try again.'));
            }
        }
        else
        {
            $this->request->data = $this->CourseCatalog->details($id);
        }
    }

    /**
     * Enable a course catalog item using the AppController defined enable function
     *
     * @param string $id expecting catalog record id
     *
     * @return void
     */
    public function enable($id = null)
    {
        return parent::enable($id);
    }

    /**
     * Disable a course catalog item using the AppController defined enable function
     *
     * @param string $id expecting catalog record id
     *
     * @return void
     */
    public function disable($id = null)
    {
        return parent::disable($id);
    }
}