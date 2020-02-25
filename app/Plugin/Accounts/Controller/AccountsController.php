<?php
/**
 * AccountsController
 *
 * @category Accounts
 * @package  Accounts.Controller
 * @author   Iowa Interactive, LLC.
 */
class AccountsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Accounts';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Account', 'ContinuingEducation.CourseRoster');

    /**
     * beforeFilter method
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        // whitelist the public auth stuff
        $this->Auth->allow(array('login', 'logout', 'register', 'confirm_user', 'decline_confirm_user'));
    }

    /**
     * logout method
     *
     * Destroy the session and redirect to site home page.
     *
     * @return void
     */
    public function logout()
    {
        $this->Session->destroy();
        $this->Session->setFlash(__('Logout successful.'));
        $this->redirect('/');
    }

    /**
     * home method
     *
     * An action to redirect to the correct user home page
     * defined by it's group.
     *
     * All home redirects should redirect here.
     *
     * @return void
     * @access public
     */
    public function home ()
    {
        try
        {
            // get the login user group
            if (!$group = $this->Account->Group->findById($this->Auth->user('group_id')))
            {
                $msg = sprintf(
                    'Failed to find group for group_id (%s) for user (%s).',
                    $this->Auth->user('group_id'),
                    $this->Auth->user('label')
                );

                throw new Exception($msg);
            }

            // default home page
            $home = '/my_account';

            // redirect to the proper home page
            if (!empty($group['Group']['home']))
            {
                $home = $group['Group']['home'];
            }

            // redirect
            $this->redirect($home);
        }
        catch (Exception $e)
        {
            // log the error
            $this->log($e->getMessage());

            // set error flash msg
            $this->Session->setFlash('Failed to determine account home location.');

            // redirect to app home
            $this->redirect('/');
        }
    }

    /**
     * index method
     *
     * Paginated list of accounts
     *
     * @return void
     */
    public function index()
    {
        // We're using the Searchble plugin index
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'index',
                'fp'         => 'Accounts',
                'fo'         => 'Account'
            )
        );

        $this->set('accounts', $this->paginate());
    }

    /**
     * View single record data
     *
     * @param int|string $id expecting account ID
     *
     * @return void
     */
    public function view($id = null)
    {
        // using the dynamic entity view page
        $this->redirect(
            array(
                'plugin' => 'licenses',
                'controller' => 'licenses',
                'action' => 'entity',
                'fp' => 'Accounts',
                'fo' => 'Account',
                'fk' => $id
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * My account page
     *
     * @param int $myID account ID
     *
     * @return void
     */
    public function my_account($myID = null)
    {


        if (!$myID)
        {
            $id = $this->Auth->user('id');
        }
        else
        {
            $id = $myID;
        }

        // temp fix until this page disappears
        $aro_alias = $this->Acl->Aro->field('alias', array('Aro.model' => 'Account', 'Aro.foreign_key' => $this->Auth->user('id')));
        if ($this->checkOwnerOrManager('Accounts.Account', $id, false)
            && $this->Acl->check($aro_alias, 'controllers/accounts/accounts/view')
            && $this->Acl->check($aro_alias, 'controllers/licenses/licenses/entity')
        )
        {
            $this->redirect(sprintf('/accounts/accounts/view/%s', $id));
        }

        $contain = array(
            'Address',
            'Note' => array('Account'),
            'License' => array('LicenseType', 'LicenseStatus', 'Application' => array('ApplicationStatus')),
            'WorkExperience',
            'PracticalWorkExperience' => array('PracticalWorkExperienceType'),
            'PracticalWorkPercentage' => array('PracticalWorkPercentageType'),
            'EducationDegree' => array('Degree', 'Upload'),
            'OtherLicense',
            'CourseRoster' => array('CourseSection' => array('CourseCatalog', 'TrainingProvider')),
            'Document',
            'AccountPhoto',
            'Reference' => array('Contact', 'Address'),
            'InsuranceInformation' => array('Upload')
        );

        $account = $this->Account->details($id, $contain);

        // get the managed licenses
        //$this->set('managed_licenses', $this->Account->Manager->getManagedLicenses($account['Account']['id']));

        // Grab Account/License Information
        $this->set('account', $account);

        // set a view var for the application info
        $apps = array();
        foreach($account['License'] as $lic)
        {
            if($lic['Application'][0]['open'] == 1)
            {
                $apps[] = $lic;
            }
        }

        $this->set('app_info', $apps);

        // grab the license types for the add new license link
        $this->set(
            'license_types',
            $this->Account->License->LicenseType->find(
                'list',
                array(
                    'conditions' => array(
                        'LicenseType.avail_for_initial' => 1
                    )
                )
            )
        );

        //initialize element model to get list of elements that pertain to the account view
        $ElementModel = ClassRegistry::init('Licenses.Element');
        $app_elements = $ElementModel->findAllByForeignObj('Account');

        $element_descriptions = $ElementModel->find(
            'list',
            array(
                'conditions' => array('Element.foreign_obj' => 'Account'),
                'fields' => array('label', 'description')
            )
        );

        $this->set('app_elements', $app_elements);
        $this->set('element_descriptions', $element_descriptions);

        // grab the note count
        $this->set('note_count', $this->Account->Note->noteCount('Account', $id));

        // find the courses that apply to this license type
        $this->CourseCatalogsLicenseTypes = ClassRegistry::init('ContinuingEducation.CourseCatalogsLicenseType');
        $valid_courses_lookup = $this->CourseCatalogsLicenseTypes->find('all');

        // extract the valid course catalog ids and pass to the view
        $valid_course = Hash::extract($valid_courses_lookup, '{n}.CourseCatalogsLicenseType.course_catalog_id');
        $this->set('valid_courses', $valid_course);

        // account link
        $this->set('fo_link', null);
    }

    /**
     * Add new account
     *
     * @return void
     *
     * @todo split this in to two actions - one adds a public account (short_form)
     * the other an admin account (full form).
     */
    public function add()
    {
        // grab a list of groups
        $groups = $this->Account->Group->find('list');
        $this->set('groups', $groups);

        if (! empty($this->data))
        {
            try
            {
                if ($this->Account->addAccount($this->data))
                {
                    $this->Session->setFlash(__('The account has been saved.'));
                    $this->redirect(
                        array(
                            'plugin'     => 'accounts',
                            'controller' => 'accounts',
                            'action'     => 'view',
                            $this->Account->getLastInsertID(),
                        )
                    );
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
     * @param int|string $id expecting account ID
     *
     * @return void
     */
    public function edit($id = null)
    {
        $this->checkOwnerOrManager('Accounts.Account', $id);

        // grab a list of groups
        $groups = $this->Account->Group->find('list');
        $this->set('groups', $groups);
        $this->set('note_count', $this->Account->Note->noteCount('Account', $id));

        if (! $id && empty($this->request->data))
        {
            $this->Session->setFlash(__('Invalid account.'));
            $this->redirect(array('action' => 'index'));
        }

        if (! empty($this->request->data))
        {
            try
            {
                if ($this->Account->updateAccount($this->request->data))
                {
                    $this->Session->setFlash(__('The account has been saved'));
                    $this->redirect(array('action' => 'view', $id));
                }
            }
            catch(Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }

        $this->Account->contain('Address', 'AccountPhoto');
        $this->request->data = $this->Account->getAccountById($id);

        // remove 'required' flag on edit
        if (! empty($this->request->data['Account']['ssn']))
        {
            foreach ($this->Account->validate['ssn'] as $rule => $options)
            {
                $this->Account->validate['ssn'][$rule]['allowEmpty'] = true;
            }
        }
    }

    /**
     * Delete Method
     *
     * @param int|string $id expecting account ID
     *
     * @return boolean
     */
    public function delete($id = null)
    {
        try
        {
            $this->checkOwnerOrManager('Accounts.Account', $id);

            if ($this->Account->delete($id))
            {
                $this->Session->setFlash(__('The account has been deleted'));
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
     * register function
     *
     * @return void
     * @access public
     */
    public function register()
    {
        try
        {
            // pass the aa user data to the view
            $this->set('user', $this->Session->read('AaAuth.aa_user_data'));

            // process the registration form post
            if ($this->request->is('post') || $this->request->is('put'))
            {
                $data = $this->request->data;

                // attempt to match user to a local account based on SSN
                $account = $this->Account->getAccountBySSN($data['Account']['ssn']);

                if ($account)
                {
                    if (!empty($account['Account']['username']) && $account['Account']['username'] !== $this->Session->read('AaAuth.aa_user_data.user_id'))
                    {
                        $this->Session->setFlash(
                            sprintf(
                                'Registration failed. The account matching your information is already owned by %s. Please contact the State Agency.',
                                strtolower($account['Account']['username'])
                            )
                        );
                    }
                    else
                    {
                        // if a match was found and it hasn't already been claimed, redirect to the claim page
                        $dob = sprintf(
                            '%s-%s-%s',
                            $data['Account']['dob']['year'],
                            $data['Account']['dob']['month'],
                            $data['Account']['dob']['day']
                        );

                        $this->redirect(
                            array(
                                'action' => 'confirm_user',
                                $account['Account']['id'],
                                'dob' => $dob
                            )
                        );
                    }
                }
                else
                {
                    // format dob
                    $dob = sprintf('%s-%s-%s', $data['Account']['dob']['year'], $data['Account']['dob']['month'], $data['Account']['dob']['day']);

                    // if no match was found, format the account data for new account save
                    $account['Account']['username'] = $this->Session->read('AaAuth.aa_user_data.user_id');
                    $account['Account']['group_id'] = 1;
                    $account['Account']['first_name'] = $this->Session->read('AaAuth.aa_user_data.first_name');
                    $account['Account']['last_name'] = $this->Session->read('AaAuth.aa_user_data.last_name');
                    $account['Account']['email'] = $this->Session->read('AaAuth.aa_user_data.email');
                    $account['Account']['dob'] = $dob;
                    $account['Account']['ssn'] = $data['Account']['ssn'];

                    // create the new account
                    if (!$this->Account->add($account))
                    {
                        $this->log(serialize($this->Account->validationErrors));
                        throw new Exception('Registration failed. Could not save new account data.');
                    }

                    // force login the account
                    $this->Auth->login($this->Account->getAuthUserData($this->Account->getLastInsertId()));

                    // redirect
                    $this->redirect('/home');
                }
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect('/');
        }
    }

    /**
     * confirm_user function
     *
     * This will present a page to the user to confirm that the found account is their's
     * so that they can claim it and tie it to their A&A login account.
     *
     * @param int $id Existing user ID
     *
     * @return void
     * @access public
     *
     * @todo Handle case when perjury checkbox is not checked but user clicked on confirm button
     */
    public function confirm_user($id=null)
    {
        $existing_user = $this->Account->find(
            'first',
            array(
                'contain' => array(
                    'License' => array(
                        'LicenseType',
                        'LicenseStatus'
                    ),
                    'ManagedFirm',
                    'ManagedTrainingProvider',
                    'CourseRoster' => array(
                        'CourseSection' => array(
                            'CourseCatalog',
                            'TrainingProvider'
                        )
                    )
                ),
                'conditions' => array(
                    'Account.id' => $id
                )
            )
        );

        $this->set('account', $existing_user);

        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                if ((boolean) $this->request->data['Account']['perjury_acknowledged'] === false)
                {
                    throw new Exception(__('You must agree to the acknowledgement agreement'));
                }

                // user agreed to perjury statement, set some data for save
                $account['Account']['id'] = $existing_user['Account']['id'];
                $account['Account']['username'] = $this->Session->read('AaAuth.aa_user_data.user_id');
                $account['Account']['email'] = $this->Session->read('AaAuth.aa_user_data.email');

                // update the dob if passed
                if (isset($this->params['named']['dob']))
                {
                    $account['Account']['dob'] = $this->params['named']['dob'];
                }

                // add the AA username to the existing account
                if (!$this->Account->edit($account))
                {
                    throw new Exception('Failed to claim local user account.');
                }

                // login as the local user and redirect to their account page
                $this->Auth->login($this->Account->getAuthUserData($account['Account']['id']));
                $this->redirect('/home');
            }
            catch (Exception $e)
            {
                $this->Auth->logout();
                $this->Session->setFlash(
                    sprintf(
                        __('Unable to confirm user account: %s.'),
                        $e->getMessage()
                    )
                );
            }
        }
    }

    /**
     * Declines a user confirmation.
     *
     * @param int $id User ID
     *
     * @return void
     * @access public
     */
    public function decline_confirm_user($id = null)
    {
        try
        {
            $this->Account->disable($id);
            $this->Auth->logout();
            $this->Session->setFlash(__('User confirmation declined. Account disabled.'));
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect('/');
    }

    /**
     * editEmail method
     *
     * @param int|string $id expecting account ID
     *
     * @return void
     */
    public function editEmail($id = null)
    {
        $this->set('note_count', $this->Account->Note->noteCount('Account', $id));

        if (! $id && empty($this->request->data))
        {
            $this->Session->setFlash(__('Invalid account.'));
            $this->redirect(array('action' => 'index'));
        }

        if (! empty($this->request->data))
        {
            try
            {
                if ($this->Account->updateAccount($this->request->data))
                {
                    $this->Session->setFlash(__('The account has been saved'));
                    $this->redirect(array('action' => 'view', $id));
                }
            }
            catch(Exception $exception)
            {
                // fail
                $this->Session->setFlash($exception->getMessage());
            }
        }

        $this->request->data = $this->Account->getAccountById($id);

        // remove 'required' flag on edit
        if (! empty($this->request->data['Account']['ssn']))
        {
            foreach ($this->Account->validate['ssn'] as $rule => $options)
            {
                $this->Account->validate['ssn'][$rule]['allowEmpty'] = true;
            }
        }
    }
}