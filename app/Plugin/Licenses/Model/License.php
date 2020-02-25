<?php
App::uses('CakeSession', 'Model/Datasource');

/**
 * License model
 *
 * Extends the AppModel. Responsible for managing license data.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class License extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'License';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'license_number';

    /**
     * Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Payments.Payable',
        'Searchable.Searchable',
    );

    /**
     * belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'foreign_key',
            'conditions' => array('License.foreign_obj' => 'Account')
        ),
        'Firm' => array(
            'className' => 'Firms.Firm',
            'foreignKey' => 'foreign_key',
            'conditions' => array('License.foreign_obj' => 'Firm')
        ),
        'TrainingProvider' => array(
            'className' => 'ContinuingEducation.TrainingProvider',
            'foreignKey' => 'foreign_key',
            'conditions' => array('License.foreign_obj' => 'TrainingProvider')
        ),
        'LicenseNumber' => array(
            'className' => 'Licenses.LicenseNumber',
            'foreignKey' => 'license_number_id',
        ),
        'LicenseType' => array(
            'className' => 'Licenses.LicenseType',
            'foreignKey' => 'license_type_id',
        ),
        'LicenseStatus' => array(
            'className' => 'Licenses.LicenseStatus',
            'foreignKey' => 'license_status_id',
        ),
        'CurrentApplication' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_id',
        ),
    );

    /**
     * hasOne
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'Contractor' => array(
            'className' => 'Licenses.Contractor',
            'foreignKey' => 'license_id',
        ),
        'LicenseExpireReason' => array(
            'className' => 'Licenses.LicenseExpireReason',
            'foreignKey' => 'license_id',
        ),
        'OpenApplication' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'license_id',
            'conditions' => array('OpenApplication.open' => 1),
            // there should only ever be one open applicaiton per license
        ),
    );

    public $hasAndBelongsToMany = array(
        'ParentLicense' => array(
            'className' => 'Licenses.License',
            'joinTable' => 'licenses_licenses',
            'foreignKey' => 'child_id',
            'associationForeignKey' => 'parent_id',
        ),
        'ChildLicense' => array(
            'className' => 'Licenses.License',
            'joinTable' => 'licenses_licenses',
            'foreignKey' => 'parent_id',
            'associationForeignKey' => 'child_id',
        )
    );

    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'license_id',
            'order' => array('Application.id' => 'DESC'),
            'dependent' => true
        ),
        'Note' => array(
            'className' => 'Notes.Note',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Note.foreign_obj' => 'License'),
            'dependent' => true
        ),
        'LicenseGap' => array(
            'className' => 'Licenses.LicenseGap',
            'foreignKey' => 'license_id',
            'order' => array('LicenseGap.previous_expire_date' => 'DESC'),
            'dependent' => true
        ),
        'LicenseVariant' => array(
            'className' => 'Licenses.LicenseVariant',
            'foreignKey' => 'license_id',
            'dependent' => true
        ),
        'InsuranceInformation' => array(
            'className'  => 'Licenses.InsuranceInformation',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'InsuranceInformation.foreign_plugin' => 'Licenses',
                'InsuranceInformation.foreign_obj' => 'License',
            ),
            'dependent' => true
        ),
        'LicensesLicense' => array(
            'className' => 'Licenses.LicensesLicense',
            'foreignKey' => 'parent_id',
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
        'license_number' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );

    /**
     * Includes license foreign obj data
     * via afterFind if License model is
     * primary model in query
     */
    public $includeForeignData = true;

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
    public function beforeDelete($cascade = false)
    {
        // remove child records
        $this->LicenseExpireReason->deleteAll(array('LicenseExpireReason.license_id' => $this->id));
        $this->Application->deleteAll(array('Application.license_id' => $this->id));
        $this->LicensesLicense->deleteAll(array('LicensesLicense.parent_id' => $this->id, 'LicensesLicense.child_id' => $this->id));
        $this->Note->deleteAll(array('Note.foreign_obj' => 'License', 'Note.foreign_key' => $this->id));
        $this->LicenseGap->deleteAll(array('LicenseGap.license_id' => $this->id));
        $this->LicenseVariant->deleteAll(array('LicenseVariant.license_id' => $this->id));

        return parent::beforeDelete($cascade);
    }

    /**
     * Delete function overriding delete in parent class
     *
     * @param int     $id      id of item to be deleted
     * @param boolean $cascade Set to true to delete records that depend on this record
     *
     * @return boolean True on success
     *
     * @todo throw exception if there is related data
     */
    public function delete($id=null, $cascade=true)
    {
        // turn off foreign data
        $this->includeForeignData = false;

        return parent::delete($id, $cascade);
    }

    /**
     * afterFind method
     *
     * Does stuff after finding records
     *      * Query for foreign data and merge in to license data array
     *
     * @param array $results expecting cake query data array
     * @param bool  $primary cakephp param to determine if model is primary model in cake query
     *
     * @return true
     * @access public
     *
     * @todo move this to appModel to make this apply globally to record sets containing foreign_obj field
     */
    public function afterFind($results = array(), $primary = false)
    {
        // dont forget to run the parent afterFind
        parent::afterFind($results, $primary);

        try
        {
            if ($this->includeForeignData && $primary && GenLib::isData($results, '0.License'))
            {
                // get related foreign obj data
                // this has to be done here because we don't know the foreign obj model
                // until we have our records
                $results = $this->_addForeignObjectData($results);
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }

        // turn back on foreign data
        $this->includeForeignData = true;

        return $results;
    }

    /**
     * generateLicenseNumber method
     *
     * Generates a new license number. A license record must
     * exist before the license number is generated.
     *
     * 0000345-TRPR
     * (license_numbers primary key)-(lic type abbr)
     *
     * @param int $license_id license id
     *
     * @return string
     * @access public
     */
    public function generateLicenseNumber($license_id)
    {
        try
        {
            $license = $this->find(
                'first',
                array(
                    'contain' => array(
                        'LicenseNumber',
                        'LicenseType',
                        'LicenseVariant'
                    ),
                    'conditions' => array(
                        'License.id' => $license_id
                    )
                )
            );

            // incomplete licenses have 'INCOMPLETE-<license type suffix>' for a license number
            if (! preg_match('/^incomplete/i', $license['License']['license_number']))
            {
                return $license['License']['license_number'];
            }

            $ln_key = str_pad($license['LicenseNumber']['id'], 7, '0', STR_PAD_LEFT);

            // license type abbr
            $lic_type_abbr = $license['LicenseType']['abbr'];

            // build a new license number
            $license_number = strtoupper(sprintf('%s-%s', $ln_key, $lic_type_abbr));

            // append variant label to end of license number
            if (Configure::read('Licenses.license_number_variants') && count($license['LicenseVariant']) > 0)
            {
                $license_number .= sprintf('-%s', $license['LicenseVariant'][0]['Variant']['abbr']);
            }

            return $license_number;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * resetExpireDate function
     *
     * @param Event $event object
     *
     * @return bool returns true or false
     * @access public
     */
    public function resetExpireDate($event)
    {
        try
        {
            // double check out inputs
            if (!isset($event->data['license_id']))
            {
                throw new Exception('Event did not pass license id.');
            }

            // is license_id an array of ids or a single id
            $license_ids = !is_array($event->data['license_id']) ? array($event->data['license_id']) : $event->data['license_id'];

            // loop each of the license ids even if there is only one
            foreach ($license_ids as $id)
            {
                // turn on foreign obj data
                $this->includeForeignData = true;

                // get the license record
                $contain = array('Application', 'CurrentApplication', 'LicenseType');
                if (!$license = $this->getLicenseById($id, array()))
                {
                    throw new Exception('Failed to get license data.');
                }

                // skip licenses that are incomplete (new and not yet approved)
                if ($license['License']['license_status_id'] == 9)
                {
                    // skip to the next record
                    continue;
                }

                // get the dates of things that expire a license
                $expire_dates = $this->getLicenseExpireDates($id);

                // update the license record's expire_date with earliest expiring date
                if ($expire_dates['next'] !== null)
                {
                    $earliest_expire_date = $expire_dates['dates'][$expire_dates['next']];

                    $this->set('id', $id);
                    if (!$this->saveField('expire_date', $earliest_expire_date))
                    {
                        throw new Exception('Failed to update license expiration date.');
                    }

                    switch ($expire_dates['next'])
                    {
                    case 'application':
                        $description = "application expiration";
                        break;
                    case 'interim':
                        $description = "interim expiration";
                        break;
                    case 'course':
                        $description = "course expiration";
                        break;
                    case 'reciprocal':
                        $description = "reciprocal expiration";
                        break;
                    default:
                        $description = 'unknown';
                        break;
                    }

                    if (!$this->LicenseExpireReason->setReason($id, $earliest_expire_date, $description))
                    {
                        throw new Exception('Failed to set license expiration reason.');
                    }
                }
                else
                {
                    throw new Exception('Failed to determine earliest license expiration date.');
                }

                // turn on foreign obj data
                $this->includeForeignData = true;

                // get the updated license information
                $updated_license = $this->getLicenseById($id, $contain);

                // if the license expiration date changed, format data for queue doc
                if (date('Y-m-d', strtotime($license['License']['expire_date'])) != date('Y-m-d', strtotime($updated_license['License']['expire_date'])))
                {
                    // if the license is in a suspended status, do not produce the output doc
                    if ($updated_license['License']['license_status_id'] == 3)
                    {
                        continue;
                    }
                    else
                    {
                        // produce the certification letter (*_renewal) if the expiration date is in the future
                        if (isset($updated_license['Account']) && !$updated_license['Account']['no_mail'])
                        {
                            // the License->saveApproval method has already generated a doc, so don't create another one
                            if(isset($event->data['from_saveApproval']) && $event->data['from_saveApproval'] == true)
                            {
                                continue;
                            }
                            else
                            {
                                // if the updated license expiration date is in the future, generate the doc
                                if(date('Y-m-d', strtotime($updated_license['License']['expire_date'])) > date('Y-m-d', strtotime('today')))
                                {
                                    $event->data['queue_docs'][] = array(
                                        'fp'         => 'Accounts',
                                        'fo'         => 'Account',
                                        'fk'         => $updated_license['Account']['id'],
                                        'trigger'    => $updated_license['LicenseType']['abbr'].'_renewal',
                                        'license_id' => $updated_license['License']['id']
                                    );
                                }
                            }
                        }

                        $event->data['exp_date_changed'] = true;

                        $this->dispatch('Model-License-exp_date_changed', $event->data);
                    }
                }

                $this->dispatch('Model-License-resetExpireDate', $event->data);
            }

            // return success
            return true;
        }
        catch (Exception $e)
        {
            // record the error
            $this->log($e->getMessage());

            // return failure
            return false;
        }
    }

    /**
     * get all expire dates for a license
     *
     * @param int $id License ID
     *
     * @return array An array of expire dates
     * @access public
     */
    public function getLicenseExpireDates ($id = null)
    {
        // default return value
        $expire_dates = array(
            'dates' => array(
                'application' => null,
                'interim' => null,
                'course' => null,
                'reciprocal' => null
            ),
            'next' => null,
            'bypass' => false
        );

        try
        {
            // get the license record
            $this->includeForeignData = false;
            $license = $this->getLicenseById($id, array('CurrentApplication'));

            // get the application and iterim expiration dates
            $expire_dates['dates']['application'] = $this->Application->getExpDate($license['License']['application_id']);
            $expire_dates['dates']['interim'] = $this->Application->getInterimDate($license['License']['application_id']);

            // get the course and reciprocal expiration dates if foreign obj is Account
            if ($license['License']['foreign_obj'] == 'Account')
            {
                // load needed models
                $CourseRoster = ClassRegistry::init('ContinuingEducation.CourseRoster');
                $expire_dates['dates']['course'] = $CourseRoster->getCourseExpiration($id);
                $expire_dates['dates']['reciprocal'] = $CourseRoster->getReciprocalExpiration($id);
            }

            // order the exipre dates
            asort($expire_dates['dates']);

            // if bypass validation has been set, exclude anything but application and interim dates
            if ($license['CurrentApplication']['bypass_validation'])
            {
                $expire_dates['dates']['course'] = null;
                $expire_dates['dates']['reciprocal'] = null;

                $expire_dates['bypass'] = true;
            }

            // loop the expire dates and choose the next expiring date
            foreach ($expire_dates['dates'] as $reason => $date)
            {
                // if null skip it
                if (empty($date))
                {
                    continue;
                }

                // if we haven't set the earliest date set it and skip to next record
                if ($expire_dates['next'] === null)
                {
                    $expire_dates['next'] = $reason;
                    continue;
                }

                // if next expiring date has a value then compare
                if (strtotime($date) < strtotime($expire_dates['dates'][$expire_dates['next']]))
                {
                    $expire_dates['next'] = $reason;
                }
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }

        return $expire_dates;
    }

    /**
     * Adds a variant to a license. Also adds license variant abbreviation to license number.
     *
     * @param int   $license_id License ID
     * @param int   $variant_id Variant ID
     * @param array $data       Variant data
     *
     * @return boolean True or false
     * @access public
     *
     * @throws Exception If license already has the specified variant.
     * @throws Exception If variant cannot be added to the license.
     * @throws Exception If license number cannot be updated with the variant's abbreviation.
     */
    public function addVariant($license_id, $variant_id, $data = array())
    {
        $license = $this->details($license_id);
        $variant = $this->LicenseVariant->Variant->details($variant_id);

        // check to make sure we don't already have this variant
        if ($this->hasVariant($license_id, $variant_id))
        {
            throw new Exception(
                sprintf(__('License already has %s variant.'), $variant['Variant']['abbr'])
            );
        }

        // add variant
        if (! $this->LicenseVariant->add($data))
        {
            throw new Exception(__('Unable to save license variant.'));
        }

        // update license number with variant abbreviation if configured to do so
        if (Configure::read('Licenses.license_number_variants'))
        {
            $newLicenseNumber = sprintf('%s-%s', $license['License']['license_number'], $variant['Variant']['abbr']);

            $this->id = $license_id;

            if (! $this->saveField('license_number', $newLicenseNumber))
            {
                throw new Exception(__('Unable to update license number to account for variant.'));
            }
        }

        return true;
    }

    /**
     * Removes a variant from a license. Also removes variant abbreviation from license number.
     *
     * @param int $license_id         License ID
     * @param int $license_variant_id License variant ID
     *
     * @return boolean True or false
     * @access public
     *
     * @throws Exception If license doesn't have specified variant.
     * @throws Exception If unable to remove variant from license.
     * @throws Exception If unable to remove variant abbreviation from license number.
     */
    public function removeVariant($license_id, $license_variant_id)
    {
        if (! $this->LicenseVariant->exists($license_variant_id))
        {
            throw new Exception(__('License does not have specified variant.'));
        }

        $license = $this->details($license_id);
        $licenseVariant = $this->LicenseVariant->details($license_variant_id, array('Variant'));

        if (! $this->LicenseVariant->delete($license_variant_id))
        {
            throw new Exception(
                sprintf(__('Unable to remove %s license variant.'), $licenseVariant['Variant']['abbr'])
            );
        }

        // remove variant abbreviation from license number
        $abbrev = $licenseVariant['Variant']['abbr'];
        $newLicenseNumber = str_replace("-{$abbrev}", '', $license['License']['license_number']);
        $this->id = $license['License']['id'];
        if (! $this->saveField('license_number', $newLicenseNumber))
        {
            throw new Exception(sprintf(__('Unable to remove %s variant from license number,'), $abbrev));
        }

        return true;
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
     * getVariantList method
     *
     * @return array returns a list of variants
     * @access public
     */
    public function getVariantList ()
    {
        return $this->LicenseVariant->Variant->find('list');
    }

    /**
     * getLicenseStatusList method
     *
     * @return array returns a list of license types
     * @access public
     */
    public function getLicenseStatusList ()
    {
        return $this->LicenseStatus->getLicenseStatusList();
    }

    /**
     * isApproved method
     *
     * Checks a license to see if it has been approved at least once before.
     *
     * @param int $id expecting license record ID
     *
     * @return bool
     * @access public
     */
    public function isApproved ($id = null)
    {
        // get the license record
        $license = $this->getLicenseById($id);

        // if submit_paid_date is null the license is incomplete
        if ($license && $license['License']['submit_paid_date'])
        {
            return true;
        }

        return false;
    }

    /**
     * setPending method
     *
     * Toggles the pending status of a license record.
     *
     * @param int  $id      expecting license record ID
     * @param bool $pending expecting true|false
     *
     * @return bool
     * @access public
     */
    public function setPending ($id = null, $pending = true)
    {
        // check that we can set the license to pending
        if ($this->isApproved($id))
        {
            $this->id = $id;
            return $this->saveField('pending', $pending);
        }

        return false;
    }

    /**
     * calcApprovalDates method
     *
     * @param string $license_type       License Type
     * @param string $prev_expire_date   Previous expiration date (default: null)
     * @param string $materials_received Materials received date
     *
     * @return array Issued date, effective date, expired date
     * @access public
     */
    public function calcApprovalDates ($license_type = null, $prev_expire_date = null, $materials_received = null)
    {
        // define the dates
        $prev_expire_date = ($prev_expire_date ? strtotime($prev_expire_date) : null);
        $materials_received = ($materials_received ? strtotime($materials_received) : time());

        // determine the correct date to use for expire calcuation
        $calc_date = $materials_received;

        if ($prev_expire_date)
        {
            $calc_date = ($prev_expire_date > $materials_received ? $prev_expire_date : $materials_received);
        }

        // static or dynamic renewal
        if (preg_match('/^[0-9]{4}(\-[0-9]{2}){2}$/', $license_type['static_expiration']))
        {
            // static renewal period

            // grab our static expiration dates
            list($base_year, $base_month, $base_day) = explode('-', $license_type['static_expiration']);

            // get the renewal cycle start date
            $year = date('Y', $materials_received);
            $cycle_years = $license_type['cycle'] / 12;
            $expiration_year = $year + abs((($year-$base_year) % $cycle_years) - $cycle_years);

            $renewal_start_date = strtotime(
                sprintf(
                    '%s -%d %s',
                    date('Y-m-d', strtotime(sprintf('%s-%s-%d', $expiration_year, $base_month, $base_day))),
                    $license_type['renew_before'],
                    ($license_type['month_calc'] ? 'months' : 'days')
                )
            );

            // if calc date before renewal period don't add the additional cycle
            if ($materials_received < strtotime(sprintf('%s +1 days -%s months', date('Y-m-d', $renewal_start_date), $license_type['cycle'])))
            {
                $renewal_start_date = strtotime(sprintf('%s -%s months', date('Y-m-d', $renewal_start_date), $license_type['cycle']));
            }

            $expire_date = strtotime(sprintf('%s-%s-%s', date('Y', $renewal_start_date), $base_month, $base_day));
        }
        else
        {
            // dynamic renewal period
            $interval = sprintf(
                '%s +%d %s',
                date('Y-m-d', $calc_date),
                $license_type['cycle'],
                ($license_type['month_calc'] ? 'months' : 'days')
            );
            $expire_date = strtotime($interval);
        }

        // format the dates
        $expire_date = date('Y-m-d H:i:s', $expire_date);
        $effective_date = date('Y-m-d H:i:s', $materials_received);

        return array($effective_date, $expire_date);
    }

    /**
     * renewLicense method
     *
     * This does not renew the license. This will
     * ONLY create a new renewal application for
     * the license.
     *
     * @param int $id expecting license record id
     *
     * @return array new license rec
     * @access public
     */
    public function renewLicense ($id = null)
    {
        // get the license record
        if (!$license = $this->getApplication($id))
        {
            throw new Exception(__('Failed to get license record.'));
        }

        // create the renewal application
        $data = array();
        $data['Application']['license_id'] = $license['License']['id'];
        $data['Application']['application_type_id'] = $this->Application->ApplicationType->field('id', array('label' => 'Renewal'));
        $data['Application']['application_status_id'] = $this->Application->ApplicationStatus->field('id', array('label' => 'Incomplete'));
        $data['Application']['created'] = date('Y-m-d H:i:s');
        $data['OpenSubmission'] = array('created' => date('Y-m-d H:i:s'));

        // add general answer placeholders and questions
        $general_questions = $this->LicenseType->Question->find(
            'all',
            array(
                'conditions' => array('Question.license_type_id' => $license['LicenseType']['id'])
            )
        );

        foreach ($general_questions as $question)
        {
            $data['QuestionAnswer'][] = array(
                'question_id' => $question['Question']['id'],
                'question' => $question['Question']['question'],
            );
        }

        // add screening answer placeholders and questions
        $screening_questions = $this->LicenseType->ScreeningQuestion->find(
            'all',
            array(
                'conditions' => array('ScreeningQuestion.license_type_id' => $license['LicenseType']['id'])
            )
        );

        foreach ($screening_questions as $question)
        {
            $data['Application']['ScreeningAnswer'][] = array(
                'screening_question_id' => $question['ScreeningQuestion']['id'],
                'screening_question' => $question['ScreeningQuestion']['question'],
                'correct_answer' => $question['ScreeningQuestion']['correct_answer'],
                'answer' => null,
            );
        }

        $this->Application->create();
        if (!$this->Application->saveAll($data, array('deep' => true)))
        {
            throw new Exception(__('Failed to create renewal application record.'));
        }

        $application_id = $this->Application->getInsertID();

        // set the submission id in the applications record
        $application_submissions = $this->Application->ApplicationSubmission->findByApplicationId($application_id);
        $this->Application->id = $application_id;
        $this->Application->saveField('application_submission_id', $application_submissions['ApplicationSubmission']['id']);

        return $application_id;
    }

    /**
     * canRenew method
     *
     * @param int $license_id license record id
     *
     * @return bool
     * @access public
     */
    public function canRenew ($license_id)
    {
        try
        {
            // default return value
            $can_renew = false;

            // contain
            $contain = array(
                'LicenseStatus',
                'LicenseType',
                'OpenApplication',
                'Application' => array(
                    'order' => array('Application.id' => 'DESC')
                ),
            );

            // get the license record
            $license = $this->getLicenseById($license_id, $contain);
            //debug($license); exit();
            //print('<pre>'); print_r($license); print('</pre>'); exit;

            // is there already an open application?
            if (GenLib::isData($license, 'OpenApplication', array('id')))
            {
                return false;
            }

            // is the not_renewing flag set?
            if ($license['License']['not_renewing'])
            {
                return false;
            }

            // is the pending flag set?
            if ($license['License']['pending'])
            {
                return false;
            }

            // has the license been converted
            if (in_array($license['LicenseStatus']['status'], array('Converted', 'Suspended')))
            {
                return false;
            }

            // is the license status Expired
            if ($license['LicenseStatus']['status'] == 'Expired')
            {
                return true;
            }

            // is the license already expired?
            if ($license['License']['expire_date'] && time() > strtotime($license['License']['expire_date']))
            {
                return true;
            }

            // dont add the cycle twice for dynamic renewal periods
            if ($license['LicenseType']['static_expiration'] == null)
            {
                $license['LicenseType']['cycle'] = 0;
            }

            // calculate the start date range for renewal opportunity
            list($effective_date, $renewal_end_date) = $this->calcApprovalDates($license['LicenseType'], $license['Application'][0]['expire_date'], $license['Application'][0]['effective_date']);


            $renewal_end_date = strtotime($renewal_end_date);

            $renewal_start_date = strtotime(
                sprintf(
                    '%s -%d %s',
                    date('Y-m-d', $renewal_end_date),
                    $license['LicenseType']['renew_before'],
                    ($license['LicenseType']['month_calc'] ? 'months' : 'days')
                )
            );

            // add one day - trust me
            $renewal_end_date = strtotime(sprintf('%s +1 day', date('Y-m-d', $renewal_end_date)));

            $can_renew = (time() >= $renewal_start_date && time() <= $renewal_end_date ? true : false);

            return $can_renew;
        }
        catch (Exception $e)
        {
            $this->log($e->getMessage());
            throw new Exception(__('Failed to calculate the license renewal period.'));
        }
    }

    /**
     * canConvert method
     *
     * @param int $license_id      license record id
     * @param int $convert_type_id license record id
     *
     * @return bool
     * @access public
     */
    public function canConvert($license_id, $convert_type_id = null)
    {
        // get the license data
        $this->includeForeignData = false;
        $license = $this->find(
            'first',
            array(
                'contain' => array(
                    'Application',
                    'LicenseStatus' => array('fields' => array('status')),
                    'LicenseType' => array(
                        'fields' => array('label'),
                        'LicenseTypeConversion' => array('fields' => array('convert_type_id'))
                    )
                ),
                'conditions' => array(
                    'License.id' => $license_id
                )
            )
        );

        // has this license already been converted?
        if (Hash::get($license, 'LicenseStatus.status') == 'Converted')
        {
            return false;
        }

        // can the license type convert to the convert type?
        if ($convert_type_id)
        {
            $convert_type_allowed = false;

            foreach ($license['LicenseType']['LicenseTypeConversion'] as $convert_type)
            {
                if ($convert_type['convert_type_id'] == $convert_type_id)
                {
                    $convert_type_allowed = true;
                    break;
                }
            }

            if (!$convert_type_allowed)
            {
                return false;
            }
        }

        // passed all conditions
        return true;
    }

    /**
     * newLicense method
     *
     * @param int $license_type_slug   expecting license type slug
     * @param int $foreign_key         expecting foreign record id
     * @param str $foreign_obj         expecting foreign model name
     * @param str $foreign_plugin      expecting foreign plugin name
     * @param str $variant_abbr        variant abbrieviation
     * @param str $app_type            App type
     * @param int $existing_license_id Existing license id
     *
     * @return array new license rec
     * @access public
     */
    public function newLicense ($license_type_slug = null, $foreign_key = null , $foreign_obj = null, $foreign_plugin = null, $variant_abbr = null, $app_type = null, $existing_license_id = null)
    {
        // validate the inputs
        if (! preg_match('/^[1-9]{1}[0-9]*$/', $foreign_key) || $foreign_obj == null || $license_type_slug == null)
        {
            throw new Exception(__('Invalid input type(s).'));
        }

        // get the foreign object record
        $ForeignModel = ClassRegistry::init(sprintf('%s.%s', $foreign_plugin, $foreign_obj));
        $foreign_record = $ForeignModel->find(
            'first',
            array(
                'conditions' => array(sprintf('%s.id', $foreign_obj) => $foreign_key)
            )
        );

        // get the license type id
        if (! $license_type = $this->LicenseType->getLicenseTypeBySlug($license_type_slug))
        {
            throw new Exception(__('Invalid license type.'));
        }

        // get the incomplete license status ID
        if (! $license_status_id = $this->LicenseStatus->getStatusId('Incomplete'))
        {
            throw new Exception(__('Invalid license status.'));
        }

        // build the data array
        $data = array(
            'License' => array(
                'foreign_plugin' => $foreign_plugin,
                'foreign_obj' => $foreign_obj,
                'foreign_key' => $foreign_key,
                'license_number' => sprintf('Incomplete-%s', $license_type['LicenseType']['abbr']),
                'license_type_id' => $license_type['LicenseType']['id'],
                'license_status_id' => $license_status_id,
            ),
            'Application' => array(
                array(
                    'application_type_id' => $this->Application->ApplicationType->field('id', array('label' => 'Initial')),
                    'application_status_id' => $this->Application->ApplicationStatus->field('id', array('label' => 'Incomplete')),
                    'OpenSubmission' => array('created' => date('Y-m-d H:i:s')),
                ),
            ),
        );

        // add general answer placeholders and questions
        $general_questions = $this->LicenseType->Question->find(
            'all',
            array(
                'conditions' => array('Question.license_type_id' => $license_type['LicenseType']['id'])
            )
        );

        foreach ($general_questions as $question)
        {
            $data['Application'][0]['QuestionAnswer'][] = array(
                'question_id' => $question['Question']['id'],
                'question' => $question['Question']['question'],
            );
        }

        // add screening answer placeholders and questions
        $screening_questions = $this->LicenseType->ScreeningQuestion->find(
            'all',
            array(
                'conditions' => array('ScreeningQuestion.license_type_id' => $license_type['LicenseType']['id'])
            )
        );

        foreach ($screening_questions as $question)
        {
            $data['Application'][0]['ScreeningAnswer'][] = array(
                'screening_question_id' => $question['ScreeningQuestion']['id'],
                'screening_question' => $question['ScreeningQuestion']['question'],
                'correct_answer' => $question['ScreeningQuestion']['correct_answer'],
            );
        }

        // if this is a license conversion, update the application type id and existing license id in the new application
        if ($app_type == 'Conversion')
        {
            $data['Application'][0]['application_type_id'] = $this->Application->ApplicationType->field('id', array('label' => 'Conversion'));
            $data['Application'][0]['converted_license_id'] = $existing_license_id;
        }

        // are we also adding a variant?
        if ($variant_abbr)
        {
            // get the variant data
            if (!$variant_data = $this->LicenseVariant->Variant->getVariantByAbbr($variant_abbr))
            {
                throw new Exception(sprintf('Failed to get variant data (%s).', $variant_abbr));
            }

            // Append the variant id to the data array
            $data['LicenseVariant'][0]['variant_id'] = $variant_data['Variant']['id'];
        }

        // check to see if this foreign object already has a base license number record
        $license_number = $this->LicenseNumber->find(
            'first',
            array(
                'conditions' => array(
                    'LicenseNumber.foreign_obj' => $foreign_obj,
                    'LicenseNumber.foreign_key' => $foreign_key
                )
            )
        );

        if ($license_number)
        {
            $data['License']['license_number_id'] = $license_number['LicenseNumber']['id'];
        }
        else
        {
            // no previous base license number record - lets create one.
            $data['LicenseNumber']['foreign_plugin'] = $foreign_plugin;
            $data['LicenseNumber']['foreign_obj'] = $foreign_obj;
            $data['LicenseNumber']['foreign_key'] = $foreign_key;
        }
        //debug($data); exit();

        if (! $this->saveAll($data, array('deep' => true)))
        {
            // fail
            throw new Exception(__('Failed to create License.'));
        }

        $application_id = $this->Application->getInsertID();

        // set the application as current application
        $this->saveField('application_id', $application_id);

        // set the submission id in the applications record
        $application_submissions = $this->Application->ApplicationSubmission->findByApplicationId($application_id);
        $this->Application->id = $application_id;
        $this->Application->saveField('application_submission_id', $application_submissions['ApplicationSubmission']['id']);

        // dispatch the after save event to update the License.label field
        $this->dispatch(
            'Model-License-afterSave',
            array(
                'License.label' => $foreign_record[$foreign_obj]['label'],
                'License.id' => $this->getInsertID()
            )
        );

        // pass
        return $this->getLicenseById($this->getInsertID(), array('CurrentApplication'));
    }

    /**
     * suspend license mentod
     *
     * @param int $id License record id
     *
     * @return bool
     * @access public
     */
    public function suspend ($id = null, $note = '')
    {
        // get the license data
        $this->includeForeignData = false;

        $license = $this->find(
            'first',
            array(
                'contain' => array('LicenseStatus'),
                'conditions' => array('License.id' => $id)
            )
        );

        // throw exception if no license found
        if (!$license)
        {
            throw new Exception('Could not find license data.');
        }

        // get the suspended license status id
        if (!$suspend_status = $this->LicenseStatus->findByStatus('Suspended'))
        {
            throw new Exception('Could not find suspended status.');
        }

        // was the license already suspended?
        if ($license['LicenseStatus']['id'] == $suspend_status['LicenseStatus']['id'])
        {
            throw new Exception('License was already suspended.');
        }

        // format the data for save
        $data = array(
            'License' => array(
                'id' => $id,
                'license_status_id' => $suspend_status['LicenseStatus']['id']
            ),
            'Note' => array(
                array(
                    'account_id' => CakeSession::read("Auth.User.id"),
                    'foreign_plugin' => 'License',
                    'foreign_obj' => 'License',
                    'foreign_key' => $id,
                    'note' => $note
                )
            ),
        );

        // update the license status to suspended
        if (!$this->saveAll($data))
        {
            //throw new Exception('Could not save license status.');
            return false;
        }

        // add system note to suspended license
        $this->Note->sysNote(
            CakeSession::read("Auth.User.id"),
            'Licenses',
            'License',
            $id,
            sprintf(
                'This license was suspended by %s on %s',
                CakeSession::read("Auth.User.label"),
                date('Y-m-d')
            )
        );

        // no expections thrown return true
        return true;
    }

    /**
     * activate license method
     *
     * @param int $id License record id
     *
     * @return bool
     * @access public
     */
    public function activate ($id = null)
    {
        // get the license data
        $this->includeForeignData = false;

        $license = $this->find(
            'first',
            array(
                'contain' => array('LicenseStatus'),
                'conditions' => array('License.id' => $id)
            )
        );

        // throw exception if no license found
        if (!$license)
        {
            throw new Exception('Could not find license data.');
        }

        if ($license['License']['expire_date'] >= date('Y-m-d'))
        {
            // get the active license status id
            if (!$active_status = $this->LicenseStatus->findByStatus('Active'))
            {
                throw new Exception('Could not find suspended status.');
            }

            // was the license already active?
            if ($license['LicenseStatus']['id'] == $active_status['LicenseStatus']['id'])
            {
                throw new Exception('License was already active.');
            }

            // format the data for save
            $data = array(
                'License' => array(
                    'id' => $id,
                    'license_status_id' => $active_status['LicenseStatus']['id']
                )
            );

            // update the license status to active
            if (!$this->save($data))
            {
                throw new Exception('Could not save license status.');
            }

            // add system note to suspended license
            $this->Note->sysNote(
                CakeSession::read("Auth.User.id"),
                'Licenses',
                'License',
                $id,
                sprintf(
                    'This license was activated by %s on %s',
                    CakeSession::read("Auth.User.label"),
                    date('Y-m-d')
                )
            );
        }
        else
        {
            $this->expireLicense($id);
        }

        // no expections thrown return true
        return true;
    }

    /**
     * Retrieves interim licenses for a given account.
     *
     * @param int    $account_id  Account ID
     *
     * @return array Interim licenses owned by the specified account.
     * @access public
     */
    public function getInterimLicenses($account_id)
    {
        $interimLicenseStatusID = $this->LicenseStatus->getStatusId('Interim');

        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'License.license_status_id' => $interimLicenseStatusID,
                    'License.foreign_obj'       => 'Account',
                    'License.foreign_key'       => $account_id,
                ),
                'contain' => array(
                    'LicenseType' => array('CourseCatalog'),
                    'CurrentApplication'
                ),
            )
        );
    }

    /**
     * getLicenseById method
     *
     * @param int   $id      license ID expected
     * @param array $contain an array of other models to include
     *
     * @return array
     * @access public
     */
    public function getLicenseById ($id = null, $contain = array())
    {
        // return results
        return $this->find(
            'first',
            array(
                'conditions' => array('License.id' => $id),
                'contain' => $contain
            )
        );
    }

    /**
     * getLicenseList method
     *
     * @param int  $foreign_key    expecting foreign record id
     * @param str  $foreign_obj    expecting foreign model name
     * @param str  $foreign_plugin expecting foreign plugin name
     * @param bool $active_only    expecting true/false
     *
     * @return array an array of license data
     * @access public
     */
    public function getLicenseList ($foreign_key = null, $foreign_obj = null, $foreign_plugin = null, $active_only = false)
    {
        // query options
        $options = array(
            'conditions' => array(
                'License.foreign_plugin' => $foreign_plugin,
                'License.foreign_obj' => $foreign_obj,
                'License.foreign_key' => $foreign_key,
            ),
            'contain' => array('LicenseType' => array('CourseCatalog'), 'LicenseStatus')
        );

        // active licenses only
        if ($active_only)
        {
            $options['conditions']['LicenseStatus.status'] = 'Active';
        }
        //debug($options); exit();

        // grab the licenses
        $licenses = $this->find(
            'all',
            $options
        );

        return $licenses;
    }

    /**
     * getLicenseByType method
     *
     * @param str $license_type_slug expecting license type slug
     * @param int $foreign_key       expecting foreign record id
     * @param str $foreign_obj       expecting foreign model name
     * @param str $foreign_plugin    expecting foreign plugin name
     *
     * @return array an array of license data
     * @access public
     */
    public function getLicenseByType ($license_type_slug = null, $foreign_key = null, $foreign_obj = null, $foreign_plugin = null)
    {
        $contain = array(
            'LicenseType',
        );

        $license = $this->find(
            'first',
            array(
                'conditions' => array(
                    'License.foreign_plugin' => $foreign_plugin,
                    'License.foreign_obj' => $foreign_obj,
                    'License.foreign_key' => $foreign_key,
                    'LicenseType.slug' => $license_type_slug,
                ),
                'contain' => $contain
            )
        );
        //debug($license); exit();

        return $license;
    }

    /**
     * getLicenseDocLinks method
     *
     * gets license document links
     *
     * @param int $id expecting license record id
     *
     * @return array
     * @access public
     */
    public function getLicenseDocLinks ($id = null)
    {
        // default return value
        $doc_links = array();

        try
        {
            // get the license data
            $this->includeForeignData = false;
            $license = $this->find(
                'first',
                array(
                    'conditions' => array('License.id' => $id),
                    'contain' => array(
                        'LicenseType',
                        'CurrentApplication' => array('ApplicationType')
                    )
                )
            );

            if (!$license)
            {
                throw new Exception ('Invalid license data.');
            }

            // load the foreign model
            $ForeignModel = ClassRegistry::init(
                sprintf(
                    '%s.%s',
                    $license['License']['foreign_plugin'],
                    $license['License']['foreign_obj']
                )
            );

            if (!$ForeignModel)
            {
                throw new Exception ('Invalid foreign model data.');
            }

            // does the foreign model actAs OutputDocument?
            if (array_key_exists('OutputDocuments.OutputDocument', $ForeignModel->actsAs))
            {
                // build the doc trigger
                $lic_type_abbr = $license['LicenseType']['abbr'];
                $app_state = strtolower(Inflector::slug($license['CurrentApplication']['ApplicationType']['label']));
                $trigger = sprintf('%s_%s', $lic_type_abbr, $app_state);

                // get the doc list based on trigger
                if ($trigger_docs = Configure::read(sprintf('OutputDocuments.triggers.%s', $trigger)))
                {
                    // loop documents and add to links array
                    foreach ($trigger_docs as $doc)
                    {
                        // get the doc type configs
                        $doc_conf = Configure::read(sprintf('OutputDocuments.docs.%s', $doc['type']));

                        // generate the doc links
                        foreach ($doc_conf['types'] as $type => $data)
                        {
                            // set the doc link params
                            $params = array(
                                'fp'         => $license['License']['foreign_plugin'],
                                'fo'         => $license['License']['foreign_obj'],
                                'fk'         => $license['License']['foreign_key'],
                                'trigger'    => $trigger,
                                'doc_type'   => $doc['type'],
                                'ext'        => $type,
                                'license_id' => $license['License']['id']
                            );

                            // generate the link and add it to the list
                            $doc_links[$doc['type']][$type] = $ForeignModel->buildDocUrl($params);
                        }
                    }
                }
            }
        }
        catch (Exception $e)
        {
            debug($e->getMessage());

            // set the links array to be emtpy
            $doc_links = array();
        }

        // return the array of links
        return $doc_links;
    }

    /**
     * getApplication method
     *
     * gets license record and nearly all associative data for application.
     *
     * @param int   $license_id expecting license record id
     * @param array $contain    contain array
     *
     * @return array
     * @access public
     */
    public function getApplication ($license_id = null, $contain = null)
    {
        // contains
        if (!$contain)
        {
            $contain = array(
                'LicenseNumber',
                'LicenseVariant' => array('Variant', 'Upload'),
                'LicenseExpireReason',
                'CurrentApplication' => array(
                    'QuestionAnswer',
                    'ScreeningAnswer',
                    'ApplicationStatus',
                    'ApplicationType',
                    'Reciprocal',
                    'ThirdPartyTest' => array('Upload'),
                ),
                'OpenApplication' => array(
                    'ApplicationType',
                    'ApplicationStatus',
                    'Reciprocal',
                    'ThirdPartyTest' => array('Upload'),
                ),
                'Application' => array(
                    'CurrentSubmission',
                    'OpenSubmission',
                    'ApplicationStatus',
                    'ApplicationSubmission',
                    'QuestionAnswer',
                    'ScreeningAnswer',
                    'ApplicationType',
                    'Reciprocal',
                    'ExamScore',
                    'LicenseGap',
                    'PaymentItem' => array('Payment'),
                    'ThirdPartyTest' => array('Upload'),
                    'order' => array('Application.id' => 'DESC'),
                    'limit' => 2
                ),
                'LicenseType' => array(
                    'Program',
                    'Question',
                    'ScreeningQuestion',
                    'CourseCatalog',
                    'Variant',
                ),
                'LicenseStatus',
                'ChildLicense' => array('LicenseType', 'LicenseStatus'),
                'ParentLicense' => array('LicenseType', 'LicenseStatus'),
                'Note' => array(
                    'Account',
                    'order' => array('Note.created' => 'desc')
                ),
                'Contractor',
                'InsuranceInformation' => array('Upload'),
            );
        }

        $license = $this->getLicenseById($license_id, $contain);

        // copy the application question answers to the license type questions section
        if (isset($license['LicenseType']['Question']))
        {
            foreach ($license['LicenseType']['Question'] as $q_key => $question)
            {
                foreach ($license['Application'][0]['QuestionAnswer'] as $a_key => $answer)
                {
                    if ($question['id'] == $answer['question_id'])
                    {
                        $license['LicenseType']['Question'][$q_key]['QuestionAnswer'] = $answer;
                    }
                }
            }
        }

        // copy the application screening answers to the license type screening questions section
        if (isset($license['LicenseType']['ScreeningQuestion']))
        {
            foreach ($license['LicenseType']['ScreeningQuestion'] as $q_key => $question)
            {
                foreach ($license['Application'][0]['ScreeningAnswer'] as $a_key => $answer)
                {
                    if ($question['id'] == $answer['screening_question_id'])
                    {
                        $license['LicenseType']['ScreeningQuestion'][$q_key]['ScreeningAnswer'] = $answer;
                    }
                }
            }
        }

        //debug($license); exit();
        return $license;
    }

    /**
     * getForeignObjLicense method
     *
     * @param int $foreign_key     expecting foreign record id
     * @param str $foreign_obj     expecting foreign model name
     * @param str $foreign_plugin  expecting foreign plugin name
     * @param int $license_type_id expecting license type record id
     *
     * @return array
     * @access public
     */
    public function getForeignObjLicense ($foreign_key = null, $foreign_obj = null, $foreign_plugin = null, $license_type_id = null)
    {
        // format the conditions array
        $conditions = array(
            'License.foreign_key' => $foreign_key,
            'License.foreign_obj' => $foreign_obj,
            'License.foreign_plugin' => $foreign_plugin
        );

        // add license type id condition
        if (preg_match('/^[1-9][0-9]*$/', $license_type_id))
        {
            $conditions['License.license_type_id'] = $license_type_id;
        }

        // return data if found
        return $this->find(
            'first',
            array(
                'conditions' => $conditions,
                'contain' => array('CurrentApplication')
            )
        );
    }

    /**
     * Checks if a license has a particular variant.
     *
     * @param int $license_id License ID
     * @param int $variant_id Variant ID
     *
     * @return boolean True or false
     * @access public
     */
    public function hasVariant($license_id, $variant_id)
    {
        $conditions = array(
            'LicenseVariant.license_id' => $license_id,
            'LicenseVariant.variant_id' => $variant_id,
        );

        return $this->LicenseVariant->find('count', compact('conditions')) > 0;
    }

    /**
     * Returns whether or not a license has variants.
     *
     * @param int $id License ID
     *
     * @return boolean True or false
     * @access public
     */
    public function hasVariants($id)
    {
        $conditions = array('LicenseVariant.license_id' => $id);
        return $this->LicenseVariant->find('count', compact('conditions')) > 0;
    }

    /**
     * _addForeignObjectData method
     *
     * Provided a data array fetch the related foreign object data
     *
     * @param array &$results expecting data array
     *
     * @return array returns the provided license data array w/ added foreign object data
     * @access private
     */
    private function _addForeignObjectData (&$results = array())
    {
        // loop the data
        foreach ($results as $inc => $result)
        {
            // validate the fields exist
            if (!GenLib::isData($result, 'License', array('foreign_obj', 'foreign_key')))
            {
                continue;
            }

            // define the foreign object and key
            $foreign_plugin = $result[$this->alias]['foreign_plugin'];
            $foreign_obj = $result[$this->alias]['foreign_obj'];
            $foreign_key = $result[$this->alias]['foreign_key'];

            // load the foreign model
            $foreign_model = ClassRegistry::init(sprintf('%s.%s', $foreign_plugin, $foreign_obj));



            // !!!!!!!!!! QUICK FIX !!!!!!!!!!!!!
            $foreign_model->resetAssociations();
            // the above line is to fix issues with missing associations
            // after the model was previously loaded by Auth (I think).
            // Reference line 128 in lib/Cake/Utility/ClassRegistry and
            // _duplicate method in the same file.
            //
            // I've created a trello bug ticket for this.



            // check that foreign model actsAs License
            if (!array_key_exists('Licenses.License', $foreign_model->actsAs))
            {
                throw new Exception(
                    sprintf(
                        __('Foreign model (%s.%s) does not act as a License.'),
                        $foreign_plugin, $foreign_obj
                    )
                );
            }

            // conditions
            $extras = array(
                'conditions' => array(sprintf('%s.id', $foreign_obj) => $foreign_key),
                'contain' => $foreign_model->getForeignObjContain()
            );

            // query for foreign data
            $foreign_obj_data = $foreign_model->find(
                'first',
                $extras
            );

            // format foreign obj data - nest related data under foreign_obj key
            $retVal = array($foreign_obj => $foreign_obj_data[$foreign_obj]);

            if ($foreign_obj_data)
            {
                foreach ($foreign_obj_data as $key => $data)
                {
                    if ($key != $foreign_obj)
                    {
                        $retVal[$foreign_obj][$key] = $data;
                    }
                }
            }

            $results[$inc] = array_merge($result, $retVal);
        }

        return $results;
    }

    /**
     * setStatus method
     *
     * @param string $status status string
     * @param int    $id     License ID
     *
     * @return true
     * @access public
     */
    public function setStatus($status = null, $id = null)
    {
        if (!$id || !$status)
        {
            throw new Exception('License ID or Status not found');
        }

        $license_status = $this->LicenseStatus->find(
            'first',
            array(
                'conditions' => array(
                    'LicenseStatus.status' => $status
                )
            )
        );
        $license = array();
        $license['License']['id'] = $id;
        $license['License']['license_status_id'] = $license_status['LicenseStatus']['id'];

        $this->create();
        if (!$this->save($license))
        {
            throw new Exception('Could not save license status.');
        }

        return true;
    }

    /**
     * activateLicenses
     *
     * @param array $event event array
     *
     * @access public
     *
     * @return void
     */
    public function activateLicenses ($event = array())
    {
        if (isset($event->data['account_id']))
        {
            $licenses = $this->getLicenseList(
                $event->data['account_id'],
                'Account',
                'Accounts'
            );

            $Roster = ClassRegistry::init('ContinuingEducation.CourseRoster');

            $roster = $Roster->details(
                $event->data['course_roster_id'],
                array(
                    'CourseSection' => array(
                        'CourseCatalog'
                    )
                )
            );

            // loop the licenses
            foreach ($licenses as $license)
            {
                $catalog_ids = Hash::extract($license, 'LicenseType.CourseCatalog.{n}.id');

                if ($license['License']['license_status_id'] == 4
                    && $license['License']['expire_date'] >= date('Y-m-d 00:00:00')
                    && in_array($roster['CourseSection']['CourseCatalog']['id'], $catalog_ids)
                )
                {
                    try
                    {
                        if ($this->setStatus('Active', $license['License']['id']))
                        {
                            $this->Note->sysNote(
                                CakeSession::read("Auth.User.id"),
                                'Licenses',
                                'License',
                                $license['License']['id'],
                                sprintf('Activated by %s on %s', CakeSession::read("Auth.User.label"), date('Y-m-d'))
                            );

                            // get the license data
                            $license = $this->find(
                                'first',
                                array(
                                    'conditions' => array('License.id' => $license['License']['id']),
                                    'contain' => array('LicenseType')
                                )
                            );

                            if (!$license['Account']['no_mail'])
                            {
                                $event->data['queue_docs'][] = array(
                                    'fp'         => 'Accounts',
                                    'fo'         => 'Account',
                                    'fk'         => $license['Account']['id'],
                                    'trigger'    => $license['LicenseType']['abbr'].'_renewal',
                                    'license_id' => $license['License']['id']
                                );
                            }
                        }
                        else
                        {
                            throw new Exception(sprintf('Failed to activate license %s.', $license['License']['id']));
                        }
                    }
                    catch (Exception $e)
                    {
                        throw $e;
                    }
                }
            }
        }

        $this->dispatch('Model-CourseRoster-activateLicenses', $event->data);

        return true;
    }

    /**
     * expireLicense method
     *
     * @param int    $id         License ID
     * @param string $user_label User label
     *
     * @return true
     * @access public
     */
    public function expireLicense($id = null, $user_label = null)
    {
        try
        {
            $user_label = ($user_label ? $user_label : CakeSession::read("Auth.User.label"));

            if ($this->setStatus('Expired', $id))
            {
                $this->Note->sysNote(
                    null,
                    'Licenses',
                    'License',
                    $id,
                    sprintf('Expired by %s on %s.', $user_label, date('Y-m-d'))
                );

                return true;
            }
            throw new Exception(sprintf('Failed to expire license %s.', $id));
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id License id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        $license = $this->findById($id, array('foreign_plugin', 'foreign_obj', 'foreign_key'));

        $ForeignModel = ClassRegistry::init(sprintf('%s.%s', $license['License']['foreign_plugin'], $license['License']['foreign_obj']));

        return $ForeignModel->isOwnerOrManager($license['License']['foreign_key']);
    }

    /**
     * setManuallyEdited method
     *
     * flags the license as edited manually
     *
     * @param int $id License id
     *
     * @return bool
     */
    public function setManuallyEdited($event)
    {
        // double check out inputs
        if (!isset($event->data['license_id']))
        {
            throw new Exception('Event did not pass license id.');
        }
        else
        {
            // set the manually edited field
            $this->id = $event->data['license_id'];
            if (!$this->saveField('manually_edited', 1))
            {
                throw new Exception(sprintf('Failed to set license %s as manually edited.', $id));
            }

            return true;
        }

        return false;
    }

    /**
     * updateLabel method
     *
     * updates the label field of the license
     *
     * @param event $event the event object
     *
     * @return void
     */
    public function updateLabel($event)
    {
        if (isset($event->data['License.label']))
        {
            $License = ClassRegistry::init('Licenses.License');

            $License->id = $event->data['License.id'];

            $License->saveField('label', $event->data['License.label']);
        }
    }

    /**
     * openConvertedApplications method
     *
     * checks for any open applications that were created from a converted license
     *
     * @param int $license_id the id of the license
     *
     * @return boolean true/false if open applications were found
     */
    public function openConvertedApplications($license_id=null)
    {
        // find any applications that were converted by the provided license
        $apps = $this->Application->find(
            'list',
            array(
                'conditions' => array(
                    'Application.converted_license_id' => $license_id)
            )
        );


        if ($apps)
        {
            return true;
        }

        return false;
    }

    /**
     * getOpenApplication method
     *
     * checks for any open application
     *
     * @param int $license_id the id of the license
     *
     * @return array or false
     */
    public function getOpenApplication($license_id = null)
    {
        // find any applications that were converted by the provided license
        return $this->Application->find(
            'first',
            array(
                'conditions' => array(
                    'Application.license_id' => $license_id,
                    'Application.open' => true)
            )
        );
    }

    public function getCurrentApplicationId($id)
    {
        $this->create();
        $this->id = $id;
        return $this->field('application_id');
    }
}
