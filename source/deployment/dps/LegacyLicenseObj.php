<?php
// grab the common LegacyObj object
require_once(ROOT.DS.'source'.DS.'deployment'.DS.'vanilla'.DS.'LegacyObj.php');

class LegacyLicenseObj extends LegacyObj
{
    /**
     * Data mapping from csv to cake models  
     *
     * @var array
     * @access public
     */
    public $settings = array(
        'importLicenses' => array(
            'query' => 'SELECT
                            *
                        FROM
                            (
                                (SELECT
                                    person.user_id,
                                    person.dob,
                                    person.ssn_last_four,
                                    TRIM(REPLACE(REPLACE(CAST(AES_DECRYPT(person.ssn,"ad0be6ea426519d1288d941bd6f4fb7cbcd0cbc3") AS CHAR(10000) CHARACTER SET utf8), "-", ""), "/", "")) as ssn,
                                    TRIM(person.first_name) AS first_name,
                                    TRIM(person.last_name) AS last_name,
                                    TRIM((CASE WHEN 
                                        (details.license_type = "Unclassified") 
                                        THEN "Unclassified Person" 
                                    WHEN 
                                        (details.license_type = "Apprentice Electrician") 
                                        THEN "Apprentice" 
                                    ELSE 
                                        details.license_type 
                                    END)) as license_type,
                                    TRIM(details.license_number) AS license_number,
                                    details._type,
                                    DATE_FORMAT(FROM_UNIXTIME(history.timestamp), "%Y-%m-%d") AS application_date
                                FROM
                                    details
                                JOIN person
                                    ON TRIM(details.last_name) = TRIM(person.last_name) AND person.user_id = details.id_number
                                JOIN history
                                    ON history.id_number = details.id_number AND history.page = "legacy_acceptance_letter"
                                WHERE 
                                    details.status = "A" 
                                    AND details._type NOT IN ("INACTIVE")
                                    AND details.license_number NOT IN ("PENDING")
                                    AND details.license_type NOT IN ("Electrical Contractor", "Residential Contractor")
                                )
                            UNION
                                (SELECT
                                    person.user_id,
                                    person.dob,
                                    person.ssn_last_four,
                                    TRIM(REPLACE(REPLACE(CAST(AES_DECRYPT(person.ssn,"ad0be6ea426519d1288d941bd6f4fb7cbcd0cbc3") AS CHAR(10000) CHARACTER SET utf8), "-", ""), "/", "")) as ssn,
                                    TRIM(person.first_name) AS first_name,
                                    TRIM(person.last_name) AS last_name,
                                    TRIM((CASE WHEN 
                                        (details.license_type = "Unclassified") 
                                        THEN "Unclassified Person" 
                                    WHEN 
                                        (details.license_type = "Apprentice Electrician") 
                                        THEN "Apprentice" 
                                    ELSE 
                                        details.license_type 
                                    END)) as license_type,
                                    TRIM(details.license_number) AS license_number,
                                    details._type,
                                    DATE_FORMAT(FROM_UNIXTIME(submission.submission_date), "%Y-%m-%d") AS application_date
                                FROM
                                    details
                                JOIN person
                                    ON TRIM(details.last_name) = TRIM(person.last_name) AND person.user_id = details.id_number
                                JOIN submission
                                    ON submission.user_id = details.id_number AND submission.application_id = person.application_id
                                WHERE 
                                    details.status = "A" 
                                    AND details._type NOT IN ("INACTIVE")
                                    AND details.license_number NOT IN ("PENDING")
                                    AND details.license_type NOT IN ("Electrical Contractor", "Residential Contractor")
                                )
                            UNION
                                (SELECT
                                    person.user_id,
                                    person.dob,
                                    person.ssn_last_four,
                                    TRIM(REPLACE(REPLACE(CAST(AES_DECRYPT(person.ssn,"ad0be6ea426519d1288d941bd6f4fb7cbcd0cbc3") AS CHAR(10000) CHARACTER SET utf8), "-", ""), "/", "")) as ssn,
                                    TRIM(person.first_name) AS first_name,
                                    TRIM(person.last_name) AS last_name,
                                    TRIM((CASE WHEN 
                                        (details.license_type = "Unclassified") 
                                        THEN "Unclassified Person" 
                                    WHEN 
                                        (details.license_type = "Apprentice Electrician") 
                                        THEN "Apprentice" 
                                    ELSE 
                                        details.license_type 
                                    END)) as license_type,
                                    TRIM(details.license_number) AS license_number,
                                    details._type,
                                    DATE_FORMAT(FROM_UNIXTIME(congratulations_letter.generated), "%Y-%m-%d") AS application_date
                                FROM
                                    details
                                JOIN person
                                    ON TRIM(details.last_name) = TRIM(person.last_name) AND person.user_id = details.id_number
                                LEFT JOIN congratulations_letter
                                    ON congratulations_letter.user_id = person.user_id AND congratulations_letter.application_id = person.application_id
                                WHERE 
                                    details.status = "A" 
                                    AND details._type NOT IN ("INACTIVE")
                                    AND details.license_number NOT IN ("PENDING")
                                    AND details.license_type NOT IN ("Electrical Contractor", "Residential Contractor")
                                )
                            ) AS license
                        GROUP BY license.ssn, license.license_type
                        ORDER BY 
                            license.ssn, FIELD(license._type, "NEW", "RENEWAL") ASC, ISNULL(license.application_date), license.application_date ASC',
            'filter' => array(
                'license.license_type' => 'licenseTypeMap'
            ),
            'data_map' => array(
                'License.legacy_number' => 'license.license_number',
                'Application.0.materials_received' => 'license.application_date',
                'Application.0.submitted_date' => 'license.application_date',
                'Application.0.paid_date' => 'license.application_date',
            ),
        ),
        'importFirmLicenses' => array(
            'query' => 'SELECT
                    person.user_id,
                    person.dob,
                    person.ssn_last_four,
                    TRIM(REPLACE(REPLACE(CAST(AES_DECRYPT(person.ssn,"ad0be6ea426519d1288d941bd6f4fb7cbcd0cbc3") AS CHAR(10000) CHARACTER SET utf8), "-", ""), "/", "")) as ssn,
                    TRIM(person.first_name) AS first_name,
                    TRIM(person.last_name) AS last_name,
                    TRIM((CASE WHEN 
                        (details.license_type = "Unclassified") 
                        THEN "Unclassified Person" 
                    WHEN 
                        (details.license_type = "Apprentice Electrician") 
                        THEN "Apprentice" 
                    ELSE 
                        details.license_type 
                    END)) as license_type,
                    TRIM(details.license_number) AS license_number,
                    details._type,
                    DATE_FORMAT(FROM_UNIXTIME(
                        case when 
                            NOT ISNULL(congratulations_letter.generated)
                        then 
                            congratulations_letter.generated
                        else 
                            submission.submission_date 
                        end
                    ), "%Y-%m-%d") AS application_date,
                    electrical_contractor.id,
                    electrical_contractor.dol_number,
                    electrical_contractor.fid_number,
                    electrical_contractor.master_name,
                    electrical_contractor.contractor_name,
                    null as elf_firm,
                    null as elf_account

                FROM
                    details
                JOIN person
                    ON person.user_id = details.id_number AND details.last_name = person.last_name
                LEFT JOIN submission
                    ON submission.user_id = person.user_id AND submission.application_id = person.application_id
                LEFT JOIN congratulations_letter
                    ON congratulations_letter.user_id = person.user_id AND congratulations_letter.application_id = person.application_id
                LEFT JOIN electrical_contractor
                    ON electrical_contractor.user_id = person.user_id
                WHERE 
                    details.status = "A" 
                    AND details._type NOT IN ("INACTIVE")
                    AND details.license_number NOT IN ("PENDING")
                    AND details.license_type IN ("Electrical Contractor", "Residential Contractor")
                GROUP BY person.ssn, license_type, application_date
                ORDER BY 
                    person.ssn, FIELD(_type, "NEW", "RENEWAL") ASC, ISNULL(application_date), application_date ASC;
                ',
            'filter' => array(
                'details.license_type' => 'licenseTypeMap',
                'electrical_contractor.fid_number' => 'numericOnly',
                '0.elf_firm' => 'getFirm',
                '0.elf_account' => 'getAccount',
                '0.elf_associated_master' => 'associateMaster'
            ),
            'data_map' => array(
                'License.legacy_number' => '0.license_number',
                'Application.0.materials_received' => '0.application_date',
                'Application.0.submitted_date' => '0.application_date',
                'Application.0.paid_date' => '0.application_date',
                'Contractor.crn' => 'electrical_contractor.dol_number',
                'Contractor.fin' => 'electrical_contractor.fid_number',
            ),
        ),
        'importLicenseNotes' => array(
            'query' => 'SELECT 
                    "Licenses" AS foreign_plugin,
                    "License" AS foreign_obj,
                    staff_note.first_name,
                    staff_note.last_name,
                    NULL AS foreign_key,
                    NULL AS license_notes,
                    staff_note.id_number AS user_id,
                    TRIM((CASE WHEN 
                        (staff_note.license_type = "Unclassified") 
                        THEN "Unclassified Person" 
                    WHEN
                        (staff_note.license_type = "Special")
                        THEN "Special Electrician"
                    WHEN
                        (staff_note.license_type = "Master Class A Electrician")
                        THEN "Master Class A"
                    WHEN
                        (staff_note.license_type = "Master Class B Electrician")
                        THEN "Master Class B"
                    WHEN
                        (staff_note.license_type = "Journeyman Class A Electrician")
                        THEN "Journeyman Class A"
                    WHEN
                        (staff_note.license_type = "Journeyman Class B Electrician")
                        THEN "Journeyman Class B"
                    WHEN
                        (staff_note.license_type = "Apprentice Electrician")
                        THEN "Apprentice"
                    ELSE 
                        staff_note.license_type 
                    END)) AS license_type,
                    staff_note.leave_note AS note,
                    DATE_FORMAT(FROM_UNIXTIME(staff_note.timestamp), "%Y-%m-%d") AS created
                FROM staff_note 
                GROUP BY staff_note.id_number, staff_note.leave_note, staff_note.timestamp
                ORDER BY staff_note.timestamp ASC;',
            'filter' => array(
                '0.license_notes' => 'buildLicenseNotes'
            ),
            'data_map' => array(
                'Note' => '0.license_notes',
            ),
        ),
        'importInsuranceInformation' => array(
            'query' => 'SELECT
                    electrical_contractor.id,
                    electrical_contractor.user_id,
                    electrical_contractor.insurance_name,
                    electrical_contractor.insurance_expiration_date,
                    TRIM(person.first_name) AS first_name,
                    TRIM(person.last_name) AS last_name,
                    "Licenses" AS foreign_plugin,
                    "License" AS foreign_obj,
                    NULL AS foreign_key
                FROM electrical_contractor
                LEFT JOIN person ON person.user_id = electrical_contractor.user_id
                ',
            'filter' => array(
                '0.foreign_key' => 'getInsuranceLicenseKey',
                'electrical_contractor.insurance_expiration_date' => 'formatDate',
            ),
            'data_map' => array(
                'InsuranceInformation.label' => 'electrical_contractor.insurance_name',
                'InsuranceInformation.expire_date' => 'electrical_contractor.insurance_expiration_date',
                'InsuranceInformation.foreign_plugin' => '0.foreign_plugin',
                'InsuranceInformation.foreign_obj' => '0.foreign_obj',
                'InsuranceInformation.foreign_key' => '0.foreign_key',
            ),
        ),
        'importLicenseVariants' => array(
            'query' =>
                    'SELECT TRIM(licenseType.user_id) AS user_id, TRIM(licenseType.endorsement_irrigation) AS irrigation, "irrigation" as endorsement_type
                        FROM license_type AS licenseType
                        WHERE licenseType.endorsement_irrigation <> 0 AND licenseType.endorsement_irrigation IS NOT NULL
                    UNION ALL
                    SELECT TRIM(licenseType.user_id) AS user_id, TRIM(licenseType.endorsement_hvac) AS hvac, "hvac" as endorsement_type
                        FROM license_type AS licenseType
                        WHERE licenseType.endorsement_hvac <> 0 AND licenseType.endorsement_hvac IS NOT NULL
                    UNION ALL
                    SELECT TRIM(licenseType.user_id) AS user_id, TRIM(licenseType.endorsement_sign_installation) AS signs, "signs" as endorsement_type
                        FROM license_type AS licenseType
                        WHERE licenseType.endorsement_sign_installation <> 0 AND licenseType.endorsement_sign_installation IS NOT NULL
                    ORDER BY user_id, endorsement_type ASC',
            'filter' => array(
                '0.endorsement_type' => array('endorsementTypeToVariantId'),
            ),
            'data_map' => array(
                'LicenseVariant.variant_id' => '0.endorsement_type',
            ),
        ),
    );

    public function __construct(&$Model)
    {
        $this->Model = $Model;
    }

    /**
     * Get the elf license data and add it to the legacy_data for mapping
     * 
     * @param misc 
     */
    public function getInsuranceLicenseKey($value = null, $legacy_data = array())
    {   
        // get the elf firm and license
        $firm = $this->getFirm($value, $legacy_data);
        
        $license = $this->Model->find(
            'first', 
            array(
                'conditions' => array(
                    'License.foreign_obj' => 'Firm', 
                    'License.foreign_key' => $firm['Firm']['id'],
                )
            )
        );
        
        return ($license ? $license['License']['id'] : null);
    }
    
    /**
     * Get the elf license data and add it to the legacy_data for mapping
     * 
     * @param misc 
     */
    public function addElfLicenseData($value = null, $legacy_data = array())
    {
       // load the account model
        $AccountModel = ClassRegistry::init('Accounts.Account', 'Model');
        
        // get the elf account and license
        $account = $AccountModel->getAccountByLegacyID($legacy_data['staff_note']['user_id']);
        
        $license = $this->Model->find(
            'first', 
            array(
                'contain' => array('LicenseType'),
                'conditions' => array(
                    'License.foreign_obj' => 'Account', 
                    'License.foreign_key' => $account['Account']['id'],
                    'LicenseType.label' => $legacy_data[0]['license_type']
                )
            )
        );

        if(!$license)
        {
            // skip those notes that do not map to licenses in our ELF database 
            throw new Exception(
                sprintf(
                    "Inside LegacyLicenseObj addElfLicenseData: %s license not found for %s %s.", 
                    $legacy_data[0]['license_type'],
                    $legacy_data['staff_note']['first_name'], 
                    $legacy_data['staff_note']['last_name']
                )
            );
        }
        
        return ($license ? $license['License']['id'] : null);
    }

    public function getFirm($value = null, $legacy_data = array())
    {
        $FirmModel = ClassRegistry::init('Firms.Firm', 'Model');
        $firm = $FirmModel->findByLegacyId($legacy_data['electrical_contractor']['id']);
        if ($firm)
        {
            return $firm;
        }
        else
        {
            // skip those firms that were not imported to our ELF database 
            throw new Exception(
                    sprintf(
                        "Inside LegacyLicenseObj getFirm: %s %s's firm could not be found.", 
                        $legacy_data['0']['first_name'], 
                        $legacy_data['0']['last_name']
                    )
                );
        }
        return false;
    }

    public function getAccount($value = null, $legacy_data = array())
    {
        $AccountModel = ClassRegistry::init('Accounts.Account', 'Model');
        $account = $AccountModel->getAccountByLegacyID($legacy_data['person']['user_id']);
        if (!$account)
        {
            throw new Exception(
                sprintf(
                    "Inside LegacyLicenseObj getFirm error #2: %s %s's account associated to the firm could not be found.", 
                    $legacy_data['0']['first_name'], 
                    $legacy_data['0']['last_name']
                )
            );
        }
        return $account;
    }

    public function buildLicenseNotes($value = null, $legacy_data = array())
    {
        // load the account model
        $AccountModel = ClassRegistry::init('Accounts.Account', 'Model');

        $contain = array(
            'Manager',
            'License'
        );

        $data = array();
        
        // get the elf account and license
        $account = $AccountModel->getAccountByLegacyID($legacy_data['staff_note']['user_id'], $contain);

        if (!$account)
        {
            throw new Exception(
                sprintf(
                    "buildLicenseNotes: %s %s's account associated to the note could not be found.", 
                    $legacy_data['staff_note']['first_name'], 
                    $legacy_data['staff_note']['last_name']
                )
            );
        }
        
        // check for associated licenses
        if (!GenLib::isData($account, 'License.0', array('id')) && GenLib::isData($account, 'Manager.0', array('id')))
        {
            throw new Exception(
                sprintf(
                    "buildLicenseNotes: %s %s does not manage or own any licenses.", 
                    $legacy_data['staff_note']['first_name'], 
                    $legacy_data['staff_note']['last_name']
                )
            );
        }

        if (GenLib::isData($account, 'License.0', array('id')))
        {
            foreach ($account['License'] as $license)
            {
                $note = array(
                    'foreign_plugin' => 'Licenses',
                    'foreign_obj' => 'License',
                    'foreign_key' => $license['id'],
                    'note' => $legacy_data['staff_note']['note'],
                    'created' => $legacy_data[0]['created'],
                );
                $data[] = $note;
            }
        }

        if (GenLib::isData($account, 'Manager.0', array('id')))
        {
            // get the firm licenses
            $firm_licenses = $AccountModel->Manager->getManagedLicenses($account['Account']['id'], 'Firm');
            
            foreach ($firm_licenses as $firm_license)
            {
                if (!GenLib::isData($firm_license, 'License', array('id')))
                {
                    throw new Exception(
                        sprintf(
                            "buildLicenseNotes: %s %s's firm license id could not be found.", 
                            $legacy_data['staff_note']['first_name'], 
                            $legacy_data['staff_note']['last_name']
                        )
                    );
                }

                $note = array(
                    'foreign_plugin' => 'Licenses',
                    'foreign_obj' => 'License',
                    'foreign_key' => $firm_license['License']['id'],
                    'note' => $legacy_data['staff_note']['note'],
                    'created' => $legacy_data[0]['created'],
                );
                $data[] = $note;
            }
        }

        return $data;
    }

    public function unixToDateTime($value = null, $legacy_data = array())
    {
        if ($value == null)
        {
            return null;
        }

        return date('Y-m-d H:i:s', $value);
    }

    public function licenseTypeMap($value = null, $legacy_data = array())
    {
        $match = trim(strtolower($value));
        
        switch ($match)
        {
            case "apprentice electrician":
                $value = "Apprentice";
                break;
            case "unclassified":
                $value = "Unclassified Person";
                break;
        }
        
        return $value;
    }

    public function formatDate($value = null)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * endorsementTypeToVariantId filter
     * -----------------------------------------
     *
     * Maps DPS endorsement type descriptions to the corresponding ELF variant ID via
     * the variant's abbreviation
     *
     * @var array
     * @access public
     */
    public function endorsementTypeToVariantId($value = null, $legacy_data = array())
    {
        $match = trim(strtolower($value));

        switch ($match)
        {
            case "irrigation":
                $value = "IR";
                break;
            case "hvac":
                $value = "DC";
                break;
            case "signs":
                $value = "SI";
                break;
            default:
                $value = "Unknown";
                break;
        }

        // get the variant and return the id
        $variant = $this->Model->LicenseVariant->Variant->getVariantByAbbr($value);

        return $variant['Variant']['id'];
    }    
}
