<?php
/**
 * Applications Controller
 *
 * @package Licenses.Controller
 * @author  Iowa Interactive, LLC.
 */
class ApplicationsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Applications';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Licenses.License',
        'Licenses.Application',
        'Licenses.Question',
        'Licenses.QuestionAnswer',
        'Licenses.ScreeningQuestion',
        'Licenses.ScreeningAnswer',
        'Licenses.ThirdPartyTest'
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * index method
     *
     * Paginated list of license applications
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
                'fo'         => 'Application',
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
        if (!$id)
        {
            throw new Exception('Missing application id.');
        }

        // check for ownership or managership
        $this->checkOwnerOrManager('Licenses.Application', $id);

        $app_open = $this->Application->isOpen($id);

        // get current submission
        $current_submission = $this->Application->getCurrentSubmission($id);
        // set missing_serial_data variable
        $missing_serial_data = empty($current_submission['application_data']);

        $application_view_data = array();

        // if submission data is missing or the application is open, use live application data, else unserialize submission data
        if ($missing_serial_data || $app_open)
        {
            // get the application data
            if (!$application_view_data = $this->Application->getApplicationViewData($id))
            {
                $this->Session->setFlash(__('Invalid application data.'));
                $this->redirect($this->referer());
            }
        }
        else
        {
            $application_view_data = unserialize($current_submission['application_data']);
        }

        // did we get valid application view data?
        if (empty($application_view_data))
        {
            $this->Session->setFlash(__('Invalid application data.'));
            $this->redirect($this->referer());
        }

        // define the foreign obj controller and view variables
        $this->ForeignObject->init(
            $this,
            $application_view_data['License']['foreign_plugin'],
            $application_view_data['License']['foreign_obj'],
            $application_view_data['License']['foreign_key']
        );

        // get the sections for this page (plugin/controller/action)
        $sections = ClassRegistry::init('DynamicSection')->find(
            'all',
            array(
                'conditions' => array('DynamicSection.section_key' => sprintf('application/view.%s', $application_view_data['License']['LicenseType']['abbr'])),
                'order' => array('DynamicSection.order')
            )
        );

        // Find the ARO alias
        $aro_alias = $this->Acl->Aro->field(
            'alias',
            array(
                'Aro.model' => 'Account',
                'Aro.foreign_key' => $this->Auth->user('id'),
            )
        );

        // don't display additional sections for license info data block
        Configure::write('App.DataBlock.License.additional_sections', array());

        // display the reopen button if the current application is the most recent application
        if (!$app_open && $id == $this->License->getCurrentApplicationId($application_view_data['License']['id']))
        {
            Configure::write('App.DataBlock.Application.actions.reopen.display', true);
        }

        // if the application is incomplete or undifined display the delete button
        if (in_array($application_view_data['ApplicationStatus']['label'], array('Incomplete', 'Undefined')))
        {
            Configure::write('App.DataBlock.Application.buttons.delete.display', true);
        }

        // if the logged in user is an admin, show the bypass validation slidee
        if ($this->Auth->user('Group.admin') && $app_open)
        {
            Configure::write('App.DataBlock.Application.additional_sections.bypass_validation.display', true);
        }

        // set view variables
        $this->set(
            array(
                // sections
                'sections' => $sections,
                // section nav
                'fo_link' => array(
                    'plugin' => Inflector::underscore(lcfirst($this->foreign_plugin)),
                    'controller' => $this->foreign_controller,
                    'action' => 'view',
                    $application_view_data['License'][$this->foreign_obj]['id']
                ),
                'header' => $application_view_data['License']['LicenseType']['label'],
                'sub_header' => (isset($application_view_data['License']['license_number']) ? $application_view_data['License']['license_number'] : '<em>Not Submitted</em>'),
                'label' => $application_view_data['License']['label'],
                'note_count' => $this->License->Note->noteCount('License', $application_view_data['License']['id']),
                'license_id' => $application_view_data['License']['id'],
                // app open
                'app_open' => $app_open,
                // missing serial data
                'missing_serial_data' => $missing_serial_data,
                // application view data
                'application_view_data' => $application_view_data,
                // set vars to show/hide action bar buttons
                'show_continue_button' => $application_view_data['ApplicationStatus']['label'] == 'Incomplete',
                'show_approve_button' => $application_view_data['ApplicationStatus']['label'] == 'Pending',
                'allow_approve_button' => $this->Acl->check($aro_alias, 'controllers/Licenses/Applications/approve'),
                // data for open application
                'open_application' => false,
                // set bypass variables
                'bypass_validation' => $application_view_data['Application']['bypass_validation'],
                'bypass_validation_id' => $application_view_data['Application']['id']
            )
        );
    }

    /**
     * repoen method
     *
     * @param int $id expecting Application ID
     *
     * @return void
     * @access public
     */
    public function reopen($id = null)
    {
        try {
            // pull the app record
            if (!$application = $this->Application->findById($id))
            {
                throw new Exception('Could not be found.');
            }

            // check for any open applications for this license
            if ($this->License->getOpenApplication($application['Application']['license_id']))
            {
                throw new Exception(__('An open application already exists.'));
            }

            // ensure the app is already closed
            if ($application['Application']['open'] != 0)
            {
                // if not redirect back to license page w/ error message
                throw new Exception('Not in a closed state.');
            }

            // update record to be open
            $data['Application']['id'] = $application['Application']['id'];

            // update status of current submission record (not always accurately saved at this point)
            $data['ApplicationSubmission'] = array(
                array(
                    'id' => $application['Application']['application_submission_id'],
                    'application_status_id' => $application['Application']['application_status_id'],
                )
            );

            // can't update one submission record and create a new one at the same time, so
            // attempt to save the update first
            if (! $this->Application->saveAll($data, array('deep' => true)))
            {
                throw new Exception('Failed to update.');
            }

            // update record to be open
            $data = array();
            $data['Application']['id'] = $application['Application']['id'];
            $data['Application']['application_status_id'] = $this->Application->ApplicationStatus->field('id', array('label' => 'Incomplete'));
            $data['Application']['open'] = 1;

            // create a new submission record
            $data['ApplicationSubmission'] = array(
                array(
                    'prev_app_status_id' => $application['Application']['application_status_id'],
                    'application_status_id' => $this->Application->ApplicationStatus->field('id', array('label' => 'Incomplete')),
                    'created' => date('Y-m-d H:i:s')
                )
            );

            // attempt to update the application
            if (! $this->Application->saveAll($data, array('deep' => true)))
            {
                throw new Exception('Failed to update.');
            }

            // set flash
            $this->Session->setFlash('The application is now reopened.');

            // redirect to the new open application, ignoring the return if present.
            $this->redirect(
                array(
                    'plugin' => 'licenses',
                    'controller' => 'applications',
                    'action' => 'view',
                    $application['Application']['id']
                ),
                null,
                true,
                'forward'
            );
        }
        catch (Exception $e)
        {
            // set flash
            $this->Session->setFlash(sprintf('Application could not be reopened - (%s).', $e->getMessage()));
        }

        // go back to where we came from
        $this->redirect('/accounts/accounts/home');
    }

    /**
     * questions method
     *
     * @param int $id expecting Application ID
     *
     * @return void
     * @access public
     */
    public function questions($id)
    {
        try
        {
            // get the application data
            $application = $this->Application->find(
                'first',
                array(
                    'conditions' => array(
                        'Application.id' => $id
                    )
                )
            );

            // did we find an application
            if (!$application)
            {
                throw new exception('Appliation could not be found.');
            }

            // is the application open
            if (!$application['Application']['open'])
            {
                throw new exception('Appliation is not open for edits.');
            }

            // process the form submit
            if ($this->request->is('post') || $this->request->is('put'))
            {
                // loop the answer and unset those that are left blank
                $this->request->data['Application']['Question'] = array_filter(
                    array_map('trim', $this->request->data['Application']['Question'])
                );

                // save the answers
                $msg = __('Question answers could not be saved. Try again?');
                if ($this->Application->saveQuestionAnswers($id, $this->request->data))
                {
                     $msg = __('Question answers were saved.');
                }

                $this->Session->setFlash($msg);
                $this->redirect();
            }

            // contain
            $contain = array(
                'License' => array(
                    'LicenseType' => array(
                        'Question' => array(
                            'QuestionAnswer' => array(
                                'conditions' => array(
                                    'QuestionAnswer.application_id' => $id
                                )
                            )
                        )
                    )
                )
            );

            if (! $application = $this->Application->details($id, $contain))
            {
                // fail
                $this->Session->setFlash(__('Questions could not be displayed.'));
                $this->redirect($this->referer());
            }

            $this->set('application', $application);
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect();
        }
    }

    /**
     * answer method
     *
     * @param int $id expecting QuestionAnswer ID
     *
     * @return void
     * @access public
     */
    public function answer($id)
    {
        if (! $answer = $this->QuestionAnswer->getAnswer($id))
        {
            // fail
            $this->Session->setFlash(__('Answer could not be displayed.'));
            $this->redirect($this->referer());
        }

        $this->set('answer', $answer);

        // do not display the layout
        $this->layout = null;
    }

    /**
     * screening_questions method
     *
     * @param int $id expecting Application ID
     *
     * @return void
     * @access public
     */
    public function screening_questions($id)
    {
        try
        {
            // get the application data
            $application = $this->Application->find(
                'first',
                array(
                    'conditions' => array(
                        'Application.id' => $id
                    )
                )
            );

            // did we find an application
            if (!$application)
            {
                throw new exception('Appliation could not be found.');
            }

            // is the application open
            if (!$application['Application']['open'])
            {
                throw new exception('Appliation is not open for edits.');
            }

            // process the form submit
            if ($this->request->is('post') || $this->request->is('put'))
            {
                $saved = false;
                // save the answers
                try
                {
                    $saved = $this->Application->saveScreeningAnswers($id, $this->request->data);
                }
                catch (Exception $e)
                {
                    // failed
                    $this->Session->setFlash($e->getMessage());
                }

                if ($saved)
                {
                    // passed - redirect back to where we came from
                    $this->Session->setFlash(__('Screening question answers were saved.'));
                    $this->redirect();
                }
            }

            // contain
            $contain = array(
                'License' => array(
                    'LicenseType' => array(
                        'ScreeningQuestion' => array(
                            'ScreeningAnswer' => array(
                                'conditions' => array(
                                    'ScreeningAnswer.application_id' => $id
                                )
                            )
                        )
                    )
                )
            );

            if (! $application = $this->Application->details($id, $contain))
            {
                // fail
                $this->Session->setFlash(__('Screening questions could not be displayed.'));
                $this->redirect($this->referer());
            }

            $this->set('application', $application);
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect();
        }
    }

    /**
     * edit application expire date method
     *
     * This method updates the expire date and sets the
     * manually edited record flag for applictions.
     *
     * @param int $id expecting application ID
     *
     * @return void
     * @access public
     */
    public function edit_expire_date ($id)
    {
        try
        {
            // process the form submit
            if ($this->request->is('post') && !empty($this->request->data))
            {
                // get the application data for saving
                if (!$application = $this->Application->findById($id))
                {
                    throw new Exception ('Invalid application data.');
                }

                // format the new expire date
                $new_expire_date = GenLib::dateFormat(implode('-', $this->request->data['Application']['expire_date']), 'Y-m-d H:i:s');

                // did the date change?
                if (strtotime($application['Application']['expire_date']) == strtotime($new_expire_date))
                {
                    $this->Session->setFlash('The date you entered was the same as the application\'s previous expire date.');
                }
                else
                {
                    // add some data to the request data
                    $this->request->data['Application']['id'] = $application['Application']['id'];
                    $this->request->data['License']['id'] = $application['Application']['license_id'];
                    $this->request->data['License']['Note'][0]['account_id'] = CakeSession::read("Auth.User.id");
                    $this->request->data['License']['Note'][0]['foreign_plugin'] = 'Licenses';
                    $this->request->data['License']['Note'][0]['foreign_obj'] = 'License';
                    $this->request->data['License']['Note'][0]['foreign_key'] = $application['Application']['license_id'];

                    // update the application record
                    if (!$this->Application->saveAll($this->request->data, array('deep' => true)))
                    {
                        throw new Exception ('Failed to save application data.');
                    }

                    // add system note to license of the application that was updated
                    $this->Application->License->Note->sysNote(
                        CakeSession::read("Auth.User.id"),
                        'Licenses',
                        'License',
                        $application['Application']['license_id'],
                        sprintf(
                            'The application expire date for this license was manually updated from %s to %s by %s on %s.',
                            GenLib::dateFormat($application['Application']['expire_date']),
                            GenLib::dateFormat($new_expire_date),
                            CakeSession::read("Auth.User.label"),
                            date('Y-m-d')
                        )
                    );

                    // dispatch the editExpireDate event for listeners
                    $this->Application->License->dispatch('Model-Application-editExpireDate', array('license_id' => $application['Application']['license_id']));

                    // set success message
                    $this->Session->setFlash('The application expire date was updated.');

                    // redirect to license view if not modal page
                    $this->redirect(array('controller' => 'licenses', 'action' => 'view', $application['Application']['license_id']));
                }
            }
        }
        catch (Exception $e)
        {
            // set fail message=
            $this->Session->setFlash(sprintf('Failed to update the application expire date. %s', $e->getMessage()));
        }

        // get the application data for viewing
        if (!$application = $this->Application->findById($id))
        {
            throw new Exception ('Invalid application data.');
        }

        $this->set('application', $application);
    }

    /**
     * approve method
     *
     * @param int $id license ID expected.
     *
     * @return void
     * @access public
     */
    public function approve ($id = null)
    {
        try
        {
            // get the application record
            $application = $this->Application->find(
                'first',
                array(
                    'contain' => array(
                        'License' => array(
                            'LicenseType'
                        )
                    ),
                    'conditions' => array('Application.id' => $id)
                )
            );

            if (!$application)
            {
                throw new Exception ('Failed to find application data.');
            }

            // set the application materials recieved date
            $this->set('materials_received_date', $application['Application']['materials_received']);

            // set a view vars to be used for the setting the interim date
            if (!isset($application['Application']['expire_date']) || $application['Application']['expire_date'] == '0000-00-00 00:00:00')
            {
                $this->set('interim_max_year', date('Y') + 1);
            }
            else
            {
                $this->set('interim_max_year', date('Y', strtotime($application['Application']['expire_date'])) + 1);
            }

            if (!isset($application['Application']['interim_expire_date']))
            {
                $this->set('interim_date', null);
            }
            else
            {
                $this->set('interim_date', $application['Application']['interim_expire_date']);
            }

            // process the form submit
            if ($this->request->is('post'))
            {
                // add the application and license ids
                $data['Application']['id'] = $id;
                $data['Application']['materials_received'] = GenLib::dateFormat($this->request->data['Application']['materials_received'], 'Y-m-d');

                // do not allow partial interim date formats
                if(!GenLib::isData($this->request->data, 'Application.interim_expire_date', array('month'))
                   && !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('day'))
                   && !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('year')))
                {
                    // do nothing
                }
                elseif ((GenLib::isData($this->request->data, 'Application.interim_expire_date', array('month'))
                    && !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('day'))
                    || !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('year')))
                    || (GenLib::isData($this->request->data, 'Application.interim_expire_date', array('day'))
                    && !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('month'))
                    || !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('year')))
                    || (GenLib::isData($this->request->data, 'Application.interim_expire_date', array('year'))
                    && !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('month'))
                    || !GenLib::isData($this->request->data, 'Application.interim_expire_date', array('day')))
                )
                {
                    throw new Exception ('Interim date format is not valid.');
                }

                // if an interim date is set, it cannot be in the past or after the application expiration date
                if (GenLib::isData($this->request->data, 'Application.interim_expire_date', array('month'))
                    && GenLib::isData($this->request->data, 'Application.interim_expire_date', array('day'))
                    && GenLib::isData($this->request->data, 'Application.interim_expire_date', array('year'))
                )
                {
                    if(GenLib::dateFormat($this->request->data['Application']['interim_expire_date'], 'Y-m-d') < date('Y-m-d'))
                    {
                         throw new Exception ('Interim date cannot be earlier than today.');
                    }
                    elseif(GenLib::isData($application, 'Application', array('expire_date')))
                    {
                        if(GenLib::dateFormat($this->request->data['Application']['interim_expire_date'], 'Y-m-d') >
                            GenLib::dateFormat($application['Application']['expire_date'], 'Y-m-d'))
                        {
                            throw new Exception ('Interim date cannot be after the application expiration date.');
                        }
                    }
                    else
                    {
                        // save the provided interim date
                        $data['Application']['interim_expire_date'] = GenLib::dateFormat($this->request->data['Application']['interim_expire_date'], 'Y-m-d');
                    }
                }
                else
                {
                    // set the interim date to null
                    $data['Application']['interim_expire_date'] = null;
                }

                // these are the conditions that require a note !!!!!
                // add the note or don't add the note - the ['Note'] key in the data array requires the note as validation
                if (GenLib::isData($this->request->data, 'License.Note.0', array('note')))
                {
                    $data['Application']['License']['id'] = $application['License']['id'];
                    $data['Application']['License']['Note'][0]['foreign_plugin'] = 'Licenses';
                    $data['Application']['License']['Note'][0]['foreign_obj'] = 'License';
                    $data['Application']['License']['Note'][0]['foreign_key'] = $application['License']['id'];
                    $data['Application']['License']['Note'][0]['note'] = $this->request->data['License']['Note'][0]['note'];
                    $data['Application']['License']['Note'][0]['account_id'] = CakeSession::read("Auth.User.id");
                }

                // attemp to update the application data before approval
                if ($this->Application->saveAll($data, array('deep' => true)))
                {
                    // attempt to approve the application
                    if (!$this->Application->saveApproval($id))
                    {
                        throw new Exception ('Failed to approve License.');
                    }

                    $this->Session->setFlash(
                        sprintf(
                            __('The %s application for %s has been approved.'),
                            $application['License']['LicenseType']['abbr'],
                            $application['License']['label']
                        )
                    );

                    // return to the application
                    $this->redirect('/licenses/applications/view/'.$id);
                }
            }
        }
        catch (Exception $e)
        {
            // throw the exception
            $this->Session->setFlash($e->getMessage());
        }
    }

    /**
     * bypass method
     *
     * Enables/disables application validation bypass flag.
     *
     * @param int $id application ID expected.
     *
     * @return void
     * @access public
     */
    public function bypass ($id = null)
    {
        try
        {
            // get the application
            if (!$application = $this->Application->findById($id))
            {
                throw new Exception ('invalid application');
            }

            // toggle the bypass
            $bypass = 1;
            $msg = 'Application validation bypass enabled.';

            if ($application['Application']['bypass_validation'])
            {
                $bypass = 0;
                $msg = 'Application validation bypass disabled.';
            }

            $save_data = array(
                'Application' => array(
                    'id' => $id,
                    'bypass_validation' => $bypass
                )
            );

            // attempt to save the data
            if (!$this->Application->save($save_data))
            {
                //debug($this->Application->validationErrors);
                throw new Exception ('update failed');
            }

            // return
            $this->Session->setFlash($msg);
            $this->redirect(array('action' => 'view', $id));
        }
        catch (Exception $e)
        {
            throw new Exception (
                sprintf(
                    'Failed to set application validation bypass. (%s)',
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * submit method
     *
     * Submits an application. Adds fees to the
     * shopping cart, updates the license/app
     * statuses.
     *
     * @param int $id application ID expected.
     *
     * @return void
     * @access public
     */
    public function submit ($id = null)
    {
        try
        {
            // get the application record
            $application = $this->Application->details($id);

            // get the license record
            $license = $this->License->getApplication($application['Application']['license_id']);

            // bypass validation
            if (!$application['Application']['bypass_validation'])
            {
                // process the perjury data
                if ($this->request->is('post')
                    && isset($this->request->data['Application']['perjury_name'])
                    && isset($this->request->data['Application']['perjury_date'])
                )
                {
                    $data = array();
                    $data['Application']['id'] = $application['Application']['id'];
                    $data['Application']['perjury_name'] = $this->request->data['Application']['perjury_name'];
                    $data['Application']['perjury_date'] = $this->request->data['Application']['perjury_date'];

                    if (!$this->Application->edit($data))
                    {
                        $this->Session->setFlash(__('Perjury section is required.'));
                        $this->redirect(sprintf('licenses/view/%s', $id));
                    }
                }

                // determine if the application can be submitted (has all required data)
                try
                {
                    $this->Application->canSubmit($id);
                }
                catch (Exception $e)
                {
                    throw new Exception(sprintf(__('Unable to submit application: %s'), $e->getMessage()));
                }
            }

            // flag for additional review
            // if any screening question has an incorrect answer
            if(!$this->Application->ScreeningAnswer->areAnswersCorrect($license['OpenApplication']['id']))
            {
                // set flag
                $this->License->OpenApplication->flagAdditionalReview($license['OpenApplication']['id']);
            }

            // create the fee key
            $fee_key = null;
            foreach ($license['Application'] as $app)
            {
                if ($app['id'] == $license['OpenApplication']['id'])
                {
                    $fee_key = sprintf(
                        '%s_%s',
                        strtolower($license['LicenseType']['abbr']),
                        strtolower($app['ApplicationType']['label'])
                    );

                    // is this a resubmit?
                    if (count($app['ApplicationSubmission']) !== 1)
                    {
                        $fee_key .= "_resubmit";
                    }
                }
            }

            // get the license fee for this license type
            $fee = $this->License->getFeeByKey($fee_key);

            if (!$fee)
            {
                throw new Exception ('Failed to find fee.');
            }
            else
            {
                // add fees to the shopping cart
                try
                {
                    $this->License->addItem(
                        $this->Auth->user('id'),
                        'Licenses',
                        'Application',
                        $license['OpenApplication']['id'],
                        $fee,
                        $fee['Fee']['label'],
                        $license['License']['label']
                    );
                }
                catch (Exception $e)
                {
                    throw $e;
                }

                // get the modifiers (later release)

                // loop the modifiers and determine which should be applied (later release)
                    // add modifier fee to shopping cart
            }

            //check for a passed in license id to redirect to
            if (isset($this->request->params['named']['li']))
            {
                $license_id = $this->request->params['named']['li'];
//                $application_id = $this->request->params['named']['li'];
                $this->redirect(
                    array('plugin' => 'licenses',
                        'controller' => 'licenses',
                        'action' => 'view',
                        $license_id,
                    ),
                    null,
                    false,
                    'skip'
                );
            }
            //else redirect normally.
            else
            {
                // redirect to the shopping cart
                $this->Session->setFlash(__('Item added to shopping cart.'));

                $this->redirect(
                    array(
                        'plugin'     => 'payments',
                        'controller' => 'shopping_carts',
                        'action'     => 'view',
                    ),
                    null,
                    false,
                    'forward'
                );
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect($this->referer());
        }
    }

    /**
     * cancel method
     *
     * Cancel open application.
     *
     * @param int $id application ID expected.
     *
     * @return void
     * @access public
     */
    public function cancel ($id = null)
    {
        // success msg
        $msg = 'The Application has been removed.';
        $redirect = '/accounts/accounts/home';

        try
        {
            // get application record
            $application = $this->Application->find(
                'first',
                array(
                    'contain' => array(
                        'ApplicationSubmission' => array('order' => array('ApplicationSubmission.id' => 'DESC')),
                        'License' => array('Application')
                    ),
                    'conditions' => array('Application.id' => $id)
                )
            );

            if (!$application)
            {
                throw new Exception ('Missing application data.');
            }

            // double check the application is open
            if (!$application['Application']['open'])
            {
                throw new Exception ('Application is closed.');
            }

            // get the previous application status prior to deleting anything
            if (GenLib::isData($application, 'ApplicationSubmission.0', array('prev_app_status_id')))
            {
                $prev_app_status_id = $application['ApplicationSubmission'][0]['prev_app_status_id'];
            }
            else
            {
                // set status to undefined
                $prev_app_status_id = 5;
            }

            // only one submission, one application - delete license
            if (count($application['License']['Application']) < 2 && count($application['ApplicationSubmission']) < 2)
            {
                // delete license
                try
                {
                    $this->Application->License->delete($application['License']['id'], false);
                }
                catch (Exception $e)
                {
                    //debug($e->getMessage()); exit();
                    throw new Exception ('Failed to delete license data for application.');
                }
            }

            // only one submission - delete application
            elseif (count($application['ApplicationSubmission']) < 2)
            {
                // delete application
                if (!$this->Application->delete($id, false))
                {
                    //debug($e->getMessage()); exit();
                    throw new Exception ('Failed to delete application data.');
                }
            }

            // delete submission
            else
            {
                // more than one submission record - delete the latest submission
                if (!$this->Application->ApplicationSubmission->delete($application['ApplicationSubmission'][0]['id']))
                {
                    throw new Exception ('Failed to delete application submission data.');
                }

                // set application status to closed and reset the application status to the previous value
                $data = array('Application' => array(
                            'id' => $id,
                            'open' => 0,
                            'application_status_id' => $prev_app_status_id
                        )
                );

                // update the submission record with the previous value as well
                $data['ApplicationSubmission'][0]['application_status_id'] = $prev_app_status_id;

                if (!$this->Application->save($data))
                {
                    throw new Exception ('Failed to update application.');
                }

                // set msg and redirect back to application
                $redirect = sprintf('/licenses/applications/view/%s', $id);
            }
        }
        catch (Exception $e)
        {
            // set error msg and redirect back to application
            $msg = sprintf('Cancel Failed - %s', $e->getMessage());
        }

        // redirect
        $this->Session->setFlash($msg);
        $this->redirect($redirect);
    }

    /**
     * pending method
     *
     * Redirects to applications searchable with pending filter
     *
     * @return void
     * @access public
     */

    public function pending ()
    {
        $pending_status = $this->Application->ApplicationStatus->findByLabel('Pending');

        $this->redirect(
            array(
                'plugin' => 'searchable',
                'controller' => 'searchable',
                'action' => 'index',
                'fp' => 'licenses',
                'fo' => 'application',
                'application_status_id' => $pending_status['ApplicationStatus']['id']
            )
        );
    }

    /**
     * remove interim expire date
     *
     * @param int $id Application ID
     *
     * @return void
     * @access public
     */

    public function remove_interim ($id = null)
    {
        // default message (fail)
        $msg = 'Failed to remove application interim expiration date.';

        // attempt to remove the interim expire date
        if ($this->Application->removeInterim($id))
        {
            // set success msg
            $msg = 'Application interim expiration date was removed.';
        }

        // set flash message
        $this->Session->setFlash($msg);

        // redirect back to where we came from else user's home
        $this->redirect('/accounts/accounts/home');
    }
}
