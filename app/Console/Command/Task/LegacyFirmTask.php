<?php

App::uses('Security', 'Utility');
App::uses('Hash', 'Utility');
App::uses('LegacyImportTask', 'Console/Command/Task');

/**
 * LegacyFirmImportTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class LegacyFirmTask extends LegacyImportShell
{
    /**
     * Models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.Account',
        'AddressBook.Address',
        'Firms.Firm'
    );

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
        $this->loadLegacyObj('LegacyFirmObj', 'Firm');
    }

    /**
     * importFirms method
     *
     * Import the Firm data
     *
     * @param array $legacy_firm Legacy firm data array
     *
     * @return array
     * @access public
     */
    public function importFirms($legacy_firm = array())
    {
        try
        {
            $legacy_firm = $this->legacyFilters($legacy_firm, 'importFirms');

            // map the legacy data to the elf data array
            $insert_data = $this->_mapData($legacy_firm, 'importFirms');

            // look for previous inserted ELF account
            $previous_firm = $this->Firm->find(
                'first',
                array(
                    'conditions' => array(
                        'Firm.legacy_id' => $insert_data['Firm']['legacy_id']
                    )
                )
            );

            if ($previous_firm)
            {
                return array('duplicate', 2);
            }

            if (empty($insert_data['Firm']['label']) && !empty($insert_data['Contact']['first_name']))
            {
                $insert_data['Firm']['label'] = $insert_data['Contact']['first_name'];
            }

            // Generate slug
            $insert_data['Firm']['slug'] = GenLib::makeSlug($insert_data['Firm']['label']);

            // drop blank addresses
            foreach ($insert_data['Address'] as $key => $address)
            {
                if (empty($insert_data['Address'][$key]['addr1'])
                    && empty($insert_data['Address'][$key]['city'])
                    && empty($insert_data['Address'][$key]['state'])
                    && empty($insert_data['Address'][$key]['portal'])
                )
                {
                    unset($insert_data['Address'][$key]);
                }
            }

            if (isset($this->Firm->Address->validate['county']))
            {
                unset($this->Firm->Address->validate['county']);
            }

            // import the account
            $this->Firm->create();

            if (!$this->Firm->saveAll($insert_data))
            {
                throw new Exception(
                    sprintf(
                        '%s - %s',
                        $insert_data['Firm']['legacy_id'],
                        $this->validationErrorsToString($this->Firm->validationErrors)
                    )
                );
            }

            $master_license = $this->Firm->License->find(
                'first',
                array(
                    'conditions' => array(
                        'License.foreign_plugin' => 'Accounts',
                        'License.foreign_obj' => 'Account',
                        'License.foreign_key' => $insert_data['elf_account']['Account']['id'],
                        'LicenseType.abbr' => array('MA','MB')
                    ),
                    'contain' => array(
                        'LicenseType'
                    )
                )
            );

            if ($master_license)
            {
                $data['FirmLicense']['firm_id'] = $this->Firm->id;
                $data['FirmLicense']['license_id'] = $master_license['License']['id'];

                // Create FirmLicense record
                $this->Firm->FirmLicense->create();
                $this->Firm->FirmLicense->save($data);
            }

        }
        catch (Exception $e)
        {
            // return error
            return array($e->getMessage(), 3);
        }

        // return success
        return array('success', 1);
    }
}
