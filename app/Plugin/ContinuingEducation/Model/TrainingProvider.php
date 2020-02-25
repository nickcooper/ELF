<?php
/**
 * TrainingProvider Model
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class TrainingProvider extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'TrainingProvider';

    /**
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Model Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Searchable.Searchable',
        'Licenses.License' => array(
            'license' => array(
                'contain' => array(
                    'Manager' => array('Account'),
                    'PrimaryAddress',
                    'Address',
                    'InstructorAssignment' => array('Account'),
                    'Course' => array('CourseCatalog'),
                    'CourseLocation' => array('Address')
                ),
            ),
            'entity' => array(
                'contain' => array(
                    'CompletedLicense' => array(
                        'LicenseType',
                        'LicenseStatus'
                    ),
                    'OpenLicense' => array('LicenseType', 'OpenApplication' => array('ApplicationType', 'ApplicationStatus')),
                    'Course' => array('CourseCatalog'),
                    'Address',
                    'Manager' => array('Account'),
                    'CourseLocation' => array('Address'),
                    'CourseSection' => array('Account', 'Address', 'order' => array('CourseSection.modified' => 'desc', 'CourseSection.start_date' => 'desc')),
                    'InstructorAssignment' => array('Account')
                )
            ),
        ),
        'OutputDocuments.OutputDocument',
    );

    /**
     * belongsTo Associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'id',
            'conditions' => array('License.foreign_obj' => 'TrainingProvider')));

    /**
     * hasMany Associations
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'OpenLicense' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'OpenLicense.foreign_plugin' => 'ContinuingEducation',
                'OpenLicense.foreign_obj' => 'TrainingProvider',
                '(SELECT COUNT(applications.id) FROM applications WHERE applications.license_id = OpenLicense.id AND applications.open)'
            )
        ),
        'CompletedLicense' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'CompletedLicense.foreign_plugin' => 'ContinuingEducation',
                'CompletedLicense.foreign_obj' => 'TrainingProvider',
                'CompletedLicense.license_status_id != (SELECT license_statuses.id FROM license_statuses WHERE license_statuses.status = "Incomplete" LIMIT 1)',
            )
        ),
        'Manager' => array(
            'className' => 'Accounts.Manager',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Manager.foreign_obj' => 'TrainingProvider')
        ),
        'CourseLocation' => array(
            'className' => 'ContinuingEducation.CourseLocation',
            'foreignKey' => 'training_provider_id'
        ),
        'Course' => array(
            'className' => 'ContinuingEducation.Course',
            'foreignKey' => 'training_provider_id'
        ),
        'CourseSection' => array(
            'className' => 'ContinuingEducation.CourseSection',
            'foreignKey' => 'training_provider_id'
        ),
        'InstructorAssignment' => array(
            'className' => 'ContinuingEducation.InstructorAssignment',
            'foreignKey' => 'training_provider_id',
        ),
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Address.foreign_obj' => 'TrainingProvider')
        ),
    );

    /**
     * hasOne Associations
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'PrimaryAddress' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'PrimaryAddress.foreign_obj'  => 'TrainingProvider',
                'PrimaryAddress.primary_flag' => 1,
            )
        )
    );

    /**
     * validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'label' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter a training provider name',
            ),
        ),
        'abbr' => array(
            'required' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Please enter an abbreviation'
            ),
            'length' => array(
                'rule'    => array('between', 1, 5),
                'message' => 'Training provider abbreviations must be between 1 and 5 characters long',
            ),
            'unique' => array(
                'rule'    => 'isUnique',
                'message' => 'This abbreviation is already in use',
            ),
        ),
        'training_plan' => array(
            'rule' => 'notEmpty',
            'message' => 'Please enter your training plan'
        ),
        'equipment' => array(
            'rule' => 'notEmpty',
            'message' => 'Please enter equipment information'
        ),
    );

    /**
     * Callback that is called after license approval through actsAs License
     *
     * @param array  $data    Data for the record that was approved
     * @param string $trigger output document trigger (default: false)
     *
     * @return boolean
     * @access public
     */
    public function afterApprove($data, $trigger = false)
    {
        try
        {
            $training_provider_id = $data['License']['foreign_key'];

            // Throw an exception if it's not the right foreign object
            if ($data['License']['foreign_obj'] != $this->alias)
            {
                throw new Exception(__('License is not for a training provider'));
            }

            // Throw an exception if the training provider doesn't exist
            if (! $this->exists($training_provider_id))
            {
                throw new Exception(__('Invalid Training Provider'));
            }

            // Approve Courses for training provider
            $this->Course->approveCoursesForTrainingProvider(
                $training_provider_id,
                $data['Application'][0]['submit_paid_date'],
                $data['Application'][0]['ApplicationType']['label']
            );

            // Approve InstructorAssignment for training provider
            $this->InstructorAssignment->approveInstructorsForTrainingProvider(
                $training_provider_id,
                $data['Application'][0]['submit_paid_date']
            );

            // check if the firm has the no_mail flag set
            if (!$data['TrainingProvider']['no_mail'])
            {
                // queue output document
                if (!$trigger)
                {
                    $trigger = strtolower(Inflector::slug($data['Application'][0]['ApplicationType']['label']));
                }

                $outputDocumentParams = array(
                    'fp'         => $data['License']['foreign_plugin'],
                    'fo'         => $data['License']['foreign_obj'],
                    'fk'         => $training_provider_id,
                    'trigger'    => sprintf('%s_%s', $data['LicenseType']['abbr'], $trigger),
                    'license_id' => $data['License']['id']
                );

                $this->queueDocs($outputDocumentParams);
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }

        return true;
    }

    /**
     * Get courses that a training provider is allowed to teach
     *
     * @param int $training_provider_id Id for the training provider
     *
     * @return array
     */
    public function getCourses($training_provider_id)
    {
        return $this->Course->find(
            'all', array(
                'contain' => array(
                    'CourseCatalog'
                ),
                'conditions' => array(
                    'Course.training_provider_id' => $training_provider_id,
                )
            )
        );
    }

    /**
     * Get instructors for a specific training provider
     *
     * @param int $training_provider_id Id of training provider record
     *
     * @return array
     */
    public function getInstructors($training_provider_id)
    {
        $ids = $this->InstructorAssignment->find(
            'list', array(
                'conditions' => array(
                    'training_provider_id' => $training_provider_id,
                ),
                'fields' => array(
                    'id', 'id',
                ),
            )
        );

        return $this->InstructorAssignment->Account->find(
            'all', array(
                'conditions' => array(
                    'Instructor.id' => $ids,
                ),
            )
        );
    }

    /**
     * Get the location information for a training provider
     *
     * @param int $training_provider_id Id of training provider record
     *
     * @return array
     */
    public function getLocation($training_provider_id)
    {
        return $this->PrimaryAddress->find(
            'all', array(
                'conditions' => array(
                    'PrimaryAddress.foreign_plugin' => 'ContinuingEducation',
                    'PrimaryAddress.foreign_obj'    => 'TrainingProvider',
                    'PrimaryAddress.foreign_key'    => $training_provider_id
                ),
            )
        );
    }

    /**
     * getTrainingProviderByLegacyID method
     *
     * @param int $legacy_id legacy training provider id
     *
     * @return array
     * @access public
     */
    public function getTrainingProviderByLegacyID($legacy_id = null)
    {
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    sprintf(
                        'TrainingProvider.legacy_id REGEXP "(^%s$|^%s,|, %s,|, %s$)"',
                        $legacy_id,
                        $legacy_id,
                        $legacy_id,
                        $legacy_id
                    )
                )
            )
        );
    }

    /**
     * Get the license id for a training provider
     *
     * @param int $id training provider id
     *
     * @return int
     */
    public function getTrainingProviderLicenseId($id)
    {
        $license = $this->License->find(
            'first',
            array(
                'contain' => false,
                'conditions' => array(
                    'License.foreign_plugin' => 'ContinuingEducation',
                    'License.foreign_obj'    => 'TrainingProvider',
                    'License.foreign_key'    => $id,
                )
            )
        );

        $retval = false;

        if (!empty($license['License']['id']))
        {
            $retval = $license['License']['id'];
        }

        return $retval;
    }

    /**
     * Retrieves data to send to an output document.
     *
     * @param array $params Parameters
     *
     * @return array Output document data
     * @access public
     *
     * @todo Check document templates for correct information
     */
    public function getOutputDocumentData($params = array())
    {
        // get the license data
        $license = $this->License->getApplication($this->getTrainingProviderLicenseId($params['fk']));

        // get the application fees
        $fees = ClassRegistry::init('Fee')->find(
            'list',
            array(
                'fields' => array('fee_key', 'fee'),
                'conditions' => array('Fee.fee_key  LIKE "%train_%"')
            )
        );

        // add the fees to the license data
        $license['fees'] = $fees;

        return $license;
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id Firm id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        return $this->Manager->hasAny(
            array(
                'Manager.foreign_plugin' => 'ContinuingEducation',
                'Manager.foreign_obj' => 'TrainingProvider',
                'Manager.foreign_key' => $id,
                'Manager.account_id' => CakeSession::read("Auth.User.id")
            )
        );
    }
}
