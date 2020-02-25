<?php
App::uses('Router', 'Routing');

/**
 * GenerateLateRemindersShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class GenerateIndividualLateRemindersShell extends AppShell
{
    /**
     * Models
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.License', 'Accounts.Account');

    /**
     * Numbers of days ago in the past, used to compared expiration dates.
     * Can be overwritten as a command line argument.
     *
     * @var int
     * @access public
     */
    public $daysAgo = 55;

    /**
     * Main entry point.
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
                        'License.foreign_plugin' => 'Accounts',
                        'License.foreign_obj'    => 'Account',
                        'License.expire_date'    => $expired_date,
                        'LicenseStatus.status !=' => 'Converted',
                        'LicenseStatus.status !=' => 'Suspended'
                    )
                )
            )
        );

        // Enable License afterFind to include foreign obj data
        $this->License->includeForeignData = true;

        $errors = array();

        // Loop through individual results and queue docs
        if (count($expired_licenses) > 0)
        {
            foreach ($expired_licenses as $expired_license)
            {
                try
                {
                    $this->Account->queueDocs(
                        array(
                            'fp'         => 'Accounts',
                            'fo'         => 'Account',
                            'fk'         => $expired_license['License']['foreign_key'],
                            'trigger'    => 'individual_late_reminder',
                            'license_id' => $expired_license['License']['id'],
                        )
                    );
                }
                catch (Exception $e)
                {
                    $errors[] = '[Accounts.Account.'.$expired_license['License']['foreign_key'].'] '.$e->getMessage();
                }
            }
        }

        // Deliver errors
        foreach ($errors as $error)
        {
            $this->out($error);
        }
    }
}
