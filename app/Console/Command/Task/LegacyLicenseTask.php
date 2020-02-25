<?php

App::uses('Security', 'Utility');
App::uses('Hash', 'Utility');
App::uses('LegacyImportTask', 'Console/Command/Task');

/**
 * LegacyLicenseImportTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class LegacyLicenseTask extends LegacyImportShell
{
    /**
     * Models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Accounts.Account',
        'Licenses.License',
        'Firms.Firm',
        'Firms.FirmLicense',
        'Accounts.Manager',
        'Notes.Note'
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
        $this->loadLegacyObj('LegacyLicenseObj', 'License');
    }

    /**
     * importLicenses method
     *
     * Import the license data
     *
     * @param array $legacy_license Legacy license data array.
     *
     * @return array
     * @access public
     */
    public function importLicenses($legacy_license = array())
    {
        try
        {
            // filter the legacy data
            $legacy_license = $this->legacyFilters($legacy_license, 'importLicenses');

            // skip those accounts that were not imported to our ELF database
            if (!$account = $this->Account->getAccountByLegacyID($legacy_license['license']['user_id']))
            {
                throw new Exception(
                    sprintf(
                        "%s %s's account could not be found.",
                        $legacy_license['license']['first_name'],
                        $legacy_license['license']['last_name']
                    )
                );
            }

            if (!$license_type = $this->License->LicenseType->findByLabel($legacy_license['license']['license_type']))
            {
                throw new Exception(
                    sprintf(
                        "%s license type could not be found.",
                        $legacy_license['license']['license_type']
                    )
                );
            }

            // look for previous inserted ELF license
            $license = $previous_license = $this->License->getLicenseByType(
                $license_type['LicenseType']['slug'],
                $account['Account']['id'],
                'Account',
                'Accounts'
            );

            if (!$previous_license)
            {
                $license = $this->License->newLicense(
                    $license_type['LicenseType']['slug'],
                    $account['Account']['id'],
                    'Account',
                    'Accounts',
                    false //variant
                );

                if (!$license)
                {
                    throw new Exception('Failed to create license record.');
                }
            }
            else
            {
                if (!$this->License->renewLicense($license['License']['id']))
                {
                    throw new Exception('Failed to renew license record.');
                }
            }

            // get the license record
            $license = $this->License->getApplication($license['License']['id']);

            // map the legacy data to the elf data array
            $insert_data = $this->_mapData($legacy_license, 'importLicenses');

            // adding additional application data
            $insert_data['Application'][0]['open'] = 0;

            // reassign the data so we're not trying to save all of the associated data
            $data = array(
                'License' => array_merge($license['License'], $insert_data['License']),
                'Application' => array(array_merge($license['Application'][0], $insert_data['Application'][0])),
            );

            // unset the blank perjury fields
            unset($data['Application'][0]['perjury_name']);
            unset($data['Application'][0]['perjury_date']);

            // update the license and application
            if (! $this->License->saveAll($data))
            {
                throw new Exception($this->validationErrorsToString($this->License->validationErrors));
            }

            // approve the application
            $this->License->saveApproval($license['License']['id'], false);
        }
        catch (Exception $e)
        {
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return (!$previous_license ? array('success', 1) : array('duplicate', 2));
    }

    /**
     * importFirmLicenses method
     *
     * Import the license data
     *
     * @param array $legacy_firm_license Legacy firm license data array.
     *
     * @return array
     * @access public
     */
    public function importFirmLicenses($legacy_firm_license = array())
    {
        try
        {
            // filter the legacy data
            $legacy_firm_license = $this->legacyFilters($legacy_firm_license, 'importFirmLicenses');

            $firm = $this->Firm->findByLegacyId($legacy_firm_license['electrical_contractor']['id']);/* Agency Specific */

            if (!$license_type = $this->License->LicenseType->findByLabel($legacy_firm_license['0']['license_type']))/* Agency Specific */
            {
                throw new Exception(
                    sprintf(
                        "%s license type could not be found.",
                        $legacy_firm_license['0']['license_type']
                    )
                );
            }

            // look for previous inserted ELF license
            $license = $previous_license = $this->License->getLicenseByType(
                $license_type['LicenseType']['slug'],
                $firm['Firm']['id'],
                'Firm',
                'Firms'
            );

            if (!$previous_license)
            {
                $license = $this->License->newLicense(
                    $license_type['LicenseType']['slug'],
                    $firm['Firm']['id'],
                    'Firm',
                    'Firms',
                    false //variant
                );

                if (!$license)
                {
                    throw new Exception('Failed to create license record.');
                }
            }
            else
            {
                if (!$this->License->renewLicense($license['License']['id']))
                {
                    throw new Exception('Failed to renew license record.');
                }
            }

            // get the license record
            $license = $this->License->getApplication($license['License']['id']);

            // map the legacy data to the elf data array
            $insert_data = $this->_mapData($legacy_firm_license, 'importFirmLicenses');

            // adding additional application data
            $insert_data['Application'][0]['open'] = 0;

            // reassign the data so we're not trying to save all of the associated data
            $data = array(
                'License' => array_merge($license['License'], $insert_data['License']),
                'Application' => array(array_merge($license['Application'][0], $insert_data['Application'][0])),
                'Contractor' => $insert_data['Contractor'],
            );

            // unset the blank perjury fields
            unset($data['Application'][0]['perjury_name']);
            unset($data['Application'][0]['perjury_date']);

            // update the license and application
            if (!$this->License->saveAll($data))
            {
                throw new Exception($this->validationErrorsToString($this->License->validationErrors));
            }

            // approve the application
            $this->License->saveApproval($license['License']['id'], false);
        }
        catch (Exception $e)
        {
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return (!$previous_license ? array('success', 1) : array('duplicate', 2));
    }

    /**
     * importLicenseNotes method
     *
     * Import the license notes data
     *
     * @param array $legacy_data Legacy data array.
     *
     * @return array
     * @access public
     */
    public function importLicenseNotes($legacy_data = array())
    {
        try
        {
            // filter the legacy data
            $legacy_data = $this->legacyFilters($legacy_data, 'importLicenseNotes');

            // map the legacy data to the elf data array
            $data = $this->_mapData($legacy_data, 'importLicenseNotes');

            foreach ($data['Note'] as $note)
            {
                $this->Note->create();
                $this->Note->set($note);

                // update the license and application
                if (! $this->Note->save())
                {
                    throw new Exception($this->validationErrorsToString($this->Note->validationErrors));
                }
            }
        }
        catch (Exception $e)
        {
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return array('success', 1);
    }

    /**
     * importInsuranceInformation method
     *
     * Import the insurance information data
     *
     * @param array $legacy_data Legacy insurance data array.
     *
     * @return array
     * @access public
     */
    public function importInsuranceInformation($legacy_data = array())
    {
        try
        {
            // filter the legacy data
            $legacy_data = $this->legacyFilters($legacy_data, 'importInsuranceInformation');

            // map the legacy data to the elf data array
            $data = $this->_mapData($legacy_data, 'importInsuranceInformation');

            if (isset($this->License->InsuranceInformation->validate['expire_date']['futureExpireDate']))
            {
                unset($this->License->InsuranceInformation->validate['expire_date']['futureExpireDate']);
            }

            // update the license and application
            if (! $this->License->InsuranceInformation->saveAll($data))
            {
                throw new Exception($this->validationErrorsToString($this->License->InsuranceInformation->validationErrors));
            }
        }
        catch (Exception $e)
        {
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return array('success', 1);
    }

    /**
     * importLicenseVariants method
     *
     * Import the license variant data
     *
     * @param array $legacy_data Legacy variant data array.
     *
     * @return array
     * @access public
     */
    public function importLicenseVariants($legacy_data = array())
    {
        try
        {
            // filter the legacy data
            $legacy_variant = $this->legacyFilters($legacy_data, 'importLicenseVariants');

            // skip those accounts that were not imported to our ELF database
            if (!$account = $this->Account->getAccountByLegacyID($legacy_variant[0]['user_id']))
            {
                throw new Exception(
                    sprintf(
                        "Account could not be found for legacy id %s.",
                        $legacy_variant[0]['user_id']
                    )
                );
            }

            // check for an ELF SE license record for this legacy account
            $license = $this->License->find(
                'first',
                array(
                    'conditions' => array(
                        'License.foreign_key' => $account['Account']['id'],
                        'License.foreign_obj' => 'Account',
                        'License.foreign_plugin' => 'Accounts',
                        'License.license_type_id' => 5
                    )
                )
            );

            if (!$license)
            {
                throw new Exception(
                    sprintf(
                        "A valid SE License for legacy id %s could not be found.",
                        $legacy_variant[0]['user_id']
                    )
                );
            }

            // map the legacy data to the elf data array
            $data = $this->_mapData($legacy_variant, 'importLicenseVariants');

            // set the remaining license variant values
            $data['LicenseVariant']['license_id'] = $license['License']['id'];

            // update the license and application
            if (! $this->License->addVariant($data['LicenseVariant']['license_id'], $data['LicenseVariant']['variant_id'], $data))
            {
                throw new Exception($this->validationErrorsToString($this->License->Variant->validationErrors));
            }
        }
        catch (Exception $e)
        {
            return array($e->getMessage(), 3);
        }

        // return success or duplicate
        return array('success', 1);
    }
}