<?php
/**
 * LicensesController
 *
 * @package Licenses.Controller
 * @author  Iowa Interactive, LLC.
 */
class LicensesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Licenses';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Licenses.License',
        'Firms.Firm',
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * Default pagination options.
     *
     * @var Array
     * @access public
     */
    public $paginate = array(
        'contain' => array(
            'Application' => array(
                'order' => array('Application.id' => 'DESC'),
                'ApplicationType'
            ),
            'LicenseType',
            'LicenseStatus',
            'LicenseVariant'
        ),
        'limit' => 10,
        'conditions' => array(),
        'order' => array('License.modified' => 'DESC'),
    );

    /**
     * index method
     *
     * Paginated list of licenses
     *
     * @return bool
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
                'fp'         => 'Licenses',
                'fo'         => 'License',
                'cf'         => 'complete'
            )
        );
    }

    /**
     * view method
     *
     * @param int $id license ID expected.
     *
     * @return bool
     * @access public
     *
     * @todo Validate the application is complete before submitting.
     * @todo Make data visible when locked but block edits to application specific data.
     * @todo Andrew will need to style the elements that are required.
     */
    public function view ($id = null)
    {
        try
        {
            // check for ownership or managership
            $this->checkOwnerOrManager('Licenses.License', $id);

            // get the license info
            if (!$license = $this->License->getApplication($id))
            {
                $this->Session->setFlash(__('Invalid license.'));
                $this->redirect($this->referer());
            }

            // if account license add course roster records based on license type
            if (isset($license['Account']['id']))
            {
                $license['Account']['CourseRoster'] = ClassRegistry::init('ContinuingEducation.CourseRoster')->getByLicenseTypeForAccount($license['LicenseType']['id'], $license['Account']['id']);
            }

            // define the foreign obj controller and view variables
            // the ForeignObject component assigns the view vars automatically
            $this->ForeignObject->init(
                $this,
                $license['License']['foreign_plugin'],
                $license['License']['foreign_obj'],
                $license['License']['foreign_key']
            );

            // get the sections for this page (plugin/controller/action)
            $sections = ClassRegistry::init('DynamicSection')->find(
                'all',
                array(
                    'conditions' => array('DynamicSection.section_key' => sprintf('licenses/view.%s', $license['LicenseType']['abbr'])),
                    'order' => array('DynamicSection.order')
                )
            );

            // get the license document links
            $doc_links = $this->License->getLicenseDocLinks($license['License']['id']);

            // show or not show doc links in license info section
            if (!count($doc_links))
            {
                Configure::write('App.DataBlock.License.additional_sections.doc_links.display', false);
            }

            // define the admin action links
            $admin_actions = array();

            // add the suspend action to admin actions
            if ($license['LicenseStatus']['status'] == 'Active')
            {
                $admin_actions[] = array(
                    'label' => 'Suspend License',
                    'url' => '/licenses/licenses/suspend/'.$license['License']['id'],
                    'attr' => array(
                        'title' => 'Suspend License',
                        'class' => 'button small modal'
                    )
                );
            }

            // add the activate action to admin actions
            if ($license['LicenseStatus']['status'] != 'Active')
            {
                $admin_actions[] = array(
                    'label' => 'Activate License',
                    'url' => '/licenses/licenses/activate/'.$license['License']['id'],
                    'attr' => array(
                        'title' => 'Activate License',
                        'class' => 'button small'
                    )
                );
            }

            // add edit application expire date link to admin actions?
            if ($license['LicenseStatus']['status'] != 'Incomplete')
            {
                $admin_actions[] = array(
                    'label' => 'Edit Application Expiration Date',
                    'url' => '/licenses/applications/edit_expire_date/'.$license['CurrentApplication']['id'],
                    'attr' => array(
                        'title' => 'Edit Application Expiration Date',
                        'class' => 'button small modal'
                    )
                );
            }

            // add application bypass to admin actions?
            if (GenLib::isData($license, 'OpenApplication', array('id')))
            {
                $admin_actions[] = array(
                    'label' => 'Bypass Application Validation Requirements',
                    'url' => '/licenses/applications/bypass/'.$license['OpenApplication']['id'],
                    'attr' => array(
                        'title' => 'Bypass Application Validation Requirements',
                        'class' => 'button small'
                    )
                );
            }

            // add application remove interim date to admin actions?
            if (GenLib::isData($license, 'CurrentApplication', array('interim_expire_date')))
            {
                $admin_actions[] = array(
                    'label' => 'Remove Interim Expiration',
                    'url' => '/licenses/applications/remove_interim/'.$license['CurrentApplication']['id'],
                    'attr' => array(
                        'title' => 'Remove Interim Expiration',
                        'class' => 'button small'
                    )
                );
            }

            // get a list of convertible license types
            $convert_license_types = $this->License->LicenseType->getConversionTypes($license['LicenseType']['id']);

            if (!count($convert_license_types))
            {
                Configure::write('App.DataBlock.License.additional_sections.convert_license_type.display', false);
            }

            // display the renew button is license is renewable
            if ($this->License->canRenew($id))
            {
                Configure::write('App.DataBlock.License.buttons.renew.display', true);
            }

            // show view button for this action
            Configure::write('App.DataBlock.CurrentApplication.buttons.view.display', true);

            if ($license['OpenApplication']['id'] == null)
            {
                Configure::write('App.DataBlock.CurrentApplication.buttons.reopen.display', true);

            }
            else
            {
                if (isset($license['OpenApplication']['ApplicationStatus']['label']))
                {
                    if ($license['OpenApplication']['ApplicationStatus']['label'] == 'Incomplete')
                    {
                        Configure::write('App.DataBlock.CurrentApplication.additional_sections.incomplete_application.display', true);
                    }
                    if ($license['OpenApplication']['ApplicationStatus']['label'] == 'Pending')
                    {
                        Configure::write('App.DataBlock.CurrentApplication.additional_sections.pending_application.display', true);
                    }
                }
            }

            // if license is pending, turn off remove button display
            if ($license['License']['pending'])
            {
                Configure::write('App.DataBlock.OpenApplication.buttons.remove.display', false);
            }


            // set view variables
            $this->set(
                array(
                    'license' => $license,
                    // sections
                    'sections' => $sections,
                    'admin_actions' => $admin_actions,
                    // section nav
                    'fo_link' => array(
                        'plugin' => Inflector::underscore(lcfirst($this->foreign_plugin)),
                        'controller' => $this->foreign_controller,
                        'action' => 'view',
                        $license[$this->foreign_obj]['id']
                    ),
                    'header' => $license['LicenseType']['label'],
                    'sub_header' => (isset($license['License']['license_number']) ? $license['License']['license_number'] : '<em>Not Submitted</em>'),
                    'label' => $license['License']['label'],
                    'note_count' => $this->License->Note->noteCount('License', $id),
                    'license_id' => $license['License']['id'],
                    // doc links
                    'doc_links' => $doc_links,
                    // convert license types
                    'convert_license_types' => $convert_license_types,
                    // data for open application
                    'open_application' => isset($license['OpenApplication']['ApplicationStatus']['label']) ? $license['OpenApplication'] : null,
                    // set bypass variables
                    'bypass_validation' => $license['OpenApplication']['bypass_validation'],
                    'bypass_validation_id' => $license['OpenApplication']['id']
                )
            );
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * suspend method
     *
     * @param int $id license ID.
     *
     * @return bool
     * @access public
     */
    public function suspend ($id = null)
    {
        try
        {
            // check for form submit
            if ($this->request->is('post'))
            {
                // attempt to suspend the licnese
                if ($this->License->suspend($id, $this->request->data['Note'][0]['note']))
                {
                    // set sucess message
                    $this->Session->setFlash('License has been suspended.');

                    // done, if not modal redirect back to license view
                    $this->redirect(array('action' => 'view', $id));
                }
            }

            // get the license record
            $license = $this->License->find(
                'first',
                array(
                    'contain' => array('LicenseStatus'),
                    'conditions' => array('License.id' => $id)
                )
            );

            $this->set('license', $license);
        }
        catch (Exception $e)
        {
            // set error message
            $this->Session->setFlash(sprintf('Failed to suspend license. %s', $e->getMessage()));
        }
    }

    /**
     * activate method
     *
     * @param int $id license ID.
     *
     * @return bool
     * @access public
     */
    public function activate ($id = null)
    {
        try
        {
            // attempt to activate the licnese
            $this->License->activate($id);

            // set sucess message
            $this->Session->setFlash('License has been activated.');
        }
        catch (Exception $e)
        {
            // set error message
            $this->Session->setFlash(sprintf('Failed to activate license. %s', $e->getMessage()));
        }

        // done, redirect back to license view
        $this->redirect(
            array(
                'action' => 'view',
                $id
            )
        );
    }

    /**
     * renew method
     *
     * @param int $id license record id
     *
     * @return bool
     * @access public
     */
    public function renew ($id)
    {
        try
        {
            $this->checkOwnerOrManager('Licenses.License', $id);

            // check that the license is renewable
            if (!$this->License->canRenew($id))
            {
                throw new Exception(__('License is not renewable at this time.'));
            }

            // check for any open applications for this license
            if ($open_app = $this->License->getOpenApplication($id))
            {
                $application_id = $open_app['Application']['id'];
            }
            else
            {
                $application_id = $this->License->renewLicense($id);
            }

            // submit the application, redirect to license view
            $this->redirect(
                array(
                    'controller' => 'applications',
                    'action'     => 'view',
                    $application_id,
                ),
                null,
                true,
                'skip'
            );
        }
        catch (Exception $e)
        {
            $this->log($e->getMessage());
            $this->Session->setFlash(__('License renewal failed. ' . $e->getMessage()));

            // submit the application, redirect to license view
            $this->redirect(
                array(
                    'controller' => 'licenses',
                    'action'     => 'view',
                    $id,
                ),
                null,
                true,
                'skip'
            );
        }
    }

    /**
     * add method
     *
     * @param int $id license type id
     *
     * @return bool
     * @access public
     *
     * @todo public side - if foreign obj is Account skip the foreign obj search and use login Account data
     */
    public function add ($id)
    {
        try
        {
            // get the license type
            if (! $license_type = $this->License->LicenseType->getLicenseTypeById($id))
            {
                // fail
                throw new Exception(__('Invalid license type provided.'));
            }

            // define the controller and view vars for foreign obj
            $this->ForeignObject->init(
                $this,
                $license_type['LicenseType']['foreign_plugin'],
                $license_type['LicenseType']['foreign_obj'],
                null
            );

            // do we have a searchable id?
            $foreign_key = null;
            if (isset($this->params['named']['searchable']))
            {
                $foreign_key = $this->params['named']['searchable'];
            }
            elseif ($this->request->is('post') && isset($this->request->data['Searchable']))
            {
                $foreign_key = $this->request->data['Searchable'];
            }

            // if auth user is default user then force foreign_key to be the auth user id
            $GroupModel = ClassRegistry::init('Accounts.Group');
            $group = $GroupModel->findById($this->Auth->user('group_id'));

            // force indvidual licenses to be owned by default group users, prevents default users from adding license to other users
            if ($license_type['LicenseType']['foreign_obj'] == 'Account' && $group && $group['Group']['label'] == 'Default Group')
            {
                $foreign_key = $this->Auth->user('id');
            }

            // check for Searchable id
            if ($foreign_key)
            {
                $existing_license = $this->License->getForeignObjLicense(
                    $foreign_key,
                    $this->foreign_obj,
                    $this->foreign_plugin,
                    $license_type['LicenseType']['id']
                );

                // check to see if this foreign obj already has a license of the same license type
                if ($existing_license)
                {
                    // yes - redirect to the license edit page
                    $this->redirect(
                        array(
                            'plugin'     => 'licenses',
                            'controller' => 'applications',
                            'action'     => 'view',
                            $existing_license['CurrentApplication']['id'],
                        ),
                        null,
                        true,
                        'skip'
                    );
                }

                // no existing license for this foreign obj and license type - create one
                try
                {
                    $license = $this->License->newLicense(
                        $license_type['LicenseType']['slug'],
                        $foreign_key,
                        $this->foreign_obj,
                        $this->foreign_plugin
                    );

                    if (! $license)
                    {
                        // fail
                        throw new Exception(__('Failed to create new license application.'));
                    }
                }
                catch (Exception $e)
                {
                    throw $e;
                }

                // done - redirect to the license edit page
                $this->redirect(
                    array(
                        'plugin'     => 'licenses',
                        'controller' => 'applications',
                        'action'     => 'view',
                        $license['CurrentApplication']['id'],
                    ),
                    null,
                    true,
                    'skip'
                );
            }

            // prevent redirect loop for searchable locators cancel button
            if (preg_match('/searchable\/locator/i', $this->referer()))
            {
                // return
                $this->redirect();
            }

            // no Searchable id, let's go get one
            $this->redirect(
                array(
                    'plugin'     => 'searchable',
                    'controller' => 'searchable',
                    'action'     => 'locator',
                    'fp'         => $license_type['LicenseType']['foreign_plugin'],
                    'fo'         => $license_type['LicenseType']['foreign_obj'],
                    'return'     => base64_encode($this->here)
                ),
                null,
                true,
                'skip'
            );

        }
        catch (Exception $e)
        {
            $this->Session->setFlash(
                sprintf(__('Failed to save License data. (%s)'), $e->getMessage())
            );

            $this->redirect(
                array(
                    'controller' => 'licenses',
                    'action'     => 'index',
                ),
                null,
                true,
                'skip'
            );
        }
    }

    /**
     * convert method
     *
     * Largely mimics the add method
     *
     * @param int $id         license record ID
     * @param int $license_id license ID to convert
     *
     * @return void
     * @access public
     */
    public function convert ($id = null, $license_id=null)
    {
        try
        {
            // get the license type
            if (! $license_type = $this->License->LicenseType->getLicenseTypeById($id))
            {
                // fail
                throw new Exception(__('Invalid license type provided.'));
            }

            // check for any open applications that have been converted from this license
            if ($this->License->openConvertedApplications($license_id))
            {
                throw new Exception(__('An open application already exists.'));
            }

            // check that the license is convertible
            if (!$this->License->canConvert($license_id, $id))
            {
                throw new Exception(__('License can not be converted.'));
            }

            // define the controller and view vars for foreign obj
            $this->ForeignObject->init(
                $this,
                $license_type['LicenseType']['foreign_plugin'],
                $license_type['LicenseType']['foreign_obj'],
                null
            );

            $GroupModel = ClassRegistry::init('Accounts.Group');
            $group = $GroupModel->findById($this->Auth->user('group_id'));

            // if auth user is default user then force foreign_key to be the auth user id
            if ($group['Group']['label'] == 'Default Group')
            {
                $foreign_key = $this->Auth->user('id');
            }
            else // an admin is processing, so set the foreign key to the account on the license being converted
            {
                $lic_to_convert = ClassRegistry::init('Licenses.License');
                $lic_to_convert = $lic_to_convert->getLicenseById($license_id);

                if (GenLib::isData($lic_to_convert, 'Account', array('id')))
                {
                    $foreign_key = $lic_to_convert['Account']['id'];
                }
                else // a firm license is being converted, so map the foreign_key accordingly
                {
                    if (GenLib::isData($lic_to_convert, 'Firm.Manager.0.Account', array('id')))
                    {
                        $foreign_key = $lic_to_convert['Firm']['Manager'][0]['Account']['id'];
                    }
                }
            }

            // check for existing license
            if ($foreign_key)
            {
                $existing_license = $this->License->getForeignObjLicense(
                    $foreign_key,
                    $this->foreign_obj,
                    $this->foreign_plugin,
                    $license_type['LicenseType']['id']
                );

                // check to see if this foreign obj already has a license of the same license type
                if ($existing_license)
                {
                    // yes - redirect to the license edit page
                    $this->redirect(
                        array(
                            'plugin'     => 'licenses',
                            'controller' => 'applications',
                            'action'     => 'view',
                            $existing_license['CurrentApplication']['id'],
                        ),
                        null,
                        true,
                        'skip'
                    );
                }

                // no existing license for this foreign obj and license type - create one
                try
                {
                    $license = $this->License->newLicense(
                        $license_type['LicenseType']['slug'],
                        $foreign_key,
                        $this->foreign_obj,
                        $this->foreign_plugin,
                        '',
                        'Conversion',
                        $license_id
                    );

                    if (! $license)
                    {
                        // fail
                        throw new Exception(__('Failed to create new license application.'));
                    }
                }
                catch (Exception $e)
                {
                    throw $e;
                }

                // done - redirect to the license edit page
                $this->redirect(
                    array(
                        'plugin'     => 'licenses',
                        'controller' => 'applications',
                        'action'     => 'view',
                        $license['CurrentApplication']['id'],
                    ),
                    null,
                    true,
                    'skip'
                );
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash(
                sprintf(__('Failed to convert License. (%s)'), $e->getMessage())
            );

            $this->redirect(
                array(
                    'plugin'     => 'licenses',
                    'controller' => 'applications',
                    'action'     => 'index',
                )
            );
        }
    }

    /**
     * License entity view page
     *
     * This page is a overview of licensing data associated
     * to a licensable entity (Account, Firm, TrainingProvider).
     *
     * @param str $type Expecting the type of license entity (lowercase/underscored)
     * @param int $id   Expecting the record ID for the license entity
     *
     * @return void
     * @access public
     */
    public function entity ()
    {
        try
        {
            // define the foreign obj controller and view variables
            $this->ForeignObject->init(
                $this,
                $this->params['named']['fp'],
                $this->params['named']['fo'],
                $this->params['named']['fk']
            );

            // double check we have both obj and key
            if (!$this->foreign_obj || !$this->foreign_key)
            {
                throw new Exception ('Invalid license entity');
            }

            // check for ownership or managership
            $this->checkOwnerOrManager(sprintf('%s.%s', $this->foreign_plugin, $this->foreign_obj), $this->foreign_key);

            // convert the type value to a model/alias format
            $entity = Inflector::classify($this->foreign_obj);
            $this->set('entity', $entity);

            // get the App.EntityPage config
            if (!$entity_config = Configure::read('App.EntityPage.'.$entity))
            {
                throw new Exception ('Entity is not configured.');
            }

            // assign entity config to the view
            $this->set($entity_config);

            // check for actsAs License configuration
            if (!array_key_exists('Licenses.License', $this->License->{$entity}->actsAs))
            {
                throw new Exception ('Entity does not act as License.');
            }

            // grab the actsAs configuration
            $actsAs_config = $this->License->{$entity}->actsAs['Licenses.License'];

            // get the entity data
            $data = $this->License->{$entity}->find(
                'first',
                array(
                    'conditions' => array($entity.'.id' => $this->foreign_key),
                    'contain' => $actsAs_config['entity']['contain']
                )
            );
            //print "<pre>"; print_r($data); print "</pre>";

            // did we get data?
            if (!$data)
            {
                throw new Exception ('Entity could not be found.');
            }

            // determine which licenses are renewable and set can_renew. Used for renew action on record.
            if (isset($data['CompletedLicense']) && count($data['CompletedLicense']) > 0)
            {
                foreach ($data['CompletedLicense'] as $key => $completed_license)
                {
                    $data['CompletedLicense'][$key]['can_renew'] = $this->License->canRenew($completed_license['id']);
                }
            }

            // assign data to the view
            $this->set('data', $data);

            // get the sections for this page (plugin/controller/action)
            $sections = ClassRegistry::init('DynamicSection')->find(
                'all',
                array(
                    'conditions' => array('DynamicSection.section_key' => sprintf('licenses/entity.%s', strtoupper($entity))),
                    'order' => array('DynamicSection.order')
                )
            );
            $this->set('sections', $sections);

            $this->set('license_types',$this->License->LicenseType->find(
                'list',
                array(
                    'conditions' => array(
                        'LicenseType.avail_for_initial' => 1,
                        'LicenseType.foreign_obj' => $entity
                    )
                )
            )
        );
        }
        catch (Exception $e)
        {
            // catach exceptions and redirect w/ error msg
            $this->Session->setFlash($e->getMessage());
            $this->redirect('/accounts/accounts/home');
        }
    }

    /**
     * Associates two types of licenses to one another.
     *
     * Redirects to Searchable Locator to find the license and
     * returns the found license ID back here to associate to
     * the original license ID.
     *
     * @param int $id    License ID
     * @param str $assoc Association type, default parent else child
     *
     * @return void
     * @access public
     */
    public function associate_license($id = null, $assoc = 'parent')
    {
        try
        {
            // get the original license record
            if (!$license = $this->License->findById($id))
            {
                throw new Exception ('Failed to find license record.');
            }

            // look for the Searchable post
            if (isset($this->request->data['Searchable']) || isset($this->params['named']['searchable']))
            {
                // define the found license ID
                $found_id = false;
                switch (true)
                {
                    case isset($this->request->data['Searchable']) :
                        $found_id = $this->request->data['Searchable'];
                        break;

                    case isset($this->params['named']['searchable']) :
                        $found_id = $this->params['named']['searchable'];
                        break;
                }

                // did we find an id?
                if (!$found_id)
                {
                    throw new Exception ('Missing searchable id.');
                }

                // define which model association to use
                $data = array();
                switch ($assoc)
                {
                    // original license is parent adding a child
                    case 'parent' :
                        $data = array('parent_id' => $id, 'child_id' => $found_id);
                        break;

                    // original license is child adding to a parent
                    case 'child' :
                        $data = array('child_id' => $id, 'parent_id' => $found_id);
                        break;
                }

                // format the data for save
                $save_data = array( 'LicensesLicense' => $data);

                //debug($save_data); exit();

                // associate the two licenses
                if (!ClassRegistry::init('LicensesLicense')->saveAll($save_data))
                {
                    throw new Exception ('Failed to save associated license data.');
                }

                // set success message
                $this->Session->setFlash('Assocatiated license was added.');
                $this->redirect('/accounts/accounts/home');
            }
            else
            {
                // what is the custom filter needed to search on?
                $custom_filter = false;
                switch ($license['License']['foreign_obj'])
                {
                    case 'Firm' :
                        $custom_filter = 'associate_to_account_lic';
                        break;

                    case 'Account' :
                        $custom_filter = 'associate_to_firm_lic';
                        break;
                }

                // fail if no custom filter
                if (!$custom_filter)
                {
                    throw new Exception ('No license filter defined.');
                }

                // redirect to the Searchable plugin
                $this->redirect(
                    array(
                        'plugin' => 'searchable',
                        'controller' => 'searchable',
                        'action' => 'locator',
                        'fp' => 'Licenses',
                        'fo' => 'License',
                        'li' => $license_id,
                        'cf' => $custom_filter,
                        'return' => base64_encode($this->here)
                    ),
                    null,
                    false,
                    'skip'
                );
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash(__($e->getMessage()));
            $this->redirect('/accounts/accounts/home');
        }
    }

    /**
     * Delete an association between two types of licenses.
     *
     * @param int $parent_id Parent license ID
     * @param int $child_id  Child license ID
     *
     * @return void
     * @access public
     */
    public function remove_associate_license($parent_id = null, $child_id = null)
    {
        try
        {
            // do we have two ids?
            if (!$parent_id || !$child_id)
            {
                throw new Exception ('Invalid associated license IDs.');
            }

            // attempt to delete the record
            ClassRegistry::init('LicensesLicense')->deleteAll(
                array(
                    'LicensesLicense.parent_id' => $parent_id,
                    'LicensesLicense.child_id' => $child_id
                )
            );

            // set success message
            $this->Session->setFlash('Associate license was removed.');
        }
        catch (Exception $e)
        {
            // set fail message
            $this->Session->setFlash($e->getMessage());
        }

        // redirect
        $this->redirect('/accounts/accounts/home');
    }

    /**
     * A page to display license expiration information.
     *
     * Includes a description of expiration dates in
     * general, the current expire date and reason as
     * well as all other expireation dates.
     *
     * @param int $id license ID
     *
     * @return void
     * @access public
     */
    public function expire_reason($id = null)
    {
        // set the license data for the view
        $this->set('license', $this->License->getLicenseById($id));

        // get the user information
        $user = $this->Auth->user();

        // set the admin flag for the view
        $is_admin = false;
        if ($user['Group']['admin'])
        {
            $is_admin = true;
        }

        $this->set('is_admin', $is_admin);

        // set the expire date definitions
        $definitions = array(
            'application' => 'This is the date your license application will expire.',
            'course' => 'This is the date your course expires.',
            'reciprocal' => 'This is the date your reciprocal course expires.',
            'interim' => 'This is the date your interim license will expire.'
        );

        $this->set('definitions', $definitions);

        // set the expire dates for the view
        $this->set('expire_dates', $this->License->getLicenseExpireDates($id));
    }
}
