<?php
/**
 * ExpireLicensesShell
 *
 * Expires licenses with the following status types:
 *      - Active
 *      - Active/Hold
 *      - Interim
 *
 * Runs via cron at daily.
 *
 * @package    App.Plugin.Licenses
 * @subpackage App.Plugin.Licenses.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class ExpireLicensesShell extends AppShell
{
    /**
     * include the license model
     */
    var $uses = array('Licenses.License');

    /**
     * which type of license statuses to expire
     */
    var $status_types = array('Active', 'Active/Hold', 'Interim');

    /**
     * expired license status id
     */
    var $expired_status_id = 4;

    /**
     * days to allow for in office processing
     */
    var $processing_days = 5;


    /**
     * main method
     *
     * Method to expire license records.
     *
     * @return void
     * @access public
     */
    public function main()
    {
        // exclude foreign obj data
        $this->License->includeForeignData = false;
        
        // get licenses to expire
        $options = array(
            'contain' => array('LicenseStatus'),
            'conditions'=> array(
                'LicenseStatus.status' => $this->status_types,
                sprintf('DATE(License.expire_date) <= DATE_SUB(NOW(), INTERVAL %s DAY)', $this->processing_days),
            )
        );
        
        // get licenses to expire
        $licenses = $this->License->find('all', $options);
        $total_count = count($licenses);
        
        // counts
        $passed_count = 0;
        $failed_count = 0;
        
        // expire licenses
        foreach ($licenses as $license)
        {
            try
            {
                if ($this->License->expireLicense($license['License']['id'], 'System'))
                {
                    $passed_count++;
                    continue;
                }
            }
            catch (Exception $e)
            {
                $failed_count++;
                $this->out($e->getMessage);
            }
        }
        
        $this->out(sprintf('Found: %s, Expired: %s, Failed: %s', $total_count, $passed_count, $failed_count));
    }
}
?>
