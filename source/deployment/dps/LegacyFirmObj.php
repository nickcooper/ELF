<?php
// grab the common LegacyObj object
require_once(ROOT.DS.'source'.DS.'deployment'.DS.'vanilla'.DS.'LegacyObj.php');

class LegacyFirmObj extends LegacyObj
{
	/**
     * Data mapping from csv to cake models  
     *
     * @var array
     * @access public
     */
	public $settings = array(
        'importFirms' => array(
        	'query' => 'SELECT
                    electrical_contractor.id,
                    electrical_contractor.user_id,
                    electrical_contractor.contractor_name,
                    electrical_contractor.company_name,
                    electrical_contractor.phone,
                    electrical_contractor.mailing_address,
                    electrical_contractor.mailing_city,
                    electrical_contractor.mailing_state,
                    electrical_contractor.mailing_zip,
                    electrical_contractor.mailing_county,
                    electrical_contractor.business_address,
                    electrical_contractor.business_city,
                    electrical_contractor.business_state,
                    electrical_contractor.business_zip,
                    electrical_contractor.business_county,
                    TRIM(person.first_name) AS first_name,
                    TRIM(person.last_name) AS last_name,
                    electrical_contractor.master_name,
                    electrical_contractor.contractor_name,
                    null as elf_account,
                    null as elf_manager,
                    "Firms" as foreign_plugin,
                    "Firm" as foreign_obj,
                    "Mailing Address" as mailing_address,
                    "Business Address" as business_address,
                    1 as primary_flag

                FROM electrical_contractor
                LEFT JOIN person ON person.user_id = electrical_contractor.user_id
                ',
            'filter' => array(
                'electrical_contractor.phone' => 'numericOnly',
                '0.elf_account' => 'getAccount',
                '0.elf_manager' => 'setManager'
            ),
            'data_map' => array(
                'Firm.legacy_id' => 'electrical_contractor.id',
                'Firm.label' => 'electrical_contractor.company_name',
                'Contact.first_name' => 'electrical_contractor.contractor_name',
                'Contact.last_name' => 'electrical_contractor.contractor_name',
                'Contact.foreign_plugin' => '0.foreign_plugin',
                'Contact.foreign_obj' => '0.foreign_obj',
                'Contact.phone' => 'electrical_contractor.phone',
                'Address.0.foreign_plugin' => '0.foreign_plugin',
                'Address.0.foreign_obj' => '0.foreign_obj',
                'Address.0.label' => '0.mailing_address',
                'Address.0.addr1' => 'electrical_contractor.mailing_address',
                'Address.0.city' => 'electrical_contractor.mailing_city',
                'Address.0.state' => 'electrical_contractor.mailing_state',
                'Address.0.postal' => 'electrical_contractor.mailing_zip',
                'Address.0.county' => 'electrical_contractor.mailing_county',
                'Address.1.foreign_plugin' => '0.foreign_plugin',
                'Address.1.foreign_obj' => '0.foreign_obj',
                'Address.1.label' => '0.business_address',
                'Address.1.primary_flag' => '0.primary_flag',
                'Address.1.phone1' => 'electrical_contractor.phone',
                'Address.1.addr1' => 'electrical_contractor.business_address',
                'Address.1.city' => 'electrical_contractor.business_city',
                'Address.1.state' => 'electrical_contractor.business_state',
                'Address.1.postal' => 'electrical_contractor.business_zip',
                'Address.1.county' => 'electrical_contractor.business_county',
                'Manager' => '0.elf_manager',
                'elf_account' => '0.elf_account',
		    ),
		),  // end account data
	); //end settings
    
    /**
     * __construct method
     */
	public function __construct (&$Model = null)
	{
	    // define a model for table queries
		$this->Model = $Model;
	}

    public function getAccount($value = null, $legacy_data = array())
    {
        $AccountModel = ClassRegistry::init('Accounts.Account', 'Model');
        $account = $AccountModel->getAccountByLegacyID($legacy_data['electrical_contractor']['user_id']);
        if (!$account)
        {
            throw new Exception(
                sprintf(
                    "%s %s's account associated to the firm could not be found.", 
                    $legacy_data['0']['first_name'], 
                    $legacy_data['0']['last_name']
                )
            );
        }
        return $account;
    }

    public function setManager($value = null, $legacy_data = array())
    {
        if (!$legacy_data['0']['elf_account'])
        {
            throw new Exception(
                sprintf(
                    "%s %s's account could not be found to associate to firm as manager.", 
                    $legacy_data['0']['first_name'], 
                    $legacy_data['0']['last_name']
                )
            );
        }
        // Create Manager data
        $data = array(
            array(
                'foreign_plugin' => 'Firms',
                'foreign_obj' => 'Firm',
                'account_id' => $legacy_data['0']['elf_account']['Account']['id'],
                'primary_flag' => 1,
            )
        );
        return $data;
    }
}