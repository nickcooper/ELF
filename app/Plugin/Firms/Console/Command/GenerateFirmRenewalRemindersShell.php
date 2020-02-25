<?php
/**
 * GenerateFirmRenewalRemindersShell
 *
 * @package App.Plugin.Firms
 * @author  Iowa Interactive, LLC.
 */
class GenerateFirmRenewalRemindersShell extends AppShell
{
    var $uses = array('Licenses.License', 'Firms.Firm');

    /**
     * main method
     *
     * @return void
     * @access public
     */
    function main()
    {
        $expire_date = date('Y-m-d', strtotime('+ 60 days'));

        //query for active firm licenses whose expiration date is 60 days from today.
        $licenses = $this->License->find(
            "all",
            array(
                'contain' => array('LicenseStatus'),
                'conditions'=> array(
                    'License.foreign_plugin' => 'Firms',
                    'License.foreign_obj' => 'Firm',
                    'DATE(License.expire_date)' => $expire_date,
                    'LicenseStatus.status' => array('Active', 'Active, Hold')
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
                    'fp' => 'Firms',
                    'fo' => 'Firm',
                    'fk' => $license['License']['foreign_key'],
                    'trigger' => 'firm_renewal_reminder'
                );

                $this->Firm->queueDocs($params);  //call OutputDocuments by model name -> queueDocs.

            }
            catch (Exception $e)
            {
                throw $e;
            }
        }
    }
}