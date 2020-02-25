<?php
/**
 * Account model
 *
 * Extends AccountAppModel. Responsible for managing account data.
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class Account extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Account';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Order
     *
     * @var array
     * @access public
     */
    public $order = array('Account.label' => 'ASC');

    /**
     * Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Acl' => array('type' => 'requester'),
        'Licenses.License' => array(
            'license' => array(
                'contain' => array(
                    'PrimaryAddress',
                    'Address',
                    'WorkExperience',
                    'PracticalWorkExperience' => array('PracticalWorkExperienceType'),
                    'PracticalWorkPercentage' => array('PracticalWorkPercentageType'),
                    'OtherLicense',
                    'EducationDegree' => array('Degree', 'Upload'),
                    'Document',
                    'Reference' => array('Contact', 'Address'),
                    'AccountPhoto',
                    'InsuranceInformation' => array('Upload'),
                ),
            ),
            'entity' => array(
                'contain' => array(
                    'CompletedLicense' => array(
                        'LicenseType',
                        'LicenseStatus'
                    ),
                    'OpenLicense' => array('LicenseType', 'OpenApplication' => array('ApplicationType', 'ApplicationStatus')),
                    'Address',
                    'AccountPhoto',
                    'ManagedFirm' => array('PrimaryAddress'),
                    'ManagedTrainingProvider' => array('PrimaryAddress'),
                    'InstructorCourseSection' => array(
                        'TrainingProvider',
                        'Address'
                    ),
                    'EducationDegree' => array('Degree', 'Upload'),
                    'WorkExperience',
                    'Document',
                    'CourseRoster' => array(
                        'CourseSection' => array('CourseCatalog', 'TrainingProvider'),

                    )
                )
            ),
        ),
        'Searchable.Searchable',
        'OutputDocuments.OutputDocument',
        'Uploads.Upload' => array(
            'Document' => array(
                'save_location' => 'files',
                'allowed_types' => array('image/jpeg', 'application/pdf')
            ),
            'AccountPhoto' => array(
                'save_location' => 'files/accounts/photos',
                'allowed_types' => array('image/jpeg', 'application/gif'),
                'association' => 'hasOne',
            ),
        ),
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'username' => array(
            'rule' => array('isUnique'),
            'message' => 'This username has already been taken.',
            'allowEmpty' => true
        ),
        'first_name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'last_name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'ssn' => array(
            'uniquessn' => array(
                'rule' => 'uniqueSSN',
                'message' => 'This SSN is already in use.',
            ),
            'validssn' => array(
                'rule' => array('ssn', null, 'us'),
                'message' => 'Invalid SSN. Please verify.',
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'ssn_last_four' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );

    /**
     * hasOne
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'PrimaryAddress' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'PrimaryAddress.foreign_obj' => 'Account',
                'PrimaryAddress.primary_flag' => 1
            )
        ),
        'AccountPhoto' => array(
            'className' => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'AccountPhoto.foreign_obj' => 'Account',
                'AccountPhoto.identifier' => 'AccountPhoto'
            )
        ),
    );

    /**
     * belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Group' => array(
            'className' => 'Accounts.Group',
            'foreignKey' => 'group_id',
        ),
        'Instructor' => array(
            'className' => 'ContinuingEducation.Instructor',
            'foreignKey' => 'account_id'
        )
    );

    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Address.foreign_obj' => 'Account'),
        ),
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array('License.foreign_obj' => 'Account')
        ),
        'OpenLicense' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'OpenLicense.foreign_plugin' => 'Accounts',
                'OpenLicense.foreign_obj' => 'Account',
                '(SELECT COUNT(applications.id) FROM applications WHERE applications.license_id = OpenLicense.id AND applications.open)'
            )
        ),
        'CompletedLicense' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'CompletedLicense.foreign_plugin' => 'Accounts',
                'CompletedLicense.foreign_obj' => 'Account',
                'CompletedLicense.license_status_id != (SELECT license_statuses.id FROM license_statuses WHERE license_statuses.status = "Incomplete" LIMIT 1)',
            )
        ),
        'WorkExperience' => array(
            'className' => 'Accounts.WorkExperience',
            'foreignKey' => 'account_id',
            'order' => array('WorkExperience.start_date' => 'desc'),
        ),
        'PracticalWorkExperience' => array(
            'className'  => 'Accounts.PracticalWorkExperience',
            'foreignKey' => 'account_id',
        ),
        'PracticalWorkPercentage' => array(
            'className'  => 'Accounts.PracticalWorkPercentage',
            'foreignKey' => 'account_id',
        ),
        'OtherLicense' => array(
            'className' => 'Accounts.OtherLicense',
            'foreignKey' => 'foreign_key',
            'conditions' => array('OtherLicense.foreign_obj' => 'Account')
        ),
        'CourseRoster' => array(
            'className' => 'ContinuingEducation.CourseRoster',
            'foreignKey' => 'account_id',
        ),
        'EducationDegree' => array(
            'className' => 'Accounts.EducationDegree',
            'foreignKey' => 'account_id',
        ),
        'EducationCertificate' => array(
            'className' => 'Accounts.EducationCertificate',
            'foreignKey' => 'account_id',
        ),
        'Note' => array(
            'className' => 'Notes.Note',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Note.foreign_obj' => 'Account')
        ),
        'Document' => array(
            'className' => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Document.foreign_obj' => 'Account',
                'Document.identifier' => 'Document'
            )
        ),
        'Reference' => array(
            'className'  => 'Accounts.Reference',
            'foreignKey' => 'account_id',
        ),
        'InsuranceInformation' => array(
            'className'  => 'Accounts.InsuranceInformation',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'InsuranceInformation.foreign_plugin' => 'Accounts',
                'InsuranceInformation.foreign_obj' => 'Account',
            )
        ),
        'ManagedFirm' => array(
            'className' => 'Firms.Firm',
            'foreignKey' => false,
            'finderQuery' => '
                SELECT *
                FROM firms AS ManagedFirm
                JOIN managers as Manager
                    ON Manager.foreign_key = ManagedFirm.id
                    AND Manager.foreign_obj = "Firm"
                    AND Manager.account_id = {$__cakeID__$}
            ',
        ),
        'ManagedTrainingProvider' => array(
            'className' => 'ContinuingEducation.TrainingProvider',
            'foreignKey' => false,
            'finderQuery' => '
                SELECT *
                FROM training_providers AS ManagedTrainingProvider
                JOIN managers as Manager
                    ON Manager.foreign_key = ManagedTrainingProvider.id
                    AND Manager.foreign_obj = "TrainingProvider"
                    AND Manager.account_id = {$__cakeID__$}
            ',
        ),
        'InstructorAssignment' => array(
            'className' => 'ContinuingEducation.InstructorAssignment',
            'foreignKey' => 'account_id',
        ),
        'InstructorCourseSection' => array(
            'className' => 'ContinuingEducation.CourseSection',
            'foreignKey' => 'account_id',
        )
    );

    /**
     * beforeSave Callback
     *
     * @param array $options Options
     *
     * @return boolean
     * @access public
     */
    public function beforeSave($options = array())
    {
        // set the account label value
        if (GenLib::isData($this->data, 'Account', array('first_name', 'last_name')))
        {
            $middle_initial = '';
            if (isset($this->data['Account']['middle_initial']))
            {
                $middle_initial = preg_match('/[a-z]/i', $this->data['Account']['middle_initial'])
                    ? sprintf(' %s.', $this->data['Account']['middle_initial'])
                    : '';
            }

            $label = sprintf(
                '%s, %s%s',
                $this->data['Account']['last_name'],
                $this->data['Account']['first_name'],
                $middle_initial
            );
            $this->data['Account']['label'] = $label;
        }

        // encrypt the SSN
        if (isset($this->data['Account']['ssn']))
        {
            $this->data['Account']['ssn_last_four'] = substr($this->data['Account']['ssn'], -4);
            $this->data['Account']['ssn'] = GenLib::encryptString(
                preg_replace('/[^0-9]/', '', $this->data['Account']['ssn'])
            );
        }

        return parent::beforeSave($options);
    }

    /**
     * afterSave callback
     *
     * @param bool $created true/false if record was inserted
     *
     * @return void
     */
    function afterSave($created)
    {
        // Update the ARO for the ACL to have an alias because cake isn't smart enough to do that on it's own
        $this->updateAroAlias();
    }

    /**
     * getAuthUserData
     *
     * @param int $account_id account id to login to system
     *
     * @return array array of account/group/program data
     * @access public
     */
    public function getAuthUserData ($account_id = null)
    {
        // get the auth user data
        $data = $this->find(
            'first',
            array(
                'fields' => array(
                    'Account.id',
                    'Account.first_name',
                    'Account.middle_initial',
                    'Account.last_name',
                    'Account.label',
                    'Account.enabled',
                    'Account.group_id',
                ),
                'contain' => array(
                    'Group' => array(
                        'fields' => array(
                            'Group.id',
                            'Group.label',
                            'Group.admin',
                            'Group.enabled',
                            'Group.program_id'
                        ),
                    ),
                ),
                'conditions' => array(
                    'Account.id' => $account_id,
                ),
            )
        );

        // format the data for use in Auth->user calls
        $auth_user_data = $data['Account'];
        $auth_user_data['Group'] = $data['Group'];

        return $auth_user_data;
    }

    /**
     * Validates that SSN is unique
     *
     * @param array  $check         Check
     * @param string $compare_field Compare field
     *
     * @return boolean True if SSN is unique, false otherwise
     * @access public
     */
    public function uniqueSSN($check, $compare_field)
    {
        if (! empty($check))
        {
            $secret = GenLib::encryptString(preg_replace('/[^0-9]/', '', $check['ssn']));
            return $this->find('count', array('conditions' => array('Account.ssn' => $secret))) == 0;
        }
    }

    /**
     * getGroups method
     *
     * @return array returns a list of permission groups
     * @access public
     */
    public function getGroups ()
    {
        return $this->Group->find('list');
    }

    /**
     * afterApprove method
     *
     * @param array  $license expecting license/application data array
     * @param string $trigger output document trigger (default: false)
     *
     * @return boolean|array returns false or new account data array
     * @access public
     */
    public function afterApprove($license = array(), $trigger = false)
    {
        try
        {
            $trigger = sprintf('%s_%s', $license['LicenseType']['abbr'], $trigger);

            // produce the output documents
            $this->afterApproveOutputDocs($license, $trigger);
        }
        catch (Exception $e)
        {
            throw $e;
        }

        return true;
    }

    /**
     * afterApproveOutputDocs method
     *
     * @param array  $license expecting license/application data array
     * @param string $trigger output document trigger (default: false)
     *
     * @return true on sucess
     * @access private
     */
    private function afterApproveOutputDocs($license = array(), $trigger = false)
    {
        // validate the license has associated account data
        if (!GenLib::isData($license, 'Account', array('id')))
        {
            $this->log('Invalid license data. Account data not found in license data array.');
            throw new Exception(__('Failed to approve license'));
        }

        // see if the account has the no_mail flag set
        if (!$license['Account']['no_mail'])
        {
            // queue up the output documents.
            if (array_key_exists('OutputDocuments.OutputDocument', $this->actsAs))
            {

                // queue up the output documents
                $outputDocumentParams = array(
                    'fp'         => 'Accounts',
                    'fo'         => 'Account',
                    'fk'         => $license['Account']['id'],
                    'trigger'    => $trigger,
                    'license_id' => $license['License']['id'],
                    'license'    => $license
                );

                // queue the documents
                if (!$this->queueDocs($outputDocumentParams))
                {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * accountLicenseData method
     *
     * @param array $params all this->params[named] from the OutputDoc controller
     *
     * @return array $data an array of correctly formatted data for use in output doc
     * @access public
     */
    public function accountLicenseData($params = array())
    {
        try
        {
            // contain data
            $contain = array(
                'LicenseNumber',
                'LicenseVariant' => array('Variant', 'Upload'),
                'Application' => array(
                    'order' => array('Application.id' => 'DESC'),
                    'limit' => 2,
                    'ApplicationType',
                    'Reciprocal',
                    'LicenseGap',
                    'PaymentItem'
                ),
                'LicenseType',
            );

            // get the account and license info
            $license = $this->License->getApplication($params['license_id'], $contain);
            //print "<pre>"; print_r($license); print "</pre>"; exit;

            $license['refresher_date'] = $this->CourseRoster->getCourseRefresher($license['License']['id']);

            // get the next 'renewal' application fee
            $feeModel = ClassRegistry::init('Payments.Fee');
            $fee = $feeModel->find(
                'first',
                array('conditions' =>
                    array(
                        'fee_key' => sprintf('%s_renewal', strtolower($license['LicenseType']['abbr']))
                    )
                )
            );

            $license['renewal_fee'] = ($fee ? $fee['Fee']['fee'] : null);

            return $license;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * addAccount method
     *
     * @param array $data expecting cake data object array
     *
     * @return bool|array returns false or new account data array
     * @access public
     */
    public function addAccount($data = array())
    {
        try
        {
            // get the last four of the ssn
            $data['Account']['ssn_last_four'] = substr($data['Account']['ssn'], -4);

            // set enabled to true
            $data['Account']['enabled'] = true;

            // attempt to insert the data
            if ($this->add($data))
            {
                return $this->getAccountById($this->getLastInsertID());
            }
            else
            {
                throw new Exception(__('Account could not be created.'));
            }
        }
        catch (Exception $e)
        {
            // fail
            throw $e;
        }
    }

    /**
     * updateAccount method
     *
     * @param array $data expecting cake data array
     *
     * @return bool|array returns false or new account data
     * @access public
     */
    public function updateAccount($data = array())
    {
        // get the last four of the ssn
        if (! empty($data['Account']['ssn']))
        {
            $data['Account']['ssn_last_four'] = substr($data['Account']['ssn'], -4);
        }
        else
        {
            unset ($data['Account']['ssn']);
        }

        // attempt to update the data
        if ($this->edit($data))
        {
            return $this->getAccountById($data['Account']['id']);
        }

        // fail
        throw new Exception(__('Account could not be updated.'));
    }

    /**
     * getAccountEmailById method
     *
     * @param int $id expecting account ID
     *
     * @return str
     * @access public
     */
    public function getAccountEmailById($id = null)
    {
        // attempt to pull the account record
        if ($acct = $this->getAccountById($id))
        {
            return $acct['Account']['email'];
        }

        return false;
    }

    /**
     * getAccountByUsername method
     *
     * @param str $username expecting account username
     *
     * @return array
     * @access public
     */
    public function getAccountByUsername($username = null)
    {
        return $this->find('first', array('conditions' => array('username' => $username)));
    }

    /**
     * getAccountByLegacyID method
     *
     * @param int   $legacy_id legacy professional id
     * @param array $contain   contain array
     *
     * @return array
     * @access public
     */
    public function getAccountByLegacyID($legacy_id = null, $contain = array())
    {
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    sprintf(
                        'Account.legacy_id REGEXP "(^%s$|^%s,|, %s,|, %s$)"',
                        $legacy_id,
                        $legacy_id,
                        $legacy_id,
                        $legacy_id
                    )
                ),
                'contain' => $contain
            )
        );
    }

    /**
     * getAccountBySsn method
     *
     * @param string $ssn      expecting account ssn
     * @param array  $excludes exclude conditions
     *
     * @return array
     * @access public
     */
    public function getAccountBySSN($ssn = null, $excludes = array())
    {
        $conditions = array('Account.ssn' => GenLib::encryptString(preg_replace('/[^0-9]/', '', $ssn)));

        if (! empty($excludes))
        {
            $conditions['NOT'] = $excludes;
        }

        return $this->find('first', compact('conditions'));
    }

    /**
     * getAccountList method
     *
     * Returns an list formatted array of account records
     * given an array of IDs. For use in creating select
     * lists and such.
     *
     * @param array $ids expecting array of account IDs
     *
     * @return array
     * @access public
     */
    public function getAccountList($ids = array())
    {
        if (!is_array($ids) || !count($ids))
        {
            return array();
        }

        return $this->find(
            'list',
            array(
            'conditions' =>
            array(sprintf('Account.id IN (%s)', implode(',', $ids))),
            'fields' => 'Account.label', 'oder' =>
            array('Account.id ASC'))
        );
    }

    /**
     * getAccountById method
     *
     * @param int $id expecting account ID
     *
     * @return array
     * @access public
     */
    public function getAccountById($id = null)
    {
        return $this->findById($id);
    }

    /**
     * getAccountsById method
     *
     * Use this for multiple or has-many relations.
     *
     * @param int|array $ids expecting array of account IDs
     *
     * @return array
     * @access public
     */
    public function getAccountsById($ids = array())
    {
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    sprintf('Account.id IN (%s)', implode(',', $ids))
                )
            )
        );
    }

    /**
     * Retrieve all the license assocaited with the current account. Overrides AppModel::details();
     *
     * @param int $id      Account ID
     * @param int $contain set to null
     *
     * @return array Account ID
     * @access public
     */
    public function details($id, $contain = null)
    {
        if (empty($contain))
        {
            $contain = array(
                'WorkExperience',
                'PracticalWorkExperience' => array('PracticalWorkExperienceType'),
                'PracticalWorkPercentage' => array('PracticalWorkPercentageType'),
                'License' => array('LicenseType', 'LicenseStatus'),
                'CourseRoster' => array('CourseSection' => array('TrainingProvider')),
                'Document',
                'EducationDegree',
                'Reference' => array('Contact', 'Address'),
                'InsuranceInformation',
            );
        }
        return parent::details($id, $contain);
    }

    /**
     * Returns whether or not an account has a photo.
     *
     * @param int $id Account ID
     *
     * @return boolean True or false
     * @access public
     */
    public function hasPhoto($id)
    {
        if (! $this->exists($id))
        {
            return false;
        }

        $contain = array('AccountPhoto');
        $details = $this->details($id, $contain);

        return ! empty($details['AccountPhoto']);
    }

    /**
     * Copies account information over to another account.
     *
     * @param array $origUser  Original user
     * @param int   $newUserID New user ID to copy original user information to
     *
     * @return boolean True or false
     * @access public
     *
     * @throws Exception If specified original user account doesn't exist
     * @throws Exception If specified new user account doesn't exist
     */
    public function copyAccountInfo($origUser, $newUserID)
    {
        if (! $this->exists($origUser['id']))
        {
            throw new Exception(__('Original user not found.'));
        }

        if (! $this->exists($newUserID))
        {
            throw new Exception(__('New user not found.'));
        }

        try
        {
            // disable username and SSN validation as it'll be done from the user's account page
            unset ($this->validate['username']);
            unset ($this->validate['ssn']);

            $data = array(
                'Account' => array(
                    'id'                   => $newUserID,
                    'username'             => $origUser['username'],
                    'email'                => $origUser['email'],
                    'perjury_acknowledged' => true,
                    'last_login'           => date('Y-m-d H:i:s'),
                ),
            );

            return $this->save($data);
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * parentNode method
     *
     * This method is required by ACL
     *
     * @return mixed
     * @access public
     */
    public function parentNode()
    {
        if (! $this->id && empty($this->data))
        {
            return null;
        }

        if (isset($this->data['Account']['group_id']))
        {
            $groupId = $this->data['Account']['group_id'];
        }
        else
        {
            $groupId = $this->field('group_id');
        }

        if (! $groupId)
        {
            return null;
        }
        else
        {
            return array('Group' => array('id' => $groupId));
        }
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id Account id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        if (CakeSession::read("Auth.User.id") != $id)
        {
            return false;
        }

        return true;
    }
}
