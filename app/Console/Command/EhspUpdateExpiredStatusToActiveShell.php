<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 *
 * EhspUpdateExpiredStatusToActiveShell
 * =====================================
 *
 * Purpose - Search the database for licenses in expired status but have a future expiration date and reset the
 *           license status to "Active".
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 *
 */
class EhspUpdateExpiredStatusToActiveShell extends AppShell
{
	/**
	 *
     * Load the necessary database models
     *
     */
    public $uses = array(
        'Licenses.License',
    );

    /**
     * Location of output directory.
     *
     * @var array
     * @access public
     */
    public $outputDir = null;

    /**
     * Output file name.
     *
     * @var string
     * @access public
     */
    public $outputFilename = null;

    /**
     *
     * Define and initialize counter variables
     *
     */
    private $total_licenses_processed = 0;      // overall licenses record counter
    private $total_successful_updates = 0;      // overall successful license record counter
    private $total_failures = 0;                // overall failed license record counter

    /**
     * file line ending
     */
    private $line_ending = PHP_EOL;

    /**
     * sql file
     */
    private $sql_file       = '';
    private $sql_contents   = '';

    /**
     *
     * Main method
     * ------------
     *
     * @return void
     * @access public
     *
     */
    public function main()
    {
        try
        {
            // initialize the object
            $this->heading('Initialize License Status Update Processes');
            $this->init();

            // update the license statuses
            $this->heading('Updating License Statuses');
            $this->updateLicenseStatuses();

            // report the results
            $this->heading('Report License Status Update Results');
            $this->reportResults();

        }
        catch (Exception $e)
        {
            // fail and exit
            $this->outFailure(sprintf('%s', $e->getMessage()));
            $this->out('');

            // fail so that Jenkins will report a failure occured
            exit(1);
        }
    } // end main()

    /**
     *
     * init()
     * -------
     *
     * Initialize the object
     *
     * Setup some object vars, etc.
     *
     */
    private function init()
    {
        // run the parent init
        parent::initialize();

        // set the output directory and logfile name
        $this->outputDir = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS;
        $this->outputFilename = 'EhspUpdateExpiredStatusToActive.log';

        // set permissions on the output dir
        $dir = new Folder();
        $dir->chmod($this->outputDir, 0775, true);

        // if the log file already exists, save a .old version and truncate the original
        // so a new one will be made for this run
        $file = new File($this->outputDir.$this->outputFilename, false, 0664);
        if ($file->exists())
        {
            $file->copy($this->outputDir.$this->outputFilename.'.old_'. date('m-d-Y_H:i:s'));

            // create/reset the log file, truncates previous contents
            $lfh = fopen($this->outputDir.$this->outputFilename, "w");

            // close the sql file
            fclose($lfh);
        }

        // set up the sql file
        $this->sql_file = TMP.'ehsp_update_expired_status_to_active.sql';

        // create/reset the sql file, truncates previous contents
        $sfh = fopen($this->sql_file, "w");

        // close the sql file
        fclose($sfh);

        // write the initial log message
        $this->logMessage("Today's date: " . date('m-d-Y H:i:s'));

        // write message to the screen
        $this->out('License status update processes initialized...');
    } // end init()

    /**
     *
     * updateLicenseStatuses()
     * -------------------------
     *
     * Selects the license records with expired statuses and future expiration dates, and sets the license status
     * from expired to active.
     *
     * @access private
     * @return void
     */
    private function updateLicenseStatuses()
    {
        try
        {
            // write section messages to screen and log file
            $this->out('Updating License Statuses...');
            $this->logMessage('Updating License Statuses...');

            // turn off license foreign data
            $this->License->includeForeignData = false;

            // find all abatements in the system
            $this->License = ClassRegistry::init('Licenses.License');
            $found_licenses = $this->License->find(
                'all',
                array(
                    'conditions' => array(
                        'License.license_status_id' => 4,
                        'License.expire_date >' => '2014-02-01 00:00:00',
                        'License.license_type_id' => array('2', '3', '4', '5', '6'),
                    ),
                )
            );

            // turn back on license foreign data
            $this->License->includeForeignData = true;

            // get the license numbers from the query
            $license_ids = Hash::extract($found_licenses, '{n}.License.license_number');

            foreach ($license_ids as $key => $val)
            {

                // write the sql
                $sql_contents = null;

                if(!empty($val))
                {
                    // add the sql to the sql file to update the account middle initial
                    $sql_contents .= sprintf(
                        'UPDATE `licenses` SET licenses.license_status_id = 1
                            WHERE licenses.license_number = "%s" LIMIT 1;%s',
                        $val,
                        $this->line_ending
                    );

                    // open the sql file handler
                    $sfh = fopen($this->sql_file, 'a');

                    // write to file
                    fwrite($sfh, $sql_contents);

                    // close file
                    fclose($sfh);

                    // update the successful counter
                    $this->total_successful_updates++;

                    // log message
                    $this->logMessage('Updating status to active for License: ' . $val);
                }
                else
                {
                    // update the failure counter
                    $this->total_failures++;

                    // log message
                    $this->logMessage('License ' . $val . ' could not be found.');
                }

                // update the total accounts processed
                $this->total_licenses_processed++;
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     *
     * reportResults()
     * ----------------
     *
     * Report out counter results.
     *
     * @access private
     * @return void
     *
     */
    private function reportResults()
    {
        try
        {
            // print report results to the screen
            $this->out('License Status Update Report');
            $this->out('----------------------------');
            $this->out('');
            $this->out('Total licenses processed: ' . $this->total_licenses_processed);
            $this->out('Number of Successful Updates: ' . $this->total_successful_updates);
            $this->out('Number of Failed Updates: ' . $this->total_failures);
            $this->out('');
            $this->out('SQL File Path: ');
            $this->out($this->sql_file);
            $this->out('');
            $this->out('Log File Path: ');
            $this->out($this->outputDir.$this->outputFilename);
            $this->out('');

            // log the report results
            $this->logMessage('License Status Update Report');
            $this->logMessage('----------------------------');
            $this->logMessage('');
            $this->logMessage('Total licenses processed: ' . $this->total_licenses_processed);
            $this->logMessage('Number of Successful Updates: ' . $this->total_successful_updates);
            $this->logMessage('Number of Failed Updates: ' . $this->total_failures);
            $this->logMessage('');
        }
        catch (Exception $e)
        {
            throw New Exception('Error reporting results of License Status Update.');
        }
    } // end reportResults()
}