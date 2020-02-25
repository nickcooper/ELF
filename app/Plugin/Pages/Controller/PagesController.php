<?php
/**
 * PagesController
 *
 * @package Pages.Controller
 * @author  Iowa Interactive, LLC.
 */
class PagesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Pages';

    /**
     * Layout Configuration
     *
     * @var string
     * @access public
     */
    public $layout_default = 'admin';

    /**
     * Layout config
     *
     * @var array
     * @access public
     */
    public $layout_config = array(
        'default' => array('display', 'home')
    );

    /**
     * beforFilter method
     *
     * @return void
     * @access public
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        // allow access to nonsecure methods
        $this->Auth->allow(array('home', 'display'));
    }

    /**
     * index method
     *
     * Paginated list of pages
     *
     * Defined template variables:
     *      $pages      array       data array containing page and associated data
     *
     * @return void
     * @access public
     */
    public function index()
    {
        $this->set('pages', $this->paginate());
    }

    /**
     * view method
     *
     * Defined template variables:
     *      $page      array       data array containing page and associated data
     *
     * @param int|string $id expecting pages ID.
     *
     * @return void
     * @access public
     */
    public function view($id=null)
    {
        if (!$id)
        {
            $this->Session->setFlash(__('Invalid page.', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('page', $this->Page->getPageById($id));
    }

    /**
     * add method
     *
     * @return void
     * @access public
     */
    public function add()
    {
        // grab the program data
        $this->loadModel('Accounts.Program');
        $this->set('programs', $this->Program->find('list'));

        if ($this->request->is('post'))
        {
            try
            {
                //debug($this->request->data); exit();

                if ($this->Page->add($this->request->data))
                {
                    $this->Session->setFlash(__('The page has been saved.', true));
                    $this->redirect(array('action' => 'index'));
                }
            }
            catch (Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }
    }

    /**
     * edit method
     *
     * Defined template variables:
     *      $page      array       data array containing page and associated data
     *
     * @param int|string $id expecting page ID
     *
     * @return void
     * @access public
     */
    public function edit($id = null)
    {
        if (!$id && empty($this->request->data))
        {
            $this->Session->setFlash(__('Invalid page.', true));
            $this->redirect(array('action' => 'index'));
        }

        // grab the program data
        $this->loadModel('Accounts.Program');
        $this->set('programs', $this->Program->find('list'));

        if (!empty($this->request->data))
        {
            try
            {
                if ($this->Page->edit($this->request->data))
                {
                    $this->Session->setFlash(__('The page has been saved', true));
                    $this->redirect(array('action' => 'index'));
                }
            }
            catch(Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }

        if (empty($this->data))
        {
            $this->data = $this->Page->getPageById($id);
        }
    }

    /**
     * delete method
     *
     * @param int|string $id expecting page ID
     *
     * @return boolean
     * @access public
     */
    public function delete($id = null)
    {
        try
        {
            if ($this->Page->delete($id))
            {
                $this->Session->setFlash(__('The page has been deleted', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        catch(Exception $e)
        {
            // fail
            $this->Session->setFlash($exception->getMessage());
        }
    }

    /**
     * display method
     *
     * Defined template variables:
     *      $title      array       view page title text
     *      $content    array       html formatted page content
     *
     * @param string $slug expecting pages url slug
     *
     * @return void
     * @access public
     */
    public function display($slug = "")
    {
        if ((Configure::read('app.maintenance.mode') !== true && $slug == 'maintenance')
            || ! $page = $this->Page->getPageBySlug($slug)
        )
        {
            throw new NotFoundException();
        }

        // assign the view vars
        $this->set('title', $page['Page']['title']);
        $this->set('content', $page['Page']['content']);
    }

    /**
     * app_admin function
     *
     * This will most likely move, but for now we're placing
     * the page so we can get some browsing into the app
     *
     * @return void
     * @access public
     */
    public function app_admin()
    {
        $this->loadModel('Accounts.NavLink');
        $nav_links = $this->NavLink->getLinksForGroup($this->Auth->user('group_id'));

        $this->Session->write('program_id', null);
        // set some dummy links to send to view

        $action_links = array();

        foreach ($nav_links as $nav_link)
        {
            $action_links[] = array(
                'label' => $nav_link['NavLink']['label'],
                'descr' => $nav_link['NavLink']['descr'],
                'path' => $nav_link['NavLink']['path']
            );
        }

        // assign view vars
        $this->set('action_links', $action_links);
    }

    /**
     * program_admin function
     *
     * This will most likely move, but for now we're placing
     * the page so we can get some browsing into the app
     *
     * @param id $id program id
     *
     * @return void
     * @access public
     */
    public function program_admin($id = null)
    {
        // get the group
        $this->loadModel('Accounts.Group');
        $group = $this->Group->findById($this->Auth->user('group_id'));

        if ($id && $group['Group']['label'] != 'Super Admin')
        {
            if ($id != $group['Group']['group_program_id'])
            {
                $this->Session->setFlash('You do not have access to view requested program.');
                $this->redirect('/');
            }
        }

        // if the id isn't passed get it from the auth user's group
        if (!$id)
        {
            // set the program id
            $id = $group['Group']['program_id'];
        }

        // do we have a program id?
        if (!$id)
        {
            // fail
            $this->Session->setFlash('Failed to load program data, missing program id.');
            $this->redirect('/');
        }

        // Query for program data
        $this->loadModel('Accounts.Program');
        $program = $this->Program->find(
            'first',
            array(
                'contain' => array(
                    'Group'
                ),
                'conditions' => array(
                    'Program.id' => $id
                )
            )
        );

        $this->Session->write('program_id', $id);
        $this->set('programs', $program);

        $this->loadModel('Accounts.NavLink');
        $nav_links = $this->NavLink->getLinksForGroup($program['Group'][0]['id']);

        // set some dummy links to send to view
        $action_links = array();

        foreach ($nav_links as $nav_link)
        {
            $action_links[] = array(
                'label' => $nav_link['NavLink']['label'],
                'descr' => $nav_link['NavLink']['descr'],
                'path' => $nav_link['NavLink']['path']
            );
        }

        // assign view vars
        $this->set('action_links', $action_links);
    }

    /**
     * Temporary home page fix. This will be replaced by more
     * permanent, database driven solution later.
     *
     * @return void
     * @access public
     */
    public function home()
    {
    }

    /**
     * Displays application cache. Allows user to manually clear cache.
     *
     * @param string $action Action ('clear' to clear cache)
     *
     * @return void
     * @access public
     */
    public function cache($action = null)
    {
        if (strtolower($action) == 'clear')
        {
            try
            {
                GenLib::clearCache();
                $this->Session->setFlash(__('Cache cleared'));
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }

            $this->redirect(array('action' => $this->request->params['action']));
        }

        $this->set('cacheFiles', GenLib::cacheFiles());
    }

    /**
     * Enable a page using the AppController defined enable function
     *
     * @param id $id page id
     *
     * @return void
     */
    public function enable($id = null)
    {
        return parent::enable($id);
    }

    /**
     * Disable a page using the AppController defined enable function
     *
     * @param id $id page id
     *
     * @return void
     */
    public function disable($id = null)
    {
        return parent::disable($id);
    }
}