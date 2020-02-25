<?php
/**
 * Abatement model
 *
 * @package Abatements.Model
 * @author  Iowa Interactive, LLC.
 */
class Abatement extends AbatementsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Abatement';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Model behaviors.
     *
     * @var Array
     * @access public
     */
    public $actsAs = array(
        'Searchable.Searchable',
        'OutputDocuments.OutputDocument'
    );

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'AbatementStatus' => array(
            'className'  => 'Abatements.AbatementStatus',
            'foreignKey' => 'abatement_status_id',
        ),
        'DwellingType' => array(
            'className'  => 'Abatements.DwellingType',
            'foreignKey' => 'dwelling_type_id',
        ),
        'License' => array(
            'className'  => 'Licenses.License',
            'foreignKey' => 'license_id',
        ),
        'Firm' => array(
            'className'  => 'Firms.Firm',
            'foreignKey' => 'firm_id',
        ),
    );

    /**
     * hasOne associations
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
       'PropertyOwner' => array(
            'className'  => 'Contact',
            'foreignKey' => 'foreign_key',
            'conditions' => array('PropertyOwner.foreign_obj' => 'Abatement'),
        ),
        'Address' => array(
            'className'  => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Address.foreign_plugin' => 'Abatements',
                'Address.foreign_obj' => 'Abatement',
            )
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'AbatementSubmission' => array(
            'className'   => 'Abatements.AbatementSubmission',
            'foreign_key' => 'abatement_id',
            'order'       => array('AbatementSubmission.date' => 'ASC'),
        ),
        'AbatementPhase' => array(
            'className'   => 'Abatements.AbatementPhase',
            'foreign_key' => 'abatement_id',
            'order'       => array('AbatementPhase.begin_date' => 'ASC'),
        ),
        'Note' => array(
            'className'  => 'Notes.Note',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Note.foreign_obj' => 'Abatement')
        ),
        'PropertyOccupant' => array(
            'className'  => 'Contact',
            'foreignKey' => 'foreign_key',
            'conditions' => array('PropertyOccupant.foreign_obj' => 'AbatementPropertyOccupant'),
        ),
    );

    /**
     * Validation rules.
     *
     * @var array
     * @access public
     */
    public $validate = array(
        /*'date_received' => array(
            'rule' => array('date', 'ymd'),
            'message' => 'Please specify a valid request received date in YYYY-MM-DD format',
            'allowEmpty' => false,
        ),
        'dwelling_year_built' => array(
            'rule' => 'numeric',
            'message' => 'Please specify a valid year.',
            'allowEmpty' => false,
        ),
        'work_description' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Work description is required',
            ),
        ),
        'dwelling_type_id' => array(
            'notempty' => array(
                'rule' => 'notempty',
                'message' => 'Please select a dwelling type.',
            )
        ),*/
    );

    /**
     * Injects licensee information into resultset, including account information.
     *
     * @param array $results Results from find
     * @param bool  $primary primary flag. If false the data is associated not primary
     *
     * @return array Results with licensee information included
     * @access public
     */
    public function afterFind($results = array(), $primary = false)
    {
        $accountObj = ClassRegistry::init('Accounts.Account');

        foreach ($results as $idx => $result)
        {
            if (GenLib::isData($result, 'Abatement', array('license_id')))
            {
                // grab license
                if ($license = $this->License->findById($result['Abatement']['license_id']))
                {
                    if (! isset($result['License']))
                    {
                        $result['License'] = $license['License'];
                    }

                    // grab account
                    if ($license['License']['foreign_obj'] = 'Account')
                    {
                        $account = $accountObj->findById($license['License']['foreign_key']);
                        $result['License']['Account'] = $account['Account'];
                        unset ($account);
                    }

                    unset ($license);

                    $results[$idx] = $result;
                }
                unset ($result);
            }
        }

        unset ($accountObj);

        return parent::afterFind($results, $primary);
    }

    /**
     * Add an abatement.
     *
     * @param id $license_id       expecting license record id
     * @param id $dwelling_type_id expecting abetement dwelling type id
     *
     * @return boolean True or false
     * @throws Exception If abatement could not be saved to the database.
     * @access public
     */
    public function addAbatement($license_id = null, $dwelling_type_id = null)
    {
        // get a license and firm data
        if (!$license = $this->License->getApplication($license_id))
        {
            throw new Exception(__('License is not associated to a valid firm.'));
        }

        // validate the license is valid
        if ($license['LicenseType']['abbr'] != 'CONT')
        {
            throw new Exception(__('License type must be Abatement Contractor.'));
        }

        if (!GenLib::isData($license, 'ParentLicense.0', array('id')))
        {
            throw new Exception(__('License is not associated to a valid firm.'));
        }

        $firm_id = count($license['ParentLicense']) == 1 ? $license['ParentLicense'][0]['id'] : null;

        // format the new record data
        $abatement_data = array(
            'Abatement' => array(
                'label'               => 'Abatement Notice',
                'slug'                => 'abatement-notice',
                'abatement_status_id' => $this->AbatementStatus->getStatusId('Incomplete'),
                'abatement_number'    => $this->generateAbatementNumber(),
                'license_id'          => $license_id,
                'firm_id'             => $firm_id,
                'dwelling_type_id'    => $dwelling_type_id,
                'enabled'             => true,
            ),
        );

        // save the new record
        if (! $this->add($abatement_data))
        {
            throw new Exception(__('Failed to save abatement record.'));
        }

        return $this->getLastInsertId();
    }

    /**
     * Modify an abatement.
     *
     * @param Array $data Abatement data
     *
     * @return boolean True or false
     * @throws Exception If primary key (`id`) not found in $data or abatement could not be saved to the database.
     * @access public
     */
    public function editAbatement($data)
    {
        if (empty($data['Abatement']['id']))
        {
            throw new Exception(sprintf(__('Missing primary key data for model %s'), $this->name));
        }

        try
        {
            $this->_formatPropertyOwnerInformation($data);
            if (! isset($data['Abatement']['label']))
            {
                $abatement = $this->details($data['Abatement']['id']);
                $data['Abatement']['label'] = $abatement['Abatement']['label'];
                unset ($abatement);
            }
            return parent::edit($data);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Abatement (%s) could not be modified.'), $data['Abatement']['label']));
        }
    }

    /**
     * Delete an abatement.
     *
     * @param int $id Abatement ID
     *
     * @return boolean True or false
     * @throws Exception If abatement cannot be deleted from database.
     * @access public
     */
    public function deleteAbatement($id)
    {
        try
        {
            return parent::delete($id);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Abatement (%s) could not be deleted.'), $id));
        }
    }

    /**
     * Adds an abatement phase to an abatement.
     *
     * @param int   $abatement_id Abatement ID
     * @param array $phase        Phase dates
     *
     * @return void
     * @throws Exception If phase overlaps with an existing phase for the same abatement.
     * @throws Exception If phase start date is less than 7 days from the abatement's received date.
     * @access public
     */
    public function addPhase($abatement_id, $phase)
    {
        try
        {
            if ($this->canAddPhase($abatement_id, $phase))
            {
                $data = array_merge($phase, array('abatement_id' => $abatement_id));
                if (! $this->AbatementPhase->add($data))
                {
                    throw new Exception(__('Unable to add phase.'));
                }
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Edits an abatement phase.
     *
     * @param int   $abatement_id       Abatement ID
     * @param int   $abatement_phase_id Abatement phase ID
     * @param array $phase              Abatement phase data
     *
     * @return void
     * @throws Exception If phase overlaps with an existing phase for the same abatement.
     * @throws Exception If phase start date is less than 7 days from the abatement's received date.
     * @access public
     */
    public function editPhase($abatement_id, $abatement_phase_id, $phase)
    {
        try
        {
            if ($this->canModifyPhase($abatement_id, $abatement_phase_id, $phase))
            {
                $data = array_merge(
                    $phase,
                    array(
                        'id'           => $abatement_phase_id,
                        'abatement_id' => $abatement_id,
                    )
                );
                if (! $this->AbatementPhase->edit($data))
                {
                    throw new Exception(__('Unable to modify abatement phase.'));
                }
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Deletes an abatement phase.
     *
     * @param int $abatement_id       Abatement ID
     * @param int $abatement_phase_id Abatement phase ID
     *
     * @return void
     * @access public
     */
    public function deletePhase($abatement_id, $abatement_phase_id)
    {
        try
        {
            return $this->AbatementPhase->delete($abatement_phase_id);
        }
        catch (Exception $e)
        {
            throw new Exception(__('Abatement phase could not be deleted.'));;
        }
    }

    /**
     * Returns whether or not a phase can be added to an existing abatement.
     * Checks for only overlapping phase dates.
     *
     * @param int   $abatement_id Abatement ID
     * @param array $phase        Abatement phase data
     *
     * @return void
     * @throws Exception If phase end date occurs before the phase begin date.
     * @throws Exception If specified phase dates overlap with existing phase dates.
     * @access public
     */
    public function canAddPhase($abatement_id, $phase)
    {
        $existingPhases = $this->AbatementPhase->findByAbatementId($abatement_id);

        $begin = GenLib::dateFormat($phase['begin_date'], 'Y-m-d');
        $end = GenLib::dateFormat($phase['end_date'], 'Y-m-d');

        if (strtotime($end) < strtotime($begin))
        {
            throw new Exception(__('Phase end date occurs before begin date.'));
        }

        // check for overlapping dates
        $conditions = array(
            'AbatementPhase.abatement_id' => $abatement_id,
            'AND' => array(
                'AbatementPhase.begin_date <' => $end,
                'AbatementPhase.end_date >'   => $begin,
            ),
        );

        // if phase ID is set, we came from canModifyPhase, so we'll want to not check for
        // overlap using the date we're editing
        if (isset($phase['id']))
        {
            $conditions['AbatementPhase.id !='] = $phase['id'];
        }

        if ($this->AbatementPhase->find('count', compact('conditions')) > 0)
        {
            throw new Exception(__('Dates overlap with existing phase dates.'));
        }

        return true;
    }

    /**
     * Returns whether or not a phase can be modified from an existing abatement.
     * More or less a wrapper for `canAddPhase()`.
     *
     * @param int   $abatement_id       Abatement ID
     * @param int   $abatement_phase_id Abatement phase ID
     * @param array $phase              Abatement phase data
     *
     * @return void
     * @access public
     */
    public function canModifyPhase($abatement_id, $abatement_phase_id, $phase)
    {
        $phase = array_merge($phase, array('id' => $abatement_phase_id));
        return $this->canAddPhase($abatement_id, $phase);
    }

    /**
     * Retrieves phases for a given abatement.
     *
     * @param int $id Abatement ID
     *
     * @return array Abatement Phases
     * @access public
     */
    public function getPhases($id)
    {
        $conditions = array('AbatementPhase.abatement_id' => $id);
        $order = array('AbatementPhase.begin_date' => 'ASC');

        return $this->AbatementPhase->find('all', compact('conditions', 'order'));
    }

    /**
     * Adds an occupant to a rental property of an abatement project.
     *
     * @param int   $abatement_id Abatement ID
     * @param array $occupant     Occupant information
     *
     * @return boolean True or false
     * @throws Exception If specified abatement's project owner does not live in a rental property.
     * @throws Exception If unable to save occupant.
     *
     * @access public
     */
    public function addOccupant($abatement_id, $occupant)
    {
        try
        {
            $abatement = $this->details($abatement_id);
            $data = array(
                'Abatement'        => array('id' => $abatement_id),
                'PropertyOccupant' => array(
                    'foreign_plugin' => 'Abatements',
                    'foreign_obj'    => 'AbatementPropertyOccupant',
                    'foreign_key'    => $abatement_id,
                    'first_name'     => $occupant['first_name'],
                    'last_name'      => $occupant['last_name'],
                )
            );

            // add 'em
            return $this->PropertyOccupant->add($data);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Edits an occupant to a rental property of an abatement project.
     *
     * @param int   $abatement_id Abatement ID
     * @param int   $contact_id   Contact ID
     * @param array $occupant     Occupant information
     *
     * @return boolean True or false
     * @throws Exception If specified occupant is not associated to the abatement.
     *
     * @access public
     */
    public function editOccupant($abatement_id, $contact_id, $occupant)
    {
        try
        {
            if (! $this->hasOccupant($abatement_id, $contact_id))
            {
                throw new Exception(__('Contact is not an occupant of this property.'));
            }

            // modify 'em
            $data = array(
                'Abatement'        => array('id' => $abatement_id),
                'PropertyOccupant' => array_merge($occupant, array('id' => $contact_id)),
            );
            return $this->PropertyOccupant->edit($data);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Remove an occupant from rental property of an abatement project.
     *
     * @param int $abatement_id Abatement ID
     * @param int $contact_id   Contact (occupant) ID
     *
     * @return boolean True or false
     *
     * @throws Exception If specified contact is not an occupant.
     * @throws Exception If unable to remove occupant.
     *
     * @access public
     */
    public function deleteOccupant($abatement_id, $contact_id)
    {
        try
        {
            if (! $this->hasOccupant($abatement_id, $contact_id))
            {
                throw new Exception(__('Contact is not an occupant of this property.'));
            }

            // remove 'em
            return $this->PropertyOccupant->delete($contact_id, false);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Checks if a specified contact is an occupant of a property owner's dwelling.
     *
     * @param int $abatement_id Abatement ID
     * @param int $contact_id   Contact (occupant) ID
     *
     * @return boolean True or false
     * @access public
     */
    public function hasOccupant($abatement_id, $contact_id)
    {
        // validate that the specified contact is actually an occupant
        $conditions = array(
            'id'             => $contact_id,
            'foreign_plugin' => 'Abatements',
            'foreign_obj'    => 'AbatementPropertyOccupant',
            'foreign_key'    => $abatement_id,
        );

        return $this->PropertyOccupant->find('count', compact('conditions')) == 1;
    }

    /**
     * Retrieve an abatement.  Overrides AppModel::details().
     *
     * @param int   $id       Abatement ID
     * @param array $contains Contains array
     *
     * @return array Abatement
     * @access public
     *
     * @throws Exception If specified abatement cannot be found.
     */
    public function details($id, $contains = false)
    {
        try
        {
            $contain = array(
                'AbatementSubmission',
                'AbatementStatus',
                'AbatementPhase',
                'DwellingType',
                'Address',
                'License' => array('ParentLicense'),
                'PropertyOwner',
                'PropertyOccupant',
                'Firm' => array('License')
            );
            $conditions = array('Abatement.id' => $id);
            return $this->find('first', compact('conditions', 'contain'));
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Abatement (%s) could not be found'), $id));
        }
    }

    /**
     * Submits an abatement. Marks abatement status as 'Active' and
     * queues an output document.
     *
     * @param int $id Abatement ID.
     *
     * @return void
     * @access public
     */
    public function submit($id)
    {
        try
        {
            $abatement = $this->details($id);
            if ($abatement['Abatement']['date_submitted'] === null)
            {
                // hasn't been submitted yet, mark as active and batch approval letter
                $this->AbatementSubmission->addSubmission($id, $abatement['AbatementPhase'], 'Initial');
                $this->setStatus($id, 'active');
                $this->saveField('date_submitted', date('Y-m-d H:i:s'));
                $this->enable($id);
                $this->batchAbatementApprovalLetter($id);
            }
            else
            {
                $this->AbatementSubmission->addSubmission($id, $abatement['AbatementPhase'], 'Revision');
                $this->incrementRevisionCount($id);
                // already submitted, this is a revision so batch the revised letter
                $this->batchAbatementRevisedNotificationLetter($id);
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Returns whether or not an abatement can be submitted.
     *
     * @param int $id Abatement ID
     *
     * @return boolean True or false
     * @throws Exception If specified abatement cannot be found.
     * @throws Exception If specified abatement is not incomplete and hasn't been submitted (not a revision).
     * @throws Exception If specified abatement is for a rental property but has no occupants.
     * @throws Exception If specified abatement does not have a notification received date.
     * @throws Exception If specified abatement has no phases.
     * @throws Exception If specified abatement's received date occurs after the projected start date.
     * @throws Exception If specified abatement's received date is not atleast 7 days prior to the projected start date.
     * @access public
     */
    public function canSubmit($id)
    {
        $this->id = $id;
        if (! $this->exists())
        {
            throw new Exception(__('Invalid abatement notice.'));
        }

        $abatement = $this->details($id);

        // do not allow submit if abatement is not incomplete (or active for revised abatements)
        if (! $this->isIncomplete($id))
        {
            if ($abatement['Abatement']['date_submitted'] === null)
            {
                throw new Exception(
                    __('Previously active or completed abatement notices cannot be submitted.')
                );
            }
        }

        // do not allow submit if abatement is a rental and there aren't any occupants
        $isRental = $this->DwellingType->isRental($abatement['DwellingType']['id']);
        if ($isRental && count($abatement['PropertyOccupant']) == 0)
        {
            throw new Exception(__('Notification for a rental property must specify occupants.'));
        }

        $phases = $this->getPhases($id);
        $firstPhase = current($phases);
        $projectedStart = strtotime($firstPhase['AbatementPhase']['begin_date']);
        $receivedDate = strtotime($abatement['Abatement']['date_received']);

        // do not allow empty recieved date
        if (! $receivedDate)
        {
            throw new Exception(__('Notification must have a recieved date.'));
        }

        // do not allow submit if there aren't any phases
        if (! $phases)
        {
            throw new Exception(__('At least one phase is required.'));
        }

        // do not allow the projected (first phase) start to occur before the received date
        if ($receivedDate > $projectedStart)
        {
            throw new Exception(__('Notification received date must not occur after the projected start date.'));
        }

        // notification must be recieved 7 days prior to the projected (first) start date
        if (! ($projectedStart >= strtotime('+7 days', $receivedDate)))
        {
            throw new Exception(__('Notification must be received atleast 7 days prior to the projected start date.'));
        }

        if ($this->isActive($id))
        {
            if (count($abatement['AbatementSubmission']) > 0)
            {
                $abatement_submissions = Hash::sort($abatement['AbatementSubmission'], '{n}.date', 'asc');
                $current_submission = array_pop($abatement_submissions);
                $submission_phases = unserialize($current_submission['data']);

                $submission_phases = Hash::remove($submission_phases, '{n}.modified');
                $current_phases = Hash::remove($abatement['AbatementPhase'], '{n}.modified');

                $diff = Hash::diff($submission_phases, $current_phases);

                if (empty($diff))
                {
                    throw new Exception(__('No phases were changed since last submission.'));
                }
            }
        }

        return true;
    }

    /**
     * Generates a new abatement number.
     *
     * @param string $year Year (default: next year)
     *
     * @return string Abatement number
     * @access public
     */
    public function generateAbatementNumber($year = false)
    {
        $year = $year ? $year : date('Y')+1;
        return sprintf('%s-ABBT-%05d', $year, $this->find('count')+1);
    }

    /**
     * Sets an abatement status.
     *
     * @param int    $id    Abatement ID
     * @param string $label Status
     *
     * @return boolean True or false
     * @access public
     */
    public function setStatus($id, $label)
    {
        $this->read(null, $id);
        return $this->saveField('abatement_status_id', $this->AbatementStatus->getStatusId($label));
    }

    /**
     * Retrieves active abatements.
     *
     * @return array Abatements
     * @access public
     */
    public function getActiveAbatements()
    {
        $contain = array('AbatementPhase');
        $conditions = array(
            'Abatement.abatement_status_id' => $this->AbatementStatus->getStatusId('active'),
        );

        return $this->find('all', compact('conditions', 'contain'));
    }

    /**
     * Returns whether or not an abatement is active.
     *
     * @param int $id Abatement ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isActive($id)
    {
        $abatement = $this->details($id);
        return preg_match('/^active/i', $abatement['AbatementStatus']['label']);
    }

    /**
     * Returns whether or not an abatement is incomplete.
     *
     * @param int $id Abatement ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isIncomplete($id)
    {
        $abatement = $this->details($id);
        return preg_match('/incomplete/i', $abatement['AbatementStatus']['label']);
    }

    /**
     * Returns whether or not an abatement is complete.
     *
     * @param int $id Abatement ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isComplete($id)
    {
        $abatement = $this->details($id);
        return preg_match('/^complete/i', $abatement['AbatementStatus']['label']);
    }

    /**
     * Returns whether or not an abatement is cancelled.
     *
     * @param int $id Abatement ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isCancelled($id)
    {
        $abatement = $this->details($id);
        return preg_match('/^cancel/i', $abatement['AbatementStatus']['label']);
    }

    /**
     * Returns whether or not an abatement notice has multiple phases.
     *
     * @param int $id Abatement ID
     *
     * @return boolean True or false
     * @access public
     */
    public function hasMultiplePhases($id)
    {
        $abatement = $this->details($id);
        return count($abatement['AbatementPhase']) > 1;
    }

    /**
     * getDwellingTypeList method
     *
     * @return array returns a list of dwelling types
     * @access public
     */
    public function getDwellingTypeList()
    {
        return $this->DwellingType->getDwellingTypeList();
    }

    /**
     * Retrieves data to send to an output document.
     *
     * @param array $params Parameters
     *
     * @return array Output document data
     * @access public
     */
    public function getOutputDocumentData($params)
    {
        $abatement = $this->details($params['fk']);
        //debug($abatement['License']); exit;
        $lastPhaseIdx = count($abatement['AbatementPhase']) - 1;
        $license = $this->License->getApplication($abatement['Abatement']['license_id'], array('Application'));

        $lastPhaseEndDate = $abatement['AbatementPhase'][$lastPhaseIdx]['end_date'];
        $abatement['report_due_date'] = date("F j, Y", strtotime('+30 days', strtotime($lastPhaseEndDate)));
        $abatement['phase_2_start_date'] = date("F j, Y", strtotime($abatement['AbatementPhase'][$lastPhaseIdx]['begin_date']));
        $abatement['phase_2_end_date'] = date("F j, Y", strtotime($abatement['AbatementPhase'][$lastPhaseIdx]['end_date']));

        $abatement['Account'] = $license['Account'];

        return $abatement;
    }

    /**
     * Batches approval letter
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access public
     */
    public function batchAbatementApprovalLetter($id)
    {
        if (! $this->isActive($id))
        {
            throw new Exception(
                __('Unable to batch approval letter for a non-active abatement notification')
            );
        }

        return $this->batchAbatementLetter($id, 'abatement_initial');
    }

    /**
     * Batches reminder letter
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access public
     */
    public function batchAbatementReminderLeter($id)
    {
        return $this->batchAbatementLetter($id, 'abatement_reminder');
    }

    /**
     * Batches revised notification letter
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access public
     */
    public function batchAbatementRevisedNotificationLetter($id)
    {
        $trigger = $this->hasMultiplePhases($id) ? 'abatement_revised_multi_phased' : 'abatement_revised';
        return $this->batchAbatementLetter($id, $trigger);
    }

    /**
     * Batches an approval letter.
     *
     * @param int    $id      Abatement ID
     * @param string $trigger Trigger
     *
     * @return boolean True or false
     * @access public
     */
    public function batchAbatementLetter($id, $trigger)
    {
        try
        {
            $params = array(
                'fp'      => 'Abatements',
                'fo'      => $this->name,
                'fk'      => $id,
                'trigger' => $trigger,
            );

            return $this->queueDocs($params);
        }
        catch (Exception $e)
        {
            return false;
        }

        return true;
    }

    /**
     * Formats property owner information for add/edit
     *
     * @param array &$data data
     *
     * @return void
     * @access private
     */
    private function _formatPropertyOwnerInformation(&$data)
    {
        if (! isset($data['Abatement']['dwelling_type_id'])
            || ! isset($data['PropertyOwner'])
            || ! isset($data['PropertyOccupant'])
        )
        {
            return;
        }

        $isRental = $this->DwellingType->isRental($data['Abatement']['dwelling_type_id']);
        $success = false;

        // Set foreign objects for address and owner fields
        $data['PropertyOwner']['foreign_plugin'] = 'Abatements';
        $data['PropertyOwner']['foreign_obj'] = 'AbatementPropertyOwner';
        $data['PropertyOwner']['label'] = __('Abatement Property Owner');

        if ($isRental)
        {
            $data['PropertyOwnerAddress']['foreign_plugin'] = 'Abatements';
            $data['PropertyOwnerAddress']['foreign_obj'] = 'AbatementPropertyOwnerAddress';
            $data['PropertyOwnerAddress']['label'] = __('Abatement Property Owner Address');
        }
        else
        {
            unset($data['PropertyOwnerAddress']);
        }

        foreach ($data['PropertyOccupant'] as $idx => $occupant)
        {
            // only insert non-empty occupants
            if (strlen($occupant['first_name']) > 0 && strlen($occupant['last_name']) > 0)
            {
                $data['PropertyOccupant'][$idx]['foreign_plugin'] = 'Abatements';
                $data['PropertyOccupant'][$idx]['foreign_obj'] = 'AbatementPropertyOccupant';
            }
        }
    }

    /**
     * incrementRevisionCount method
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access private
     */
    public function incrementRevisionCount($id = null)
    {
        try
        {
            if ($id)
            {
                if ($abatement = $this->findById($id))
                {
                    $revision_count = $abatement['Abatement']['revision_count'];
                    $this->create();
                    $this->id = $id;
                    $this->saveField('revision_count', $revision_count + 1);
                }
            }
        }
        catch (Exception $e)
        {
            return false;
        }
        return true;
    }
}