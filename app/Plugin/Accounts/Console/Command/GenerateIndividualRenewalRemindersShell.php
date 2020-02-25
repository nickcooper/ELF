<?php
App::uses('Router', 'Routing');

/**
 * GenerateIndividualRenewalRemindersShell
 *
 * Runs everyday at 12:21 AM
 *
 * @package    App.Plugin.Accounts
 * @subpackage App.Plugin.Accounts.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class GenerateIndividualRenewalRemindersShell extends AppShell
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
        'Licenses.LicenseStatus',
        'Licenses.LicenseType',
    );

    /**
     * Licenses types this console affects.
     *
     * @var array
     * @access private
     */
    private $_applicableLicenseTypes = array(
        'lead-inspector-risk-assessor',
        'lead-safe-renovator',
        'abatement-worker',
        'abatement-contractor',
        'sampling-technician',
    );

    /**
     * Main entry point
     *
     * @return void
     * @access public
     */
    public function main()
    {
        $licenseTypes = array();
        foreach ($this->_applicableLicenseTypes as $slug)
        {
            $licenseTypes[] = current(
                Set::extract('/LicenseType/id', $this->LicenseType->getLicenseTypeBySlug($slug))
            );
        }

        $licenseStatuses = array(
            $this->LicenseStatus->getStatusId('Active'),
            $this->LicenseStatus->getStatusId('Active, Hold'),
        );

        $expire_date = date('Y-m-d', strtotime('+60 days'));

        //query for active individual licenses whose expiration date is 60 days from today.
        $licenses = $this->License->find(
            'all',
            array(
                'contain' => array('LicenseStatus'),
                'conditions'=> array(
                    'License.license_status_id' => $licenseStatuses,
                    'License.license_type_id'   => $licenseTypes,
                    'License.foreign_plugin'    => 'Accounts',
                    'License.foreign_obj'       => 'Account',
                    'DATE(License.expire_date)' => $expire_date,
                    'LicenseStatus.status !=' => 'Suspended'
                ),
            )
        );

        // submit each acquired license to Output Documents (queueDocs) for document creation.
        foreach ($licenses as $license)
        {
            // set up output document
            try
            {
                $params = array(
                    'fp'         => $license['License']['foreign_plugin'],
                    'fo'         => $license['License']['foreign_obj'],
                    'fk'         => $license['License']['foreign_key'],
                    'license_id' => $license['License']['id'],
                    'trigger'    => 'individual_renewal_reminder',
                );

                $this->Account->queueDocs($params);  //call OutputDocuments by model name -> queueDocs.

            }
            catch (Exception $e)
            {
                throw $e;
            }
        }
    }
}
