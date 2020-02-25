<?php
/**
 * GenerateLateRemindersShell
 *
 * @package Firms.Console
 * @author  Iowa Interactive, LLC.
 */
class GenerateFirmLateRemindersShell extends AppShell
{
    public $uses = array('Licenses.License', 'Firms.Firm');

    public $daysAgo = 55;

    public $errors = array();

    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        // Allows the compare expire date to be manually set from the first arguement
        if (isset($this->args[0]) && is_numeric($this->args[0]))
        {
            $this->daysAgo = $this->args[0];
        }

        // date to compare expired date to. 55 days in the past.
        $expired_date = date('Y-m-d 00:00:00', strtotime($this->daysAgo." days ago"));

        // Disable License afterFind to include foreign obj data
        $this->License->includeForeignData = false;

        // Get expired individual license records
        $expired_licenses = $this->License->find(
            'all',
            array(
                'contain' => array('LicenseStatus'),
                'conditions' => array(
                    array(
                        'License.foreign_plugin' => 'Firms',
                        'License.foreign_obj' => 'Firm',
                        'License.expire_date' => $expired_date,
                        'LicenseStatus.status !=' => 'Converted',
                        'LicenseStatus.status !=' => 'Suspended'
                    )
                )
            )
        );

        // Loop through individual results and queue docs
        if (count($expired_licenses) > 0)
        {
            foreach ($expired_licenses as $expired_license)
            {
                try
                {
                    $this->Firm->queueDocs(
                        array(
                            'fp' => 'Firms',
                            'fo' => 'Firm',
                            'fk' => $expired_license['License']['foreign_key'],
                            'trigger' => 'firm_late_reminder',
                            'license_id' => $expired_license['License']['id']
                        )
                    );
                }
                catch (Exception $e)
                {
                    $this->errors[] = '[Firms.Firm.'.$expired_license['License']['foreign_key'].'] '.$e->getMessage();
                }
            }
        }

        // Deliver errors
        if (count($this->errors) > 0)
        {
            foreach ($this->errors as $error)
            {
                $this->out($error, 1);
            }
        }
    }
}