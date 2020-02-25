<?php
/**
 * GenerateTrainingProviderRenewalRemindersShell
 *
 * Runs everyday at 12:01 AM.
 *
 * @package    App.Plugin.ContinuingEducation
 * @subpackage App.Plugin.ContinuingEducation.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class GenerateTrainingProviderRenewalRemindersShell extends AppShell
{
    /**
     * Models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'ContinuingEducation.TrainingProvider',
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
    private $_applicableLicenseTypes = array('training-provider');

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

        //query for active training provider licenses whose expiration date is 60 days from today.
        $licenses = $this->License->find(
            'all',
            array(
                'conditions'=> array(
                    'License.license_status_id' => $licenseStatuses,
                    'License.license_type_id'   => $licenseTypes,
                    'License.foreign_plugin'    => 'ContinuingEducation',
                    'License.foreign_obj'       => 'TrainingProvider',
                    'DATE(License.expire_date)' => $expire_date,
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
                    'fp'      => $license['License']['foreign_plugin'],
                    'fo'      => $license['License']['foreign_obj'],
                    'fk'      => $license['License']['foreign_key'],
                    'trigger' => 'training_provider_renewal_reminder',
                );

                $this->TrainingProvider->queueDocs($params);  //call OutputDocuments by model name -> queueDocs.
            }
            catch (Exception $e)
            {
                throw $e;
            }
        }
    }
}