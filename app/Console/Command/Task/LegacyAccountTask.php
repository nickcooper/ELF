<?php

App::uses('Security', 'Utility');
App::uses('Hash', 'Utility');
App::uses('LegacyImportTask', 'Console/Command/Task');

/**
 * LegacyAccountImportTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class LegacyAccountTask extends LegacyImportShell
{
    /**
     * Models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.Account',
        'Accounts.Group',
        'Accounts.WorkExperience',
        'Accounts.WorkExperienceType',
        'AddressBook.Address',
        'Accounts.PracticalWorkPercentageType',
    );

    /**
     * sql file
     */
    private $sql_file       = '';
    private $sql_contents   = '';

    /**
     * record counter
     */
    private $record_count   = 0;

    /**
     * file line ending
     */
    private $line_ending = PHP_EOL;

    /**
     * Initialize the task
     *
     * @return void
     * @access public
     */
    public function initialize()
    {
        // run the parent init
        parent::initialize();

        // Load legacy configuration object
        $this->loadLegacyObj('LegacyAccountObj', 'Account');

        $this->sql_file = TMP.'ehsp_account_info_updates.sql';

        // create/reset the sql file, truncates previous contents
        $sfh = fopen($this->sql_file, "w");

        // close the report file
        fclose($sfh);

        // reset record count
        $this->record_count = 0;
    }

    /**
     * importAccounts method
     *
     * Import the account data
     *
     * @param array $legacy_account Legacy account data array
     *
     * @return array
     * @access public
     */
    public function importAccounts($legacy_account = array())
    {
        try
        {
            $legacy_account = $this->legacyFilters($legacy_account, 'importAccounts');

            $this->record_count++;

            // map the legacy data to the elf data array
            $insert_data = $this->_mapData($legacy_account, 'importAccounts');

            // adjust SSN validation rules for legacy ssn numbers
            $this->Account->validator()->getField('ssn')->setRule(
                'validssn',
                array(
                    'rule' => '/^[0-9]{9}$/',
                    'message' => 'Invalid SSN. Please verify.'
                )
            );

            // assign the default account group
            $group = $this->Group->findByLabel('Default Group');
            $insert_data['Account']['group_id'] = $group['Group']['id'];

            // assign the address foreign obj
            $insert_data['Address']['0']['foreign_plugin'] = 'Accounts';
            $insert_data['Address']['0']['foreign_obj'] = 'Account';
            $insert_data['Address']['0']['primary_flag'] = 1;
            $insert_data['Address']['0']['label'] = 'Home Address';

            if ($this->args[1] == 'ehsp')
            {
                $conditions = array(
                        'Account.ssn' => GenLib::encryptString(preg_replace('/[^0-9]/', '', $legacy_account['person']['ssn'])),
                        'Account.ssn IS NOT null'
                    );
            }
            else
            {
                $conditions = array(
                        'Account.ssn' => GenLib::encryptString(preg_replace('/[^0-9]/', '', $legacy_account[0]['ssn'])),
                        'Account.ssn IS NOT null'
                    );
            }

            // look for previous inserted ELF account
            $previous_account = $this->Account->find(
                'first',
                array(
                    'conditions' => $conditions,
                    'contain' => array('Address')
                )
            );

            if ($previous_account)
            {
                // add previous account id to update the previous account
                $insert_data['Account']['id'] = $previous_account['Account']['id'];

                // add the user_id to the account legacy_id field
                $pat = sprintf(
                    '/^%s|^%s\,|\, %s\,|\, %s$/',
                    $insert_data['Account']['legacy_id'],
                    $insert_data['Account']['legacy_id'],
                    $insert_data['Account']['legacy_id'],
                    $insert_data['Account']['legacy_id']
                );

                if (!preg_match($pat, $previous_account['Account']['legacy_id']))
                {
                    $insert_data['Account']['legacy_id'] = sprintf(
                        '%s, %s',
                        $previous_account['Account']['legacy_id'],
                        $insert_data['Account']['legacy_id']
                    );
                }

                // add previous address ids so we update the previous address
                if (isset($previous_account['Address']) && array_key_exists(0, $previous_account['Address']))
                {
                    $insert_data['Address']['0']['id'] = $previous_account['Address'][0]['id'];
                    $insert_data['Address']['0']['foreign_key'] = $previous_account['Account']['id'];
                }

                // unset the ssn field - or we'll double encrypt the ssn
                unset($insert_data['Account']['ssn']);
            }
            else
            {
                // for ehsp, do a 2nd lookup for an existing account, based on the legacy id
                if ($this->args[1] == 'ehsp')
                {
                    // set the contain to include the associated addresses
                    $contain = array('Address');

                    // find the previous account
                    $previous_account = $this->Account->getAccountByLegacyID($legacy_account['person']['professional_id'], $contain);
                }
            }

            // skip validating the address - import what you can
            $this->Account->Address->validator()->remove('addr');
            $this->Account->Address->validator()->remove('city');
            $this->Account->Address->validator()->remove('county');
            $this->Account->Address->validator()->remove('state');
            $this->Account->Address->validator()->remove('postal');
            $this->Account->Address->validator()->remove('phone1');
            $this->Account->Address->validator()->remove('fax');

            if ($this->args[1] == 'ehsp')
            {
/* // Commenting out for ELF-506 because account middle initials, labels and address home phone numbers
   // have already been imported for EHSP
                // build a result row for the output csv
                $row = array(
                    0 => $legacy_account[0]['first_name'],
                    1 => $legacy_account[0]['middle_name'],
                    2 => $legacy_account[0]['last_name'],
                    3 => $legacy_account['person']['bphone'],
                    4 => 'No'
                );

                $sql_contents = null;

                if (!empty($legacy_account[0]['middle_name']) && !empty($previous_account['Account']['id']))
                {
                    // add the sql to the sql file to update the account middle initial
                    $sql_contents .= sprintf(
                        'UPDATE accounts SET accounts.middle_initial = "%s" WHERE accounts.id = %s LIMIT 1;%s',
                        $legacy_account[0]['middle_name'],
                        $previous_account['Account']['id'],
                        $this->line_ending
                    );

                    // add the sql to the sql file to update the account label, and include the middle initial
                    $sql_contents .= sprintf(
                        'UPDATE accounts SET accounts.label = "%s, %s %s" WHERE accounts.id = %s LIMIT 1;%s',
                        $legacy_account[0]['last_name'],
                        $legacy_account[0]['first_name'],
                        $legacy_account[0]['middle_name'],
                        $previous_account['Account']['id'],
                        $this->line_ending
                    );

                    // log the update as successful in the output csv
                    $row[4] = 'Yes';

                }

                if (!empty($legacy_account['person']['hphone']) && !empty($previous_account['Address'][0]['id']))
                {
                    // add the sql to the sql queue to update the phone number for the address associated to the account
                    $sql_contents .= sprintf(
                        'UPDATE addresses SET addresses.phone1 = "%s" WHERE addresses.id = %s LIMIT 1;%s',
                        $legacy_account['person']['hphone'],
                        $previous_account['Address'][0]['id'],
                        $this->line_ending
                    );

                    // log the update as successful in the output csv
                    $row[4] = 'Yes';
                }
*/

                // build a result row for the output csv
                $row = array(
                    0 => $legacy_account[0]['first_name'],
                    1 => $legacy_account[0]['middle_name'],
                    2 => $legacy_account[0]['last_name'],
                    3 => $legacy_account['person']['bphone'],
                    4 => 'No'
                );

                $sql_contents = null;

                // ELF-506, writing the sql to update the business phone number (bphone )
                if (!empty($legacy_account['person']['bphone']) && !empty($previous_account['Address'][0]['id']))
                {
                    // add the sql to the sql queue to update the phone number for the address associated to the account
                    $sql_contents .= sprintf(
                        'UPDATE addresses SET addresses.phone1 = "%s" WHERE addresses.foreign_obj = "%s" and addresses.foreign_key = %s
                            and addresses.label = "%s" and (addresses.phone1 is null or addresses.phone1 = "") LIMIT 1;%s',
                        $legacy_account['person']['bphone'],
                        'Account',
                        $previous_account['Account']['id'],
                        'Business Address',
                        $this->line_ending
                    );

                    // log the update as successful in the output csv
                    $row[4] = 'Yes';
                }

                // open the sql file handler
                $sfh = fopen($this->sql_file, 'a');

                // write to file
                fwrite($sfh, $sql_contents);

                // close file
                fclose($sfh);

                // write update to output csv
                $this->writeRow($row);
            }
            else
            {
                // import the account
                $this->Account->create();
                if (! $this->Account->saveAll($insert_data))
                {
                    throw new Exception(
                        sprintf(
                            '%s - %s - %s',
                            $insert_data['Account']['first_name'].' '.$insert_data['Account']['last_name'],
                            $insert_data['Account']['ssn_last_four'],
                            $this->validationErrorsToString($this->Account->validationErrors)
                        )
                    );
                }
            }
        }
        catch (Exception $e)
        {
            // return error
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return (!$previous_account ? array('success', 1) : array('duplicate', 2));
    }

    /**
     * importWorkExperience method
     *
     * Import the work experience data
     *
     * @param array $legacy_experience Account work experience data array
     *
     * @return array
     * @access public
     */
    public function importWorkExperience($legacy_experience = array())
    {
        try
        {
            // reset the models
            $this->WorkExperience->create();
            $this->WorkExperience->Account->create();

            // filter the data
            $legacy_experience = $this->legacyFilters($legacy_experience, 'importWorkExperience');

            // skip those accounts that were not imported to our ELF database
            if (!$account = $this->Account->getAccountByLegacyID($legacy_experience['person_employer']['user_id']))
            {
                throw new Exception(
                    sprintf(
                        "Account could not be found for legacy id %s (%s %s).",
                        $legacy_experience['person_employer']['user_id'],
                        $legacy_experience['person_employer']['first_name'],
                        $legacy_experience['person_employer']['last_name']
                    )
                );
            }

            // map the legacy data to the elf data array
            $insert_data = $this->_mapData($legacy_experience, 'importWorkExperience');

            // add the account id
            $insert_data['WorkExperience']['account_id'] = $account['Account']['id'];

            // assign the address foreign obj
            $insert_data['Address']['foreign_plugin'] = 'Accounts';
            $insert_data['Address']['foreign_obj'] = 'WorkExperience';
            $insert_data['Address']['label'] = 'Employer Address';

            // add the work expirence types
            $duties = $legacy_experience['person_employer']['duties'];

            if (!empty($duties) && $duties = unserialize(stripslashes($duties)))
            {
                foreach ($duties as $type)
                {
                    // get the work experience type id
                    if ($exp_type = $this->WorkExperienceType->findByLabel($type))
                    {
                        $insert_data['WorkExperienceType'][] = $exp_type['WorkExperienceType']['id'];
                    }
                }
            }

            // Assign the data to the model for validation of address
            $this->WorkExperience->set($insert_data);

            // adjust validation rules
            $this->WorkExperience->validator()->remove('start_date');
            $this->WorkExperience->validator()->remove('end_date');
            $this->WorkExperience->Address->validator()->remove('addr1');
            $this->WorkExperience->Address->validator()->remove('city');
            $this->WorkExperience->Address->validator()->remove('county');
            $this->WorkExperience->Address->validator()->remove('state');
            $this->WorkExperience->Address->validator()->remove('postal');
            $this->WorkExperience->Address->validator()->remove('phone1');

            // import the data
            if (! $this->WorkExperience->saveAll($insert_data))
            {
                throw new Exception(
                    sprintf(
                        '%s - %s',
                        $insert_data['WorkExperience']['employer'],
                        $this->validationErrorsToString($this->WorkExperience->validationErrors)
                    )
                );
            }
        }
        catch (Exception $e)
        {
            // return error
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return (array('success', 1));

    }

    /**
     * importWiringExperience method
     *
     * Import the associated wiring experience for a given account
     *
     * @param array $legacy_experience Account wiring expirience data array
     *
     * @return array
     * @access public
     */
    public function importWiringExperience($legacy_experience = array())
    {
        try
        {
            // reset the models
            $this->Account->PracticalWorkPercentage->create();
            $this->Account->PracticalWorkPercentage->Account->create();

            // filter the data
            $legacy_wiring_experience = $this->legacyFilters($legacy_experience, 'importWiringExperience');

            // skip those accounts that were not imported to our ELF database
            if (!$account = $this->Account->getAccountByLegacyID($legacy_wiring_experience[0]['user_id']))
            {
                throw new Exception(
                    sprintf(
                        "Account could not be found for legacy id %s.",
                        $legacy_wiring_experience[0]['user_id']
                    )
                );
            }

            // map the legacy data to the elf data array
            $insert_data = $this->_mapData($legacy_wiring_experience, 'importWiringExperience');

            // add the account id
            $insert_data['PracticalWorkPercentage']['account_id'] = $account['Account']['id'];

            // Assign the data to the model
            $this->PracticalWorkPercentage->set($insert_data);

            // import the data
            if (! $this->Account->PracticalWorkPercentage->saveAssociated())
            {
                throw new Exception(
                    sprintf(
                        'Practical Wiring Experience/Percentage failed. Not importing %s.',
                        $insert_data['PracticalWorkPercentage']['descr']
                    )
                );
            }
        }
        catch (Exception $e)
        {
            // return error
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return (array('success', 1));
    }

    /**
     * importPracticalWorkExperience method
     *
     * Import the associated wiring experience for a given account
     *
     * @param array $legacy_experience Account practical work experience data array
     *
     * @return array
     * @access public
     */
    public function importPracticalWorkExperience($legacy_experience = array())
    {
        try
        {
            // reset the models
            $this->Account->PracticalWorkPercentage->create();
            $this->Account->PracticalWorkPercentage->Account->create();
            // filter the data
            $legacy_practical_work_experience = $this->legacyFilters($legacy_experience, 'importPracticalWorkExperience');

            // skip those accounts that were not imported to our ELF database
            if (!$account = $this->Account->getAccountByLegacyID($legacy_practical_work_experience[0]['user_id']))
            {
                throw new Exception(
                    sprintf(
                        "Account could not be found for legacy id %s.",
                        $legacy_practical_work_experience[0]['user_id']
                    )
                );
            }

            // map the legacy data to the elf data array
            $insert_data = $this->_mapData($legacy_practical_work_experience, 'importPracticalWorkExperience');

            // add the account id
            $insert_data['PracticalWorkExperience']['account_id'] = $account['Account']['id'];

            // Assign the data to the model
            $this->PracticalWorkExperience->set($insert_data);

            // import the data
            if (! $this->Account->PracticalWorkExperience->saveAssociated())
            {
                throw new Exception(
                    sprintf(
                        'PracticalWorkExperience failed. Not importing %s.',
                        $insert_data['PracticalWorkExperience']['descr']
                    )
                );
            }
        }
        catch (Exception $e)
        {
            // return error
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return (array('success', 1));
    }

    /**
     * writeRow() method
     *
     * Closes the output csv file of the account records that were updated
     *
     * @param array $row Data array to write to file
     *
     * @return void
     * @access public
     */
    public function writeRow($row = null)
    {
        if (!file_exists(TMP.'accountUpdateLog.csv'))
        {
            throw new Exception('Account update output file could not be found for writing.');
        }

        $this->output_file = TMP.'accountUpdateLog.csv';

        if (($write_file = fopen($this->output_file, "a")) == false)
        {
            throw new Exception('Failed to open the account update log file.');
        }

        // write row to file
        fputcsv($write_file, $row);

        if ($this->args[1] == 'ehsp')
        {
            // close the file
            fclose($write_file);
        }
    }
}
