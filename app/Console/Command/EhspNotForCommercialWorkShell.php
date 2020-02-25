<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 *
 * EhspNotForCommercialWorkShell
 * ===========================================
 *
 * Purpose - Flag the accounts provided in the input file as not being available for commercial work.
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 *
 */
class EhspNotForCommercialWorkShell extends AppShell
{
	/**
	 *
     * Load the necessary database models
     *
     */
    public $uses = array(
        'Accounts.Account',
    );

    /**
     * source directory
     */
    private $source_dir = null;

    /**
     * source file name
     */
    private $source_filename = 'ehsp_no_comm_work.csv';

    /**
     * full source path
     */
    private $source_path = null;

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
    private $total_accounts_processed = 0;      // overall account record counter
    private $total_successful_updates = 0;      // overall account record counter
    private $total_failures = 0;                // overall account record counter

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
            $this->heading('Initialize Acccount Update Processes');
            $this->init();

            // recalculate the license expiration date
            $this->heading('Flag the Accounts as Not Available For Commercial Work');
            $this->updateAccounts();

            // report the results
            $this->heading('Report Account Update Results');
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

        // set the path to the source directory
        $this->source_dir = ROOT.DS.'source'.DS.'deployment'.DS.'ehsp';

        // set the souce path to the source file
        $this->source_path = sprintf('%s/%s', $this->source_dir, $this->source_filename);

        // set the output directory and logfile name
        $this->outputDir = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS;
        $this->outputFilename = 'EhspNotForCommercialWork.log';

        // set permissions on the output dir
        $dir = new Folder();
        $dir->chmod($this->outputDir, 0775, true);

        // if the log file already exists, save a .old version and truncate the original so a new one will be made for this run
        $file = new File($this->outputDir.$this->outputFilename, false, 0664);
        if ($file->exists())
        {
            $file->copy($this->outputDir.$this->outputFilename.'.old_'. date('m-d-Y_H:i:s'));

            // create/reset the log file, truncates previous contents
            $lfh = fopen($this->output_file, "w");

            // close the sql file
            fclose($lfh);
        }

        // set the output csv file name
        $this->output_file = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS.'EhspNotForCommercialWork.csv';

        // if the output csv file already exists, save a .old version and truncate the original so a new one will be made for this run
        $file = new File($this->output_file, false, 0664);
        if ($file->exists())
        {
            $file->copy($this->output_file.'.old_'. date('m-d-Y_H:i:s'));
            //$file->delete();

            // create/reset the output file, truncates previous contents
            $ofh = fopen($this->output_file, "w");

            // close the sql file
            fclose($ofh);
        }

        // open the output csv file so the header row can be written before processing begins
        if (($write_file = fopen($this->output_file, "a")) == false)
        {
            throw new Exception('Failed to open the not available for commercial work output file.');
        }

        // define and write header row to output file
        $header_row = array(
            'Legacy ID',
            'ELF Account ID',
            'Name',
            'Successful Update?'
        );

        // write the header row to the output file
        fputcsv($write_file, $header_row);

        // set up the sql file
        $this->sql_file = TMP.'ehsp_not_for_commercial_work.sql';

        // create/reset the sql file, truncates previous contents
        $sfh = fopen($this->sql_file, "w");

        // close the sql file
        fclose($sfh);

        // write the initial log message
        $this->logMessage("Today's date: " . date('m-d-Y H:i:s'));

        // write message to the screen
        $this->out('Account update processes initialized...');
    } // end init()

    /**
     *
     * updateAccounts()
     * ---------------------
     *
     * Wrapper function that handles individually recalculating the expire dates for each license record.
     *
     *
     * @access private
     * @return void
     */
    private function updateAccounts()
    {
        try
        {
            // write section messages to screen and log file
            $this->out('Updating Account Not For Commercial Work flags...');
            $this->logMessage('Updating Account Not For Commercial Work flags...');

            $Accounts = array();

            // check that passed in file exists
            if (file_exists($this->source_path))
            {
                // Open file
                if (($fh = fopen($this->source_path, "r")) !== false)
                {
                    // set an array to hold the row data
                    $rows = array();

                    $count = 0;
                    // read in the CSV
                    while (($row = fgetcsv($fh, 500, ",")) !== false) // && $count < 100)
                    {
                        $rows[] = $row;
                        $count++;
                    }

                    // skip header row
                    unset($rows[0]);

                    // get the license numbers from the file
                    $legacy_ids = hash::extract($rows, '{n}.0');

                    foreach ($legacy_ids as $legacy_id)
                    {
                        // find the license numbers in the DB that match the license numbers in the file
                        $this->Account = ClassRegistry::init('Accounts.Account');
                        $found_account = $this->Account->find(
                            'first',
                            array(
                                'fields' => array('id', 'legacy_id', 'label', 'no_public_contact'),
                                'conditions' => array(
                                    'legacy_id' => $legacy_id
                                ),
                            )
                        );

                        // write the sql
                        $sql_contents = null;

                        // write a sql statement to update the license record
                        if (!empty($found_account))
                        {
                            // add the sql to the sql file to update the account middle initial
                            $sql_contents .= sprintf(
                                'UPDATE accounts SET accounts.no_public_contact = "%s" WHERE accounts.id = %s LIMIT 1;%s',
                                1,
                                $found_account['Account']['id'],
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
                            $this->logMessage('Updating NFCW field in ELF Account for legacy id: ' . $legacy_id . ', name: ' . $found_account['Account']['label']);

                            // build a result row for the output csv
                            $row = array(
                                0 => $legacy_id,                             // EHSP legacy id
                                1 => $found_account['Account']['id'],        // ELF account id
                                2 => $found_account['Account']['label'],     // ELF account name
                                3 => 'Yes'                                   // successful update?
                            );
                        }
                        else
                        {
                            // update the failure counter
                            $this->total_failures++;

                            // log message
                            $this->logMessage('Account for legacy id: ' . $legacy_id . ' could not be found.');

                            // build a result row for the output csv
                            $row = array(
                                0 => $legacy_id,                             // EHSP legacy id
                                1 => null,                                   // ELF account id
                                2 => null,                                   // ELF account name
                                3 => 'No'                                   // successful update?
                            );
                        }

                        // write row to output csv
                        $this->writeRow($row);

                        // update the total accounts processed
                        $this->total_accounts_processed++;
                    }
                }
                else
                {
                    throw new Exception('Could not open the Account Not Available for Commercial Work file.  Accounts not updated.');
                }
            }
            else
            {
                throw new Exception('The Account Not Available for Commercial Work file could not be found.  Accounts not updated.');
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * writeRow() method
     *
     * Closes the output csv file of the account records that were updated
     *
     * @return void
     * @access public
     */
    private function writeRow($row = null)
    {
        if (!file_exists($this->output_file))
        {
            throw new Exception('Account Not For Commercial Work output file could not be found for writing.');
        }

        if (($write_file = fopen($this->output_file, "a")) == false)
        {
            throw new Exception('Failed to open the account not for commercial work output file.');
        }

        // write row to file
        fputcsv($write_file, $row);

        // close the file
        fclose($write_file);
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
            $this->out('Account Not For Commercial Work Update Report');
            $this->out('-----------------------------------------------');
            $this->out('');
            $this->out('Total accounts processed: ' . $this->total_accounts_processed);
            $this->out('Number of Successful Updates: ' . $this->total_successful_updates);
            $this->out('Number of Failed Updates: ' . $this->total_failures);
            $this->out('');
            $this->out('Report File Path: ');
            $this->out($this->output_file);
            $this->out('');
            $this->out('Log File Path: ');
            $this->out($this->outputDir.$this->outputFilename);
            $this->out('');

            // log the report results
            $this->logMessage('Account Not For Commercial Work Update Report');
            $this->logMessage('---------------------------------------------');
            $this->logMessage('');
            $this->logMessage('Total accounts processed: ' . $this->total_accounts_processed);
            $this->logMessage('Number of Successful Updates: ' . $this->total_successful_updates);
            $this->logMessage('Number of Failed Updates: ' . $this->total_failures);
            $this->logMessage('');
        }
        catch (Exception $e)
        {
            throw New Exception('Error reporting results of Account Not For Commercial Work Update.');
        }
    } // end reportResults()
}