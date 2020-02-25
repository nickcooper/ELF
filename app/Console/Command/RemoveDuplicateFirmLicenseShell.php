<?php

/**
 * RemoveDuplicateFirmLicenseShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class RemoveDuplicateFirmLicenseShell extends AppShell
{
    /**
     * PHP script memory limit
     *
     * @var array
     * @access private
     */
    private $_scriptMemLimit = '1024M';

    /**
     * load models
     */
    public $uses = array(
        'Firms.FirmLicense'
    );

    public $output_file = '/tmp/duplicate_firm_licenses.sql';

    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        // set the script memory limit
        ini_set('memory_limit', $this->_scriptMemLimit);

        // start time
        $start_time = time();

        $firm_licenses = $this->FirmLicense->find(
            'all',
            array(
                'fields' => array(
                    'firm_id',
                    'license_id'
                ),
                'group' => array(
                    'firm_id, license_id HAVING count(*) > 1'
                ),
            )
        );

        if (count($firm_licenses) > 0)
        {
            $duplicate_ids = array();

            foreach ($firm_licenses as $firm_license)
            {
                $duplicate_records = $this->FirmLicense->find(
                    'all',
                    array(
                        'conditions' => array(
                        'FirmLicense.firm_id' => $firm_license['FirmLicense']['firm_id'],
                        'FirmLicense.license_id' => $firm_license['FirmLicense']['license_id']
                        )
                    )
                );
                if (count($duplicate_records) > 1)
                {
                    // lob off the first record so the ones remaining are duplicates
                    array_shift($duplicate_records);
                    // add ids to the list
                    $duplicate_ids = array_merge($duplicate_ids, Hash::extract($duplicate_records, '{n}.FirmLicense.id'));
                }
            }

            // if duplicates found, delete them.
            if (count($duplicate_ids) > 0)
            {
                $this->out(sprintf('%s duplicate records found', count($duplicate_ids)));

                if (($write_file = fopen($this->output_file, "w")) == false)
                {
                    throw new Exception('Failed to open write file.');
                }

                fwrite($write_file,
                    sprintf("DELETE FROM firm_licenses WHERE id IN (%s);\n",
                        implode(', ', $duplicate_ids)
                    )
                );
                fclose($write_file);
            }
        }
        else
        {
            $this->out("No duplicate records found");
        }
    }


}