<?php
/**
 * Firm model
 *
 * @package Firms.Model
 * @author  Iowa Interactive, LLC.
 */
class Firm extends FirmsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Firm';

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
        'Licenses.License' => array(
            'license' => array(
                'contain' => array(
                    'License',
                    'Manager' => array('Account'),
                    'FirmType',
                    'Address',
                    'Contact',
                    'PrimaryAddress',
                ),
            ),
            'entity' => array(
                'contain' => array(
                    'CompletedLicense' => array('LicenseType', 'LicenseStatus'),
                    'OpenLicense' => array('LicenseType', 'OpenApplication' => array('ApplicationType', 'ApplicationStatus')),
                    'Contact',
                    'Manager' => array('Account'),
                    'Address'
                )
            )
        ),
        'Searchable.Searchable',
        'OutputDocuments.OutputDocument',
    );

    public $contain = array('FirmType', 'Address');

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'label' => array(
            'alphanumeric' => array(
                'rule' => '/[a-zA-Z0-9\-,\s]/'
            ),
        ),
        'slug' => array(
            'alphanumeric' => array(
                'rule' => '/[a-zA-Z0-9\-]/'
            ),
        ),
        'created' => array(
            'datetime' => array(
                'rule' => array('datetime')
            ),
        ),
        'modified' => array(
            'datetime' => array(
                'rule' => array('datetime')
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'FirmType' => array(
            'className' => 'Firms.FirmType',
            'foreignKey' => 'firm_type_id'
        ),
    );

    /**
     * hasOne associations
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'PrimaryAddress' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'PrimaryAddress.foreign_obj' => 'Firm',
                'PrimaryAddress.primary_flag' => true
            )
        ),
        'PrimaryManager' => array(
            'className' => 'Accounts.Manager',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'PrimaryManager.foreign_obj' => 'Firm',
                'PrimaryManager.primary_flag' => true
            )
        ),
        'Contact' => array(
            'className' => 'Contact',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Contact.foreign_obj' => 'Firm')
        ),
    );

    /**
     * hasMany associations
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'OpenLicense' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'OpenLicense.foreign_plugin' => 'Firms',
                'OpenLicense.foreign_obj' => 'Firm',
                '(SELECT COUNT(applications.id) FROM applications WHERE applications.license_id = OpenLicense.id AND applications.open)'
            )
        ),
        'CompletedLicense' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'CompletedLicense.foreign_plugin' => 'Firms',
                'CompletedLicense.foreign_obj' => 'Firm',
                'CompletedLicense.license_status_id != (SELECT license_statuses.id FROM license_statuses WHERE license_statuses.status = "Incomplete" LIMIT 1)',
            )
        ),
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'License.foreign_plugin' => 'Firms',
                'License.foreign_obj' => 'Firm',
            )
        ),
        'Manager' => array(
            'className' => 'Accounts.Manager',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Manager.foreign_obj' => 'Firm')
        ),
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Address.foreign_obj' => 'Firm')
        ),
        'Note' => array(
            'className' => 'Notes.Note',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Note.foreign_obj' => 'Firm')
        ),
    );

    /**
     * afterApprove method
     *
     * @param array  $license expecting license/application data array
     * @param string $trigger output document trigger (default: false)
     *
     * @return boolean|array returns false or new license data array
     * @access public
     *
     * @todo Fix output document batching to use new API
     */
    public function afterApprove($license = array(), $trigger = false)
    {
        try
        {
            // check if the firm has the no_mail flag set
            if (!$license['Firm']['no_mail'])
            {
                $outputDocumentParams = array(
                    'fp' => 'Firms',
                    'fo' => 'Firm',
                    'fk' => $license['Firm']['id'],
                    'trigger' => sprintf('%s_%s', $license['LicenseType']['abbr'], $trigger),
                    'license_id' => $license['License']['id'],
                );

                // send output document to batch queue
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
     * Add a firm.  Automatically creates firm license when firm
     * information is saved successfully.
     *
     * @param Array $data Firm data
     *
     * @return boolean True or false
     * @throws Exception If firm could not be saved to the database.
     * @access public
     */
    public function addFirm($data)
    {
        try
        {
            $data['Firm']['slug'] = GenLib::makeSlug($data['Firm']['label']);

            if (parent::add($data))
            {
                // if the new firm was created by a default user, automatically associate the logged in user
                // as the primary manager for that new firm
                if (CakeSession::read("Auth.User.Group.label") == 'Default Group')
                {
                    $data['Manager']['foreign_plugin'] = 'Firms';
                    $data['Manager']['foreign_obj'] = 'Firm';
                    $data['Manager']['foreign_key'] = $this->id;
                    $data['Manager']['primary_flag'] = 1;
                    $data['Manager']['account_id'] = CakeSession::read("Auth.User.id");

                    if (!$this->Manager->save($data))
                    {
                        throw new Exception(sprintf(__('Could not add Manager to Firm (%s).'), $data['Firm']['label']));
                    }
                }

                return $this->id;
            }
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Firm (%s) could not be created.'), $data['Firm']['label']));
        }
    }

    /**
     * Modify a firm.
     *
     * @param Array $data Firm data
     *
     * @return boolean True or false
     * @throws Exception If primary key (`id`) not found in $data or firm could not be saved to the database.
     * @access public
     */
    public function editFirm($data)
    {
        if (empty($data['Firm']['id']))
        {
            throw new Exception(sprintf(__('Missing primary key data for model %s'), $this->name));
        }

        try
        {
            $data['Firm']['slug'] = GenLib::makeSlug($data['Firm']['label']);
            return parent::edit($data);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Firm (%s) could not be modified.'), $data['Firm']['label']));
        }
    }

    /**
     * Delete a firm.
     *
     * @param int $id Firm ID
     *
     * @return boolean True or false
     * @throws Exception If firm cannot be deleted from database.
     * @access public
     */
    public function deleteFirm($id)
    {
        try
        {
            return parent::delete($id);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Firm (%s) could not be deleted.'), $id));
        }
    }

    /**
     * Retrieve a firm with its licenses.  Overrides AppModel::details().
     *
     * @param int   $id      Firm ID
     * @param array $contain Expecting array of cakephp query contain values
     *
     * @return array Firm
     * @access public
     */
    public function details($id, $contain = null)
    {
        try
        {
            $this->recursive = 1;
            if ($contain == null)
            {
                $contain = array(
                    'FirmType',
                    'Address',
                    'Contact',
                    'Note',
                    'License' => array('LicenseStatus', 'LicenseType'),
                    'PrimaryManager' => array('Account'),
                    'PrimaryAddress',
                );
            }

            return parent::details($id, $contain);
        }
        catch (Exception $e)
        {
            //throw new Exception($e->getMessage());
            throw new Exception(sprintf(__('Firm (%s) could not be found'), $id));
        }
    }

    /**
     * Retrieves managers for a firm.
     *
     * @param int $id Firm ID
     *
     * @return array Firm managers
     * @access public
     */
    public function getFirmManagers($id)
    {
        try
        {
            $contain = array('Manager' => array('Account'));
            $conditions = array('Firm.id' => $id);
            return $this->find('first', compact('conditions', 'contain'));
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Unable to retrieve firm managers for firm (%s)'), $id));
        }
    }

    /**
     * Retrieves the primary manager for a firm.
     *
     * @param int $id Firm ID
     *
     * @return array Primary firm manager
     * @access public
     */
    public function getPrimaryFirmManager($id)
    {
        try
        {
            $details = $this->details($id);
            return $details['PrimaryManager'];
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Unable to retrieve primary firm manager for firm (%s)'), $id));
        }
    }

    /**
     * Searches for firms by name.  Excludes self employed firms by default.
     *
     * @param string        $name                Firm name
     * @param boolean       $includeSelfEmployed Search includes self employed firms (default: false)
     * @param array|boolean $contain             Containable (optional)
     * @param int           $recursive           Recursive level
     *
     * @return array Firms
     * @access public
     */
    public function findFirmByName($name, $includeSelfEmployed = false, $contain = false, $recursive = 1)
    {
        $conditions = array(
            'OR' => array(
                'Firm.label LIKE' => sprintf('%%%s%%', $name),
                'Firm.alias LIKE' => sprintf('%%%s%%', $name),
            )
        );

        if (! $includeSelfEmployed)
        {
            $conditions['Firm.firm_type_id !='] = $this->FirmType->getFirmTypeID('self-employed');
        }

        // Default contain
        if ($contain === false)
        {
            $contain = array(
                'Address',
                'Contact',
                'PrimaryAddress',
                'License' => array('LicenseStatus'),
            );
        }

        return $this->find('all', compact('conditions', 'contain', 'recursive'));
    }

    /**
     * Returns whether or not our associated license has an incomplete status.
     *
     * @param int $id Firm ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isLicenseIncomplete($id)
    {
        $firm = $this->details($id);
        return preg_match('/incomplete/i', $firm['License']['LicenseStatus']['status']);
    }

    /**
     * Returns whether or not the associated license is active.
     *
     * @param int $id Firm ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isLicenseActive($id)
    {
        $firm = $this->details($id);
        return preg_match('/^active/i', $firm['License']['LicenseStatus']['status']);
    }

    /**
     * Returns a list of firm types.
     *
     * @param string $label Firm label.
     *
     * @return array Firm list data
     * @access public
     */
    public function getFirmTypes($label = false)
    {
        return $this->FirmType->getList($label);
    }

    /**
     * Retrieves data to send to an output document.
     *
     * @param array $params params array
     *
     * @return array Output document data
     * @access public
     */
    public function getOutputDocumentData($params = array())
    {
        try
        {
            $license = $this->License->getForeignObjLicense($params['fk'], $params['fo'], $params['fp']);
            // get the account and license info
            $data = $this->License->getApplication($license['License']['id']);

            return $data;
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
                'Manager.foreign_plugin' => 'Firms',
                'Manager.foreign_obj' => 'Firm',
                'Manager.foreign_key' => $id,
                'Manager.account_id' => CakeSession::read("Auth.User.id")
            )
        );
    }

    /**
     * canLicenseAssociateToFirm method
     *
     * checks to see if selected license type can associate with a firm
     *
     * @param int $license_id License id
     * @param int $firm_id    Firm id
     *
     * @return bool
     */
    public function canLicenseAssociateToFirm($license_id = null, $firm_id = null)
    {
        if (!$license_id || !$firm_id)
        {
            return false;
        }

        // Get license record containing license type
        $license = $this->License->find(
            'first',
            array(
                'contain' => array(
                    'LicenseType'
                ),
                'conditions' => array(
                    'License.id' => $license_id
                )
            )
        );

        // Get Firm License record containing license type
        $firm = $this->find(
            'first',
            array(
                'contain' => array(
                    'License' => array(
                        'LicenseType'
                    )
                ),
                'conditions' => array(
                    'Firm.id' => $firm_id
                )
            )
        );

        // Load LicenseTypesLicenseType model to check if firm license type allows license type
        $LicenseTypesLicenseTypeModel = ClassRegistry::init('LicenseTypesLicenseType');

        return $LicenseTypesLicenseTypeModel->hasAny(
            array(
                'LicenseTypesLicenseType.parent_license_type_id' => $firm['License']['license_type_id'],
                'LicenseTypesLicenseType.license_type_id' => $license['LicenseType']['id']
            )
        );
    }
}