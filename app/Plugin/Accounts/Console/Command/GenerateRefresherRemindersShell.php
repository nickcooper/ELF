<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Router', 'Routing');

/**
 * GenerateRefresherRemindersShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class GenerateRefresherRemindersShell extends AppShell
{
    /**
     * Models
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.License', 'Accounts.Account', 'ContinuingEducation.CourseRoster');

    /**
     * Numbers of days in the future, used to compared expiration dates.
     * Can be overwritten as a command line argument.
     *
     * @var int
     * @access public
     */
    public $daysInFuture = 90;

    /**
     * Main entry point
     *
     * @return void
     * @access public
     */
    public function main()
    {
        $errors = array();

        // Allows the compare expire date to be manually set from the first arguement
        if (isset($this->args[0]) && is_numeric($this->args[0]))
        {
            $this->daysInFuture = $this->args[0];
        }

        // date to compare expired date to. 90 days in the future.
        $expired_date = date('Y-m-d', strtotime(sprintf('+%d days', $this->daysInFuture)));

        // find all the course roster records with an expiration date that matches the
        // calculated expire date (today + 90 days)
        $expired_courses = $this->CourseRoster->find(
            'all',
            array(
                'contain' => array(
                    'CourseSection' => array(
                        'CourseCatalog' =>array('CourseCatalogLicenseType')
                    )
                ),
                'conditions' => array(
                    'CourseRoster.expire_date' => $expired_date,
                )
            )
        );

        // if course roster records are found, process each record.
        if (count($expired_courses) > 0)
        {
            foreach ($expired_courses as $expired_course)
            {
                // for each roster record, find all the associated licenses for that person
                // that have the same calculated expire date
                $licenses = $this->License->find(
                    'all',
                    array(
                        'contain' => array('LicenseStatus', 'LicenseType'),
                        'conditions' => array(
                            'License.foreign_plugin' => 'Accounts',
                            'License.foreign_obj'    => 'Account',
                            'License.foreign_key'    => $expired_course['CourseRoster']['account_id'],
                            'License.expire_date ='  => $expired_date.' 00:00:00',
                            'LicenseStatus.status !=' => 'Converted',
                            'LicenseStatus.status !=' => 'Suspended'
                        )
                    )
                );

                if (count($licenses) > 0)
                {
                    // if licenses are found, process each license
                    foreach ($licenses as $license)
                    {
                        // if the course being processed applies to the license being processed, add the
                        // refresher reminder letter to the queue
                        foreach($expired_course['CourseSection']['CourseCatalog']['CourseCatalogLicenseType']
                            as $catalog_lic_type
                        )
                        {
                            if($catalog_lic_type['license_type_id'] == $license['LicenseType']['id'])
                            {
                                try
                                {
                                    $this->Account->queueDocs(
                                        array(
                                            'fp'         => 'Accounts',
                                            'fo'         => 'Account',
                                            'fk'         => $license['License']['foreign_key'],
                                            'trigger'    => 'individual_refresher_reminder',
                                            'license_id' => $license['License']['id'],
                                        )
                                    );
                                }
                                catch (Exception $e)
                                {
                                    $errors[] = '[Accounts.Account.'.$license['License']['foreign_key'].'] '
                                        .$e->getMessage();
                                }
                            }
                        }
                    }
                }
            }
        }

        // Deliver errors
        if (count($errors) > 0)
        {
            foreach ($errors as $error)
            {
                $this->out($error);
            }
        }
    }
}
