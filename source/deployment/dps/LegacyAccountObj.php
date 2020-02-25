<?php
// grab the common LegacyObj object
require_once(ROOT.DS.'source'.DS.'deployment'.DS.'vanilla'.DS.'LegacyObj.php');

class LegacyAccountObj extends LegacyObj
{
	/**
     * Data mapping from csv to cake models 
     *
     * @var array
     * @access public
     */
	public $settings = array(
        'importAccounts' => array(
        	'query' => 'SELECT
                    person.user_id,
                    TRIM(person.first_name) AS first_name,
                    TRIM(person.middle_name) AS middle_name,
                    TRIM(person.last_name) AS last_name,
                    TRIM(REPLACE(REPLACE(CAST(AES_DECRYPT(person.ssn,"ad0be6ea426519d1288d941bd6f4fb7cbcd0cbc3") AS CHAR(10000) CHARACTER SET utf8), "-", ""), "/", "")) as ssn,
                    person.ssn_last_four,
                    person.dob,
                    person.ac,
                    person.pre,
                    person.suff,
                    person.ext,
                    NULL as phone,
                    TRIM(person.address1) AS address1,
                    TRIM(person.address2) AS address2,
                    TRIM(person.city) AS city,
                    TRIM(person.county) AS county,
                    TRIM(person.state) AS state,
                    TRIM(person.zip) AS zip
                FROM person',
            'filter' => array(
                'person.ssn_last_four' => 'numericOnly',
                '0.zip' => 'numericOnly',
                'person.dob' => 'formatDOB',
                '0.phone' => 'formatPhone'
            ),
            'data_map' => array(
        		'Account.first_name' => '0.first_name',
		        'Account.last_name' => '0.last_name',
		        'Account.middle_initial' => '0.middle_name',
		        'Account.ssn' => '0.ssn',
		        'Account.ssn_last_four' => 'person.ssn_last_four',
                'Account.dob' => 'person.dob',
                'Account.legacy_id' => 'person.user_id',
                'Address.0.phone1' => '0.phone',
                'Address.0.ext1' => 'person.ext',
                'Address.0.addr1' => '0.address1',
                'Address.0.addr2' => '0.address2',
                'Address.0.city' => '0.city',
                'Address.0.county' => '0.county',
                'Address.0.state' => '0.state',
                'Address.0.postal' => '0.zip' 
		    ),
		),
        'importWorkExperience' => array(
            'query' => 'SELECT
                    person_employer.user_id,
                    person_employer.first_name,
                    person_employer.last_name,
                    person_employer.company_name,
                    person_employer.address,
                    person_employer.city,
                    person_employer.state,
                    person_employer.zip,
                    (IF(TRIM(person_employer.start_date) IN ("00-00-0000", "00/00/0000", ""), null, TRIM(REPLACE(person_employer.start_date, "00/", "01/")))) AS start_date,
                    (IF(TRIM(person_employer.end_date) IN ("00-00-0000", "00/00/0000", ""), null, TRIM(REPLACE(person_employer.end_date, "00/", "01/")))) AS end_date,
                    person_employer.job_title,
                    CONCAT(person_employer.ac, person_employer.pre, person_employer.suff) AS phone,
                    person_employer.ext,
                    person_employer.duties,
                    person_employer.current
                FROM
                    ((SELECT
                        person.user_id AS user_id,
                        person.first_name AS first_name,
                        person.last_name AS last_name,
                        employer.previous_name AS company_name,
                        employer.previous_address AS address,
                        employer.previous_city AS city,
                        employer.previous_state AS state,
                        employer.previous_zip AS zip,
                        employer.previous_employment_from AS start_date,
                        employer.previous_employment_to AS end_date,
                        IF(employer.previous_job_title IS NULL, "Undefined", employer.previous_job_title) AS job_title,
                        employer.previous_ac AS ac, 
                        employer.previous_pre AS pre, 
                        employer.previous_suff AS suff,
                        employer.previous_ext AS ext,
                        employer.previous_duties AS duties, 
                        0 AS current
                    FROM person 
                    LEFT JOIN previous_employer AS employer ON employer.user_id = person.user_id
                    WHERE 
                        TRIM(employer.previous_name) <> "" 
                        AND TRIM(employer.previous_name) <> "N/A")
                    UNION
                    (SELECT
                        person.user_id AS user_id,
                        person.first_name AS first_name,
                        person.last_name AS last_name,
                        employer.current_name AS company_name,
                        employer.current_address AS address,
                        employer.current_city AS city,
                        employer.current_state AS state,
                        employer.current_zip AS zip,
                        employer.current_employment_from AS start_date,
                        "" AS  end_date,
                        IF(employer.current_job_title IS NULL, "Undefined", employer.current_job_title) AS job_title,
                        employer.current_ac AS ac, 
                        employer.current_pre AS pre, 
                        employer.current_suff AS suff,
                        employer.current_ext AS ext,
                        employer.current_duties AS duties,
                        1 AS current
                    FROM person 
                    LEFT JOIN current_employer AS employer ON employer.user_id = person.user_id
                    WHERE 
                        TRIM(employer.current_name) <> "" 
                        AND TRIM(employer.current_name) <> "N/A")
                    ) AS person_employer
                ORDER BY person_employer.user_id DESC, person_employer.start_date ASC',
            'filter' => array(
                '0.start_date' => 'dateFormat',
                '0.end_date' => 'dateFormat',
            ),
            'data_map' => array(
                'WorkExperience.employer' => 'person_employer.company_name',
                'WorkExperience.position' => 'person_employer.job_title',
                'WorkExperience.supervisor_phone' => '0.phone',
                'WorkExperience.start_date' => '0.start_date',
                'WorkExperience.end_date' => '0.end_date',
                'WorkExperience.current' => 'person_employer.current',
                'Address.addr1' => 'person_employer.address',
                'Address.phone1' => '0.phone',
                'Address.ext1' => 'person_employer.ext',
                'Address.city' => 'person_employer.city',
                'Address.state' => 'person_employer.state',
                'Address.postal' => 'person_employer.zip',
            ),
        ),
        'importWiringExperience' => array(
            'query' =>
                    'SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.commercial) AS percentage, "commercial" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                        FROM wiring_experience AS wiringExperience
                        WHERE wiringExperience.commercial <> 0 AND wiringExperience.commercial IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.farm) AS percentage, "farm" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.farm <> 0 AND wiringExperience.farm IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.irrigation) AS percentage, "irrigation" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.irrigation <> 0 AND wiringExperience.irrigation IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.home) AS percentage, "home" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.home <> 0 AND wiringExperience.home IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.fire) AS percentage, "fire" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.fire <> 0 AND wiringExperience.fire IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.hvac) AS percentage, "hvac" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.hvac <> 0 AND wiringExperience.hvac IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.refrigeration) AS percentage, "refrigeration" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.refrigeration <> 0 AND wiringExperience.refrigeration IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.residential) AS percentage, "residential" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.residential <> 0 AND wiringExperience.residential IS NOT NULL
                    UNION ALL
                        SELECT TRIM(wiringExperience.user_id) AS user_id, TRIM(wiringExperience.wiring_other) AS percentage, "wiring_other" AS wiring_experience_type, TRIM(wiringExperience.wiring_other_description) as wiring_other_description
                            FROM wiring_experience AS wiringExperience
                            WHERE wiringExperience.wiring_other <> 0 AND wiringExperience.wiring_other IS NOT NULL
                    ORDER BY user_id, wiring_experience_type ASC',
            'filter' => array(
                '0.wiring_experience_type' => array(
                                                'wiringExperienceTypeToId',
                                                ),
            ),
            'data_map' => array(
                'PracticalWorkPercentage.account_id' => '0.user_id',
                'PracticalWorkPercentage.percentage' => '0.percentage',
                'PracticalWorkPercentage.practical_work_percentage_type_id' => '0.wiring_experience_type',
                'PracticalWorkPercentage.descr' => '0.wiring_other_description',
            ),
        ),
        'importPracticalWorkExperience' => array(
            'query' =>
                    'SELECT TRIM(practicalWorkExperience.user_id) AS user_id, TRIM(practicalWorkExperience.apprentice) AS months, "apprentice" AS practical_experience_type, TRIM(practicalWorkExperience.practical_other_description) as practical_other_description
                        FROM practical_experience AS practicalWorkExperience
                        WHERE practicalWorkExperience.apprentice <> 0 AND practicalWorkExperience.apprentice IS NOT NULL
                    UNION ALL
                        SELECT TRIM(practicalWorkExperience.user_id) AS user_id, TRIM(practicalWorkExperience.journeyman) AS months, "journeyman" AS practical_experience_type, TRIM(practicalWorkExperience.practical_other_description) as practical_other_description
                            FROM practical_experience AS practicalWorkExperience
                            WHERE practicalWorkExperience.journeyman <> 0 AND practicalWorkExperience.journeyman IS NOT NULL
                    UNION ALL
                        SELECT TRIM(practicalWorkExperience.user_id) AS user_id, TRIM(practicalWorkExperience.foreman) AS months, "foreman" AS practical_experience_type, TRIM(practicalWorkExperience.practical_other_description) as practical_other_description
                            FROM practical_experience AS practicalWorkExperience
                            WHERE practicalWorkExperience.foreman <> 0 AND practicalWorkExperience.foreman IS NOT NULL
                    UNION ALL
                        SELECT TRIM(practicalWorkExperience.user_id) AS user_id, TRIM(practicalWorkExperience.owner) AS months, "owner" AS practical_experience_type, TRIM(practicalWorkExperience.practical_other_description) as practical_other_description
                            FROM practical_experience AS practicalWorkExperience
                            WHERE practicalWorkExperience.owner <> 0 AND practicalWorkExperience.owner IS NOT NULL
                    UNION ALL
                        SELECT TRIM(practicalWorkExperience.user_id) AS user_id, TRIM(practicalWorkExperience.estimator) AS months, "estimator" AS practical_experience_type, TRIM(practicalWorkExperience.practical_other_description) as practical_other_description
                            FROM practical_experience AS practicalWorkExperience
                            WHERE practicalWorkExperience.estimator <> 0 AND practicalWorkExperience.estimator IS NOT NULL
                    UNION ALL
                        SELECT TRIM(practicalWorkExperience.user_id) AS user_id, TRIM(practicalWorkExperience.practical_other) AS months, "practical_other" AS practical_experience_type, TRIM(practicalWorkExperience.practical_other_description) as practical_other_description
                            FROM practical_experience AS practicalWorkExperience
                            WHERE practicalWorkExperience.practical_other <> 0 AND practicalWorkExperience.practical_other IS NOT NULL
                    ORDER BY user_id, practical_experience_type ASC',
            'filter' => array(
                '0.practical_experience_type' => array(
                                                'practicalWorkExperienceTypeToId',
                                                ),
            ),
            'data_map' => array(
                'PracticalWorkExperience.account_id' => '0.user_id',
                'PracticalWorkExperience.months' => '0.months',
                'PracticalWorkExperience.practical_work_experience_type_id' => '0.practical_experience_type',
                'PracticalWorkExperience.description' => '0.practical_other_description',
            ),
        ),
	); //end settings
    
    /**
     * __construct method
     */
	public function __construct (&$Model = null)
	{
	    // define a model for table queries
		$this->Model = $Model;
	}

    public function formatDOB($value = null)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function formatPhone($value = null, $legacy_data = array())
    {
        return $legacy_data['person']['ac'].$legacy_data['person']['pre'].$legacy_data['person']['suff'];
    }

    /**
     * dateFormat filter
     * -------------------------------
     *
     * Maps DPS date w/ slashes 04/03/1999 to mysql date format 1999-04-03
     *
     * @var str
     * @return str
     * @access public
     */
    public function dateFormat($value = null)
    {
        // uses GenLib to format date
        $value = GenLib::dateFormat($value, 'Y-m-d');
        
        // replace 0000-00-00 with null
        if (preg_match('/0000/', $value))
        {
            $value = null;
        }
        
        return $value;
    }

    /**
     * WiringExperienceTypeToId filter
     * -------------------------------
     *
     * Maps DPS wiring experience type descriptions to the corresponding ELF wiring experience type ID
     *
     * @var array
     * @access public
     */
    public function wiringExperienceTypeToId($value = null, $legacy_data = array())
    {
        $match = trim(strtolower($value));

        switch ($match)
        {
            case "commercial":
                $value = "Commercial, Industrial, Public Buildings and Multi-Family Dwellings over 2 Living Units";
                break;
            case "farm":
                $value = "Farm or Agriculture Wiring";
                break;
            case "irrigation":
                $value = "Irrigation Equipment";
                break;
            case "home":
                $value = "Installing Home Appliances";
                break;
            case "fire":
                $value = "Fire Alarm Systems";
                break;
            case "hvac":
                $value = "Heating and Air Conditioning Equipment";
                break;
            case "refrigeration":
                $value = "Refrigeration Systems";
                break;
            case "residential":
                $value = "Residential (2 Living Units or Less)";
                break;
            case "wiring_other":
                $value = "Other";
                break;
            default:
                $value = "Unknown";
                break;
        }

        // get the practical work percentage type id
        $wiring_experience_type_id = $this->Model->PracticalWorkPercentage->PracticalWorkPercentageType->getTypeIdFromLabel($value);

        return $wiring_experience_type_id;
    }

    /**
     * PracticalWorkExperienceTypeToId filter
     * -----------------------------------------
     *
     * Maps DPS wiring experience type descriptions to the corresponding ELF wiring experience type ID
     *
     * @var array
     * @access public
     */
    public function practicalWorkExperienceTypeToId($value = null, $legacy_data = array())
    {
        $match = trim(strtolower($value));

        switch ($match)
        {
            case "apprentice":
                $value = "Apprenticeship";
                break;
            case "journeyman":
                $value = "Journeyman";
                break;
            case "foreman":
                $value = "Job Superintendent or Job Foreman";
                break;
            case "owner":
                $value = "Owner or Manager";
                break;
            case "estimator":
                $value = "Estimator";
                break;
            case "practical_other":
                $value = "Other";
                break;
            default:
                $value = "Unknown";
                break;
        }

        // get the practical work experience type id
        $practical_work_experience_type_id = $this->Model->PracticalWorkExperience->PracticalWorkExperienceType->getTypeIdFromLabel($value);

        return $practical_work_experience_type_id;
    }
}