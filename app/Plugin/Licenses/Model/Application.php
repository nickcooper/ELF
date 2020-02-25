<?php
/**
 * Application model
 *
 * Extends the AppModel. Responsible for managing license application data.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class Application extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Application';

    /**
     * actsAs
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Payments.Payable' => array(
            'contain' => array(
                'License'
            ),
            'map' => array(
                'label' => 'License.label'
            )
        ),
        'Searchable.Searchable',
    );

    /**
     * belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'license_id',
            'fields' => array('License.*'),
        ),
        'LicenseType' => array(
            'className' => 'Licenses.LicenseType',
            'foreignKey' => false,
            'conditions' => array(
                'LicenseType.id = (SELECT license_type_id FROM licenses WHERE licenses.id = Application.license_id LIMIT 1)'
            )
        ),
        'ApplicationType' => array(
            'className' => 'Licenses.ApplicationType',
            'foreignKey' => 'application_type_id',
        ),
        'CurrentSubmission' => array(
            'className' => 'Licenses.ApplicationSubmission',
            'foreignKey' => 'application_submission_id',
        ),
        'ApplicationStatus' => array(
            'className' => 'Licenses.ApplicationStatus',
            'foreignKey' => 'application_status_id',
        ),
    );

    /**
     * hasOne
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'OpenSubmission' => array(
            'className' => 'Licenses.ApplicationSubmission',
            'foreignKey' => 'application_id',
            'conditions' => array('OpenSubmission.application_data' => null),
            // application_data should always be null until approved or denied
        ),
        'LicenseGap' => array(
            'className' => 'Licenses.LicenseGap',
            'foreignKey' => 'application_id',
        ),
    );


    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'ApplicationSubmission' => array(
            'className' => 'Licenses.ApplicationSubmission',
            'foreignKey' => 'application_id',
            'dependent' => true
        ),
        'QuestionAnswer' => array(
            'className' => 'Licenses.QuestionAnswer',
            'foreignKey' => 'application_id',
            'dependent' => true
        ),
        'ScreeningAnswer' => array(
            'className' => 'Licenses.ScreeningAnswer',
            'foreignKey' => 'application_id',
            'dependent' => true
        ),
        'Reciprocal' => array(
            'className' => 'Licenses.Reciprocal',
            'foreignKey' => 'application_id',
            'dependent' => true
        ),
        'ExamScore' => array(
            'className' => 'ExamScore',
            'foreignKey' => 'foreign_key',
            'conditions' => array('ExamScore.foreign_obj' => 'Application'),
            'dependent' => true
        ),
        'PaymentItem' => array(
            'className' => 'Payments.PaymentItem',
            'foreignKey' => 'foreign_key',
            'conditions' => array('PaymentItem.foreign_obj' => 'Application')
        ),
        'ThirdPartyTest' => array(
            'className' => 'Licenses.ThirdPartyTest',
            'foreignKey' => 'foreign_key',
            'conditions' => array('ThirdPartyTest.foreign_obj' => 'Application'),
            'dependent' => true
        ),
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'perjury_name' => array(
            'name_required' => array(
                'rule' => array('notEmpty')
            ),
        ),
        'perjury_date' => array(
            'date_format' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Enter a valid date in YYYY-MM-DD format.'
            )
        ),
        'materials_received' => array(
            'date_format' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Enter a valid date in YYYY-MM-DD format.'
            ),
            'not_future_date' => array(
                'rule' => array('notFutureDate'),
                'message' => 'Materials received date can not be in the future.'
            )
        )
    );

    /**
     * beforeDelete method
     *
     * Callback for before delete processing.
     *
     * @param array $payment_item data array for paid item
     *
     * @return true
     * @access public
     */
    public function beforeDelete($cascade = true)
    {
        // remove child records
        $this->ApplicationSubmission->deleteAll(array('ApplicationSubmission.application_id' => $this->id));
        //$this->QuestionAnswer->deleteAll(array('QuestionAnswer.application_id' => $this->id));
        $this->ScreeningAnswer->deleteAll(array('ScreeningAnswer.application_id' => $this->id));
        /*$this->Reciprocal->deleteAll(array('Reciprocal.application_id' => $this->id));
        $this->ExamScore->deleteAll(array('ExamScore.foreign_obj' => 'Application', 'ExamScore.foreign_key' => $this->id));
        $this->PaymentItem->deleteAll(array('PaymentItem.foreign_obj' => 'Application', 'PaymentItem.foreign_key' => $this->id));
        $this->LicenseGap->deleteAll(array('LicenseGap.application_id' => $this->id));*/

        return parent::beforeDelete($cascade);
    }

    /**
     * afterPayment method
     *
     * Callback for after payment processing.
     *
     * @param array $payment_item data array for paid item
     *
     * @return true
     * @access public
     */
    public function afterPayment($payment_item = null)
    {
        try
        {
            // get the application record
            $application = $this->find(
                'first',
                array(
                    'contain' => array(
                        'License',
                        'OpenSubmission'
                    ),
                    'conditions' => array(
                        'Application.id' => $payment_item['foreign_key']
                    )
                )
            );

            if (!$application)
            {
                throw new Exception('Failed to get Application data.');
            }

            // save data
            $data = array();

            $paid_date = date('Y-m-d H:i:s');

            // set the application values
            $data['Application']['id'] = $application['Application']['id'];
            $data['Application']['submit_paid_date'] = $paid_date;
            $data['Application']['application_status_id'] = $this->ApplicationStatus->field('id', array('label' => 'Pending')); // set application status to pending

            $data['OpenSubmission']['id'] = $application['OpenSubmission']['id'];
            $data['OpenSubmission']['submit_paid_date'] = $paid_date;

            // set the license record values
            $data['License']['id'] = $application['Application']['license_id'];
            $data['License']['pending'] = 1;

            // save the data
            if (!$this->saveAll($data))
            {
                throw new Exception('Failed to update Application data.');
            }

            // record the billable item when the license is approved, exclude resubmissions
            if (count($this->getSubmissions($application['Application']['id'])) < 2)
            {
                $BillingItem = ClassRegistry::init('Payments.BillingItem');
                $BillingItem->create();

                $payment_data = $this->PaymentItem->getPaymentDataForApplication($application['Application']['id']);

                $billing_item = array(
                    'BillingItem' => array(
                        'foreign_plugin' => $application['License']['foreign_plugin'],
                        'foreign_obj' => $application['License']['foreign_obj'],
                        'foreign_key' => $application['License']['foreign_key'],
                        'date' => $paid_date,
                        'label' => $payment_data[0]['PaymentItem']['label'],
                        'owner' => $payment_data[0]['PaymentItem']['owner'],
                        'fee' => $payment_data[0]['PaymentItem']['fee'],
                        'data'=> serialize($application)
                    )
                );

                $BillingItem->save($billing_item);
            }
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf('After payment method failed. %s', $e->getMessage()));
        }
    }

    /**
     * canSubmit method
     *
     * @param array $license expecting full license data array
     *
     * @return void
     * @access public
     */
    public function canSubmit ($id)
    {
        if (empty($id))
        {
            throw new Exception('Invalid id.');
        }

        $application = $this->getApplicationViewData($id);

        // get a list of required application sections for this license type
        $DynamicSections = ClassRegistry::init('Licenses.DynamicSections');
        $required_sections = $DynamicSections->find(
            'list',
            array(
                'fields' => array(
                    'DynamicSections.config_key'
                ),
                'conditions' => array(
                    'DynamicSections.required',
                    'DynamicSections.section_key' => sprintf('application/view.%s', strtoupper($application['License']['LicenseType']['abbr']))
                ),
                'order' => array('DynamicSections.order' => 'ASC')
            )
        );

        // loop the required sections and make sure we have data
        foreach ($required_sections as $section)
        {
            // get the section configuration
            if(!$config = Configure::read(sprintf('App.DataBlock.%s', $section)))
            {
                throw new Exception ('Failed to get application section configuration.');
            }

            // loop the data maps and verify we have data
            $has_data = false;
            foreach ($config['data_maps']['Application'] as $data_key => $data_map)
            {
                // only check the primary section data
                if ($data_key != $section)
                {
                    continue; // skip to the next record
                }

                $data_map = preg_replace('/\{fp\}/', $application['License']['foreign_plugin'], $data_map);
                $data_map = preg_replace('/\{fo\}/', $application['License']['foreign_obj'], $data_map);
                $data_map = preg_replace('/\{fk\}/', $application['License']['foreign_key'], $data_map);

                if (GenLib::isData($application, $data_map, array('id')) || GenLib::isData($application, $data_map.'.0', array('id')))
                {
                    $has_data = true;
                }
            }

            // check that we found data
            if (!$has_data)
            {
                throw new Exception (sprintf('Missing %s data.', $config['label']));
            }
        }

        // verify associated licenses are valid
        switch ($application['License']['foreign_obj'])
        {
        case 'Account':
            // is the associated firm license valid? allowing incomplete and active
            foreach ($application['License']['ParentLicense'] as $parent_license)
            {
                $license_status = $parent_license['LicenseStatus']['status'];
                if (!in_array($license_status, array('Active', 'Expired', 'Incomplete')))
                {
                    throw new Exception(
                        sprintf(
                            __('Associated license is invalid. License status is %s.'),
                            strtolower($parent_license['LicenseStatus']['status'])
                        )
                    );
                }
            }
            break;

        case 'Firm':// get the active, expired, and incomplete status ids
            // check to see if at least one license is active or incomplete
            $one_valid = false;
            foreach ($application['License']['ChildLicense'] as $child_license)
            {
                $license_status = $child_license['LicenseStatus']['status'];
                if (in_array($license_status, array('Active', 'Incomplete', 'Expired')))
                {
                    $one_valid = true;
                }
            }

            // did we have at least one good one?
            if (! $one_valid)
            {
                throw new Exception(__('License requires at least one associated license to have active, expired or incomplete status.'));
            }
            break;
        }

        // validate screening questions were answered
        if (in_array('ScreeningAnswer', $required_sections))
        {
            foreach ($application['ScreeningAnswer'] as $answer)
            {
                if ($answer['answer'] == null)
                {
                    throw new Exception('Screening questions must be answered.');
                }
            }
        }

        // validate practical work percentages equal 100%
        if (in_array('PracticalWorkPercentage', $required_sections))
        {
            $percent_ttl = 0;

            foreach ($application['License']['Account']['PracticalWorkPercentage'] as $percentage)
            {
                $percent_ttl += (int) $percentage['percentage'];
            }

            if ($percent_ttl != 100)
            {
                throw new Exception('Practical work experience must equal 100%.');
            }
        }

        // set the trigger type
        $trigger = strtolower(Inflector::slug($application['ApplicationType']['label']));

        // verify education hours
        if ($application['License']['foreign_obj'] == 'Account')
        {
            $this->CourseRoster = ClassRegistry::init('ContinuingEducation.CourseRoster');
            if (!$this->CourseRoster->passCourseCredits($application['License']['id']))
            {
                throw new Exception('The minimum continuing education hours are not met.');
            }
        }

        return true;
    }

    /**
     * saveQuestionAnswers method
     *
     * @param int   $application_id expecting application record id
     * @param array $answers        expecting answer array
     *
     * @return bool
     * @access public
     */
    public function saveQuestionAnswers($application_id = null, $answers = array())
    {
        // get a list of previous answers
        $prev_answers = $this->QuestionAnswer->find(
            'list',
            array(
                'conditions' => array('QuestionAnswer.application_id' => $application_id),
                'fields' => array('question_id', 'id')
            )
        );

        // start db transaction
        $datasource = $this->getDataSource();
        $datasource->begin($this);

        // loop through the questions
        foreach ($answers['Application']['Question'] as $question_id => $answer)
        {
            // format the data
            $this->QuestionAnswer->create();

            if (isset($prev_answers[$question_id]))
            {
                $this->QuestionAnswer->set('id', $prev_answers[$question_id]);
            }

            $this->QuestionAnswer->set('application_id', $application_id);
            $this->QuestionAnswer->set('question_id', $question_id);
            $this->QuestionAnswer->set('answer', $answer);

            // save the answers
            if (!$this->QuestionAnswer->save())
            {
                // one save failed rollback the transaction and exit loop
                $datasource->rollback($this);
                return false;
            }
        }

        // all saves passed - commit the transaction
        $datasource->commit($this);
        return true;
    }

    /**
     * saveScreeningAnswers method
     *
     * @param int   $application_id expecting application record id
     * @param array $answers        expecting answer array
     *
     * @return bool
     * @access public
     */
    public function saveScreeningAnswers($application_id = null, $answers = array())
    {
        // get a list of previous answers
        $prev_answers = $this->ScreeningAnswer->find(
            'list',
            array(
                'conditions' => array('ScreeningAnswer.application_id' => $application_id),
                'fields' => array('screening_question_id', 'id'),
            )
        );

        // loop through the questions
        $data = array();
        foreach ($answers['Application']['ScreeningQuestion'] as $question_id => $answer)
        {
            $record = array();
            // format the data
            $this->ScreeningAnswer->create();

            if (isset($prev_answers[$question_id]))
            {
                $record['id'] = $prev_answers[$question_id];
            }

            $record['application_id'] = $application_id;
            $record['screening_question_id'] = $question_id;
            $record['answer'] = $answer['answer'];
            $record['comment'] = $answer['comment'];
            $data[] = $record;
        }

        $ScreeningQuestion = ClassRegistry::init('Licenses.ScreeningQuestion');
        foreach ($data as $key => $record)
        {
            $correct_answer = $ScreeningQuestion->field(
                'correct_answer',
                array('id' => $record['screening_question_id'])
            );

            if ($record['answer'] !== $correct_answer)
            {
                if (empty($record['comment']))
                {
                    throw new Exception(__('Some answers require explanation.'));
                }
            }
        }

        if (! $this->ScreeningAnswer->edit($data))
        {
            throw new Exception(__('Screening question answers could not be saved.'));
        }

        // passed
        return true;
    }

    /**
     * getSubmissions method
     *
     * @param int $id expecting application record id
     *
     * @return array
     * @access public
     */
    public function getSubmissions($id = null)
    {
        return $this->ApplicationSubmission->findAllByApplicationId($id);
    }

    /**
     * getLicenseTypeList method
     *
     * @return array returns a list of license types
     * @access public
     */
    public function getLicenseTypeList ()
    {
        return $this->LicenseType->getLicenseTypeList();
    }

    /**
     * getApplicationTypeList method
     *
     * @return array returns a list of application types
     * @access public
     */
    public function getApplicationTypeList ()
    {
        return $this->ApplicationType->getApplicationTypeList();
    }

    /**
     * getApplicationStatusList method
     *
     * @return array returns a list of application statuses
     * @access public
     */
    public function getApplicationStatusList ()
    {
        return $this->ApplicationStatus->getApplicationStatusList();
    }

    /**
     * pendingCount method
     *
     * @return int
     * @access public
     */
    public function pendingCount ()
    {
        return $this->find(
            'count',
            array(
                'contain' => array('ApplicationStatus'),
                'conditions' => array('ApplicationStatus.label' => 'Pending')
            )
        );
    }

    /**
     * saveApproval method
     *
     * @param int  $id            application record id
     * @param bool $after_approve to call afterApprove or not (initially for legacy data import)
     *
     * @return bool
     * @access public
     */
    public function saveApproval ($id = null, $after_approve = true)
    {
        // start transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try
        {
            // get the application record
            $application = $this->getApplicationViewData($id);

            if (empty($application))
            {
                throw new Exception('No Application found.');
            }
            //print "<pre>"; print_r($application); print "</pre>";

            // get the previous license application data
            if (!$was_previous = $previous_application = $this->getPreviousApplication($id))
            {
                // if there isn't a previous application to this one
                // set the previous application to the same one we're
                // trying to approve. In most, if not all, cases this
                // will be initial applications
                $previous_application = $application;
            }

            // calculate the approval dates
            // if the current application is the same one we're approving use
            // it's effective date instead of it's expire date, this fixes
            // issues with reopened application expire date calculations
            list($effective_date, $expire_date) = $this->License->calcApprovalDates(
                $application['License']['LicenseType'],
                ($was_previous ? $previous_application['Application']['expire_date'] : null),
                $application['Application']['materials_received']
            );

            // update license data
            $data = array();
            $data['License']['license_number'] = $this->License->generateLicenseNumber($application['License']['id']);
            $data['License']['id'] = $application['License']['id'];
            $data['License']['license_status_id'] = $this->License->LicenseStatus->getStatusId('active');
            $data['License']['pending'] = false; // reset pending flag
            $data['License']['application_id'] = $id;

            // if this is the first application, set the issued date
            if ($application['ApplicationType']['label'] == 'Initial')
            {
                $data['License']['issued_date'] = $effective_date;
            }

            // update current application data
            $data['Application']['id'] = $id;
            $data['Application']['effective_date'] = $effective_date;
            $data['Application']['expire_date'] = $expire_date;
            $data['Application']['processed_date'] = date('Y-m-d H:i:s');
            $data['Application']['open'] = 0;
            $data['Application']['application_status_id'] = $this->ApplicationStatus->field('id', array('label' => 'Approved'));

            // if the license approval is for a conversion and the issued date is empty, set the issued date to
            // the application processed date
            if ($application['ApplicationType']['label'] == 'Conversion')
            {
                if ($application['License']['issued_date'] == null || $application['License']['issued_date'] == "")
                {
                    $data['License']['issued_date'] = $data['processed_date'];
                }
            }

            // save the data
            $this->create();
            $this->saveAll($data, array('deep' => true));


            if (!$open_submission = $this->getOpenSubmission($id))
            {
                throw new Exception('Open Submission not found.');
            }

            $this->create();
            $this->id = $id;
            $this->saveField('application_submission_id', $open_submission['id']);

            $application = $this->getApplicationViewData($id);

            $data = array();
            $data['id'] = $open_submission['id'];
            $data['approved_date'] = date('Y-m-d H:i:s');
            $data['materials_received'] = $application['Application']['materials_received'];
            $data['application_data'] = serialize($application);

            // save the data
            $this->ApplicationSubmission->create();
            $this->ApplicationSubmission->save($data);

            // default output doc trigger
            $outputDocumentTrigger = strtolower(Inflector::slug($application['ApplicationType']['label']));

            // set the status of the license being converted to 'Converted'
            if ($application['ApplicationType']['label'] == 'Conversion')
            {
                if (!empty($application['Application']['converted_license_id']))
                {
                    $old_license['License']['id'] = $application['Application']['converted_license_id'];
                    $old_license['License']['license_status_id'] = $this->License->LicenseStatus->getStatusId('Converted');

                    $this->License->save($old_license);

                    // load converted license
                    $converted_license = $this->License->getLicenseById($old_license['License']['id']);

                    $lic_type = $this->License->LicenseType->getLicenseTypeById($converted_license['License']['license_type_id']);

                    // Add system note to old license
                    $this->License->Note->sysNote(
                        CakeSession::read("Auth.User.id"),
                        'Licenses',
                        'License',
                        $converted_license['License']['id'],
                        sprintf(
                            'This license was converted from type (%s) to type (%s) by %s on %s',
                            $lic_type['LicenseType']['label'],
                            $application['License']['LicenseType']['label'],
                            CakeSession::read("Auth.User.label"),
                            date('Y-m-d')
                        )
                    );

                    // Add system note to new license
                    $this->License->Note->sysNote(
                        CakeSession::read("Auth.User.id"),
                        'Licenses',
                        'License',
                        $application['License']['id'],
                        sprintf(
                            'This license was converted to type (%s) from type (%s) by %s on %s',
                            $application['License']['LicenseType']['label'],
                            $lic_type['LicenseType']['label'],
                            CakeSession::read("Auth.User.label"),
                            date('Y-m-d')
                        )
                    );
                }
            }

            if ($previous_application['Application']['id'] != $application['Application']['id'])
            {
                // set license gap data
                $license_gap = array();
                $license_gap['LicenseGap']['license_id'] = $application['License']['id'];
                $license_gap['LicenseGap']['previous_application_id'] = $previous_application['Application']['id'];
                $license_gap['LicenseGap']['previous_expire_date'] = $previous_application['Application']['expire_date'] ? date('Y-m-d', strtotime($previous_application['Application']['expire_date'])) : null;
                $license_gap['LicenseGap']['application_id'] = $application['Application']['id'];
                $license_gap['LicenseGap']['effective_date'] = date('Y-m-d', strtotime($application['Application']['effective_date']));

                $license_gap_expire_date = new DateTime($license_gap['LicenseGap']['previous_expire_date']);
                $license_gap_effective_date = new DateTime($license_gap['LicenseGap']['effective_date']);

                $interval = $license_gap_expire_date->diff($license_gap_effective_date);

                // compare the last expire date with the current effective date
                if ($license_gap_expire_date < $license_gap_effective_date && $interval->days > 0)
                {
                    $license_gap['LicenseGap']['diff_days'] = $interval->days;

                    $this->LicenseGap->create();
                    $this->LicenseGap->save($license_gap);
                }
            }

            // Add system note
            $this->License->Note->sysNote(
                CakeSession::read("Auth.User.id"),
                'Licenses',
                'License',
                $application['License']['id'],
                sprintf(
                    '%s (%s) approved by %s on %s',
                    $application['License']['LicenseType']['label'],
                    $application['ApplicationType']['label'],
                    CakeSession::read("Auth.User.label"),
                    date('Y-m-d')
                )
            );

            // commit transaction
            $dataSource->commit();

            // dispatch the course complete event for listeners
            $this->dispatch('Model-Application-saveApproval', array('license_id' => $application['License']['id'], 'from_saveApproval' => true));

            // run the foriegn object model after approval method
            $ForeignModel = ClassRegistry::init($application['License']['foreign_plugin'].'.'.$application['License']['foreign_obj']);

            if ($after_approve && $ForeignModel->hasMethod('afterApprove'))
            {
                $license = $this->License->getApplication($application['License']['id']);
                $ForeignModel->afterApprove($license, $outputDocumentTrigger);
            }

            // return the updated license record
            return true;

        }
        catch (Exception $e)
        {
            // rollback transaction
            $dataSource->rollback();

            throw $e;
        }
    }

    /**
     * getInterimDate function
     *
     * @param int    $id     the app id to find the app interim expire date for
     * @param string $format the date format
     *
     * @return str returns the interim expiration date of the application
     * @access public
     */
    public function getInterimDate ($id = null, $format = 'Y-m-d')
    {
        // default return value
        $retval = null;

        // get the application data
        $application = $this->findById($id);

        // extract and format the expire date
        if ($application && $date = strtotime($application['Application']['interim_expire_date']))
        {
            $retval = date($format, $date);
        }

        // return date or false
        return $retval;
    }

    /**
     * getExpDate function
     *
     * @param int    $id     the applicaiton id to find the app expire date for
     * @param string $format the date format
     *
     * @return str returns the expiration date of the application
     * @access public
     */
    public function getExpDate ($id = null, $format = 'Y-m-d')
    {
        // default return value
        $retval = null;

        // get the license/application data
        $application = $this->findById($id);

        // extract and format the expire date
        if ($application && $date = strtotime($application['Application']['expire_date']))
        {
            $retval = date($format, $date);
        }

        // return date or false
        return $retval;
    }

    public function getApplicationViewData($id)
    {
        $contain = array(
            'ApplicationType',
            'ApplicationStatus',
            'License' => array(
                'ChildLicense' => array('LicenseStatus'),
                'CurrentApplication',
                'LicenseStatus',
                'LicenseType',
                'LicenseVariant' => array('Variant', 'Upload'),
                'ParentLicense' => array('LicenseStatus'),
                'Account' => array(
                    'Address',
                    'Document',
                    'EducationDegree' => array('Degree', 'Upload'),
                    'PracticalWorkPercentage',
                    'WorkExperience',
                    'AccountPhoto',

                ),
                'Firm' => array(
                    'Address',
                    'Contact',
                    'Manager' => array('Account'),
                ),
                'TrainingProvider' => array(
                    'Address',
                    'Course' => array('CourseCatalog'),
                    'CourseLocation' => array('Address'),
                    'InstructorAssignment' => array('Account'),
                    'Manager' => array('Account'),
                ),
            ),
            'QuestionAnswer',
            'Reciprocal',
            'ScreeningAnswer',
            'ThirdPartyTest' => array('Upload'),
        );

        $application = $this->details($id, $contain);

        return $application;
    }

    public function details($id, $contain = false)
    {
        if (empty($id))
        {
            throw new Exception('Invalid id.');
        }

        $entity_contains = null;

        if ($contain !== false && isset($contain['License']))
        {
            $entities = array('Account', 'Firm', 'TrainingProvider');
            foreach ($entities as $entity)
            {
                if (isset($contain['License'][$entity]))
                {
                    $entity_contains[$entity] = $contain['License'][$entity];
                    unset($contain['License'][$entity]);
                }
            }
        }

        $application = $this->find(
            'first',
            array(
                'contain' => $contain,
                'conditions' => array(
                    'Application.id' => $id
                )
            )
        );

        if (isset($application['License']))
        {

            $foreign_plugin = $application['License']['foreign_plugin'];
            $foreign_obj = $application['License']['foreign_obj'];
            $foreign_key = $application['License']['foreign_key'];

            $entity_model = ClassRegistry::init(sprintf('%s.%s', $foreign_plugin, $foreign_obj));
            $entity_data = $entity_model->find(
                'first',
                array(
                    'contain' => $entity_contains[$foreign_obj],
                    'conditions' => array(
                        sprintf('%s.id', $foreign_obj) => $foreign_key
                    )
                )
            );

            if ($foreign_obj == 'Account')
            {
                $entity_data['Account']['CourseRoster'] = ClassRegistry::init('ContinuingEducation.CourseRoster')->getByLicenseTypeForAccount($application['License']['LicenseType']['id'], $entity_data['Account']['id']);
            }

            $application['License'][$foreign_obj] = $entity_data[$foreign_obj];
            unset($entity_data[$foreign_obj]);
            $application = Hash::merge($application, array('License' => array($foreign_obj => $entity_data)));
        }
        return $application;
    }

    public function getCurrentApplication($id)
    {
        $result = $this->find(
            'first',
            array(
                'contain' => array(
                    'License' => array(
                        'fields' => array('License.id'),
                        'CurrentApplication'
                    )
                ),
                'conditions' => array(
                    sprintf('%s.id', $this->alias) => $id
                )
            )
        );

        if (!$current_application = Hash::extract($result, 'License.CurrentApplication'))
        {
            return null;
        }
        return $current_application;
    }

    /**
     * get the previous application data
     *
     * This gets the previous application of the provided ID.
     * If there isn't a previous application returns false
     *
     * @param int $id Application ID
     *
     * @return array Application data array
     * @access public
     */
    public function getPreviousApplication($id)
    {
        // default return data
        $previous_application = false;

        try
        {
            // get license and all application data
            $application = $this->find(
                'first',
                array(
                    'contain' => array(
                        'License' => array(
                            'Application' => array('order' => array('Application.created' => 'asc'))
                        )
                    ),
                    'conditions' => array('Application.id' => $id)
                )
            );

            // did we find that provided application?
            if (!$application)
            {
                throw new Exception ('Invalid application.');
            }

            // loop the appliactions
            foreach($application['License']['Application'] as $app)
            {
                // if the app ID is the same as the provided ID drop out
                if ($app['id'] == $id)
                {
                    break;
                }

                // if we haven't gotten to our provided app yet
                //  set the previous app data
                $previous_application['Application'] = $app;
            }
        }
        catch (Exception $e)
        {
            debug($e->getMessage());

            // force return false just in case
            $previous_application = false;
        }

        // return data or false
        return $previous_application;
    }

    public function getOpenSubmission($id)
    {
        $result = $this->find(
            'first',
            array(
                'contain' => array(
                    'OpenSubmission'
                ),
                'conditions' => array(
                    sprintf('%s.id', $this->alias) => $id
                )
            )
        );

        return isset($result['OpenSubmission']) ? $result['OpenSubmission'] : null;
    }

    public function getCurrentSubmission($id)
    {
        $result = $this->find(
            'first',
            array(
                'contain' => array(
                    'CurrentSubmission'
                ),
                'conditions' => array(
                    sprintf('%s.id', $this->alias) => $id
                )
            )
        );

        return isset($result['CurrentSubmission']) ? $result['CurrentSubmission'] : null;
    }

    public function isOpen($id)
    {
        $this->create();
        $this->id = $id;
        return $this->field('open') == 1;
    }

    /**
     * Flag an application as needing additional review
     * during approval.
     *
     * @param int $id Application ID
     *
     * @return void
     * @access public
     */
    public function flagAdditionalReview($id = null)
    {
        try
        {
            // set the data for save
            $this->set(array('id' => $id, 'additional_review' => 1));

            // save the data
            $this->save();
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * remove the interim expire date
     *
     * @param int $id Application ID
     *
     * @return bool true/false
     * @access public
     */
    public function removeInterim ($id = null)
    {
        try
        {
            // get the full record
            $application = $this->findById($id);

            // attempt to remove the interim expire date
            $this->set(array('id' => $application['Application']['id'], 'interim_expire_date' => null));
            $this->save();

            // Add system note to old license
            $this->License->Note->sysNote(
                CakeSession::read("Auth.User.id"),
                'Licenses',
                'License',
                $application['Application']['license_id'],
                sprintf(
                    'Interim expiration date (%s) was removed by %s on %s.',
                    GenLib::dateFormat($application['Application']['interim_expire_date']),
                    CakeSession::read("Auth.User.label"),
                    GenLib::dateFormat(date('Y-m-d'))
                )
            );

            // dispatch an event for listeners
            $this->dispatch('Model-Application-removeInterim', array('license_id' => $application['Application']['license_id']));

        }
        catch (Exception $e)
        {
            // we have failed
            debug($e->getMessage());

            return false;
        }

        // no exceptions, return true
        return true;
    }

    /**
     * notFutureDate
     *
     * validation method to ensure date is not in the future.
     *
     * @param str $date date
     *
     * @return bool
     * @access public
     */
    public function notFutureDate($check = null)
    {
        $value = array_values($check);
        $value = $value[0];
        return $value <= date('Y-m-d');
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id Application id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        // confirm app id was passed in
        if (!$id)
        {
            return false;
        }

        // look up application record
        $application = $this->findById($id);

        // for application checks, cannot use checkOwnerOrManager sending the License Model at this point, sql error
        //debug($this->checkOwnerOrManager('Licenses.License', $application['Application']['license_id']));

        // so we look up corresponding license record
        $license = $this->License->findById($application['Application']['license_id']);

        // check for ownership of license record
        if($license['License']['foreign_key'] == CakeSession::read("Auth.User.id"))
        {
            return true;
        }

        return false;
    }
}