<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Sanitize', 'Utility');
/**
 *
 * EhspAccountSsnValidationShell
 * ===============================
 *
 * Purpose - Processes all accounts and runs validation against each to identify accounts with bad SSN data.
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 *
 */
class EhspAccountSsnValidationShell extends AppShell
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
    private $total_good_account_ssns = 0;       // overall accounts with good SSN's record counter
    private $total_bad_account_ssns = 0;        // overall accounts with bad SSN's record counter
    private $total_duplicate_ssns = 0;          // overall duplicate SSN's record counter
    private $total_invalid_format_ssns = 0;     // overall invalid format SSN's record counter
    private $total_other_ssn_issues = 0;        // overall other SSN issue record counter

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
            $this->heading('Initialize Acccount Validation Processes');
            $this->init();

            // validate the account SSN's
            $this->heading("Validate the Account SSN's");
            $this->validateAccounts();

            // report the results
            $this->heading('Report Account SSN Validation Results');
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
        $this->outputFilename = 'EhspAccountSsnValidation.log';

        // set permissions on the output dir
        $dir = new Folder();
        $dir->chmod($this->outputDir, 0775, true);

        // if the log file already exists, save a .old version and truncate the original so a new one will be made for this run
        $file = new File($this->outputDir.$this->outputFilename, false, 0664);
        if ($file->exists())
        {
            $file->copy($this->outputDir.$this->outputFilename.'.old_'. date('m-d-Y_H:i:s'));

            // create/reset the log file, truncates previous contents
            $lfh = fopen($this->outputDir.$this->outputFilename, "w");

            // close the sql file
            fclose($lfh);
        }

        // set the output csv file name
        $this->output_file = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS.'EhspAccountSsnValidation.csv';

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
            throw new Exception('Failed to open the account ssn validation output file.');
        }

        // define and write header row to output file
        $header_row = array(
            'First Name',
            'Last Name',
            'SSN',
            'Validation Error Reason',
            'Record Number'
        );

        // write the header row to the output file
        fputcsv($write_file, $header_row);

        // write the initial log message
        $this->logMessage("Today's date: " . date('m-d-Y H:i:s'));

        // write message to the screen
        $this->out('Account validation processes initialized...');
    } // end init()

    /**
     *
     * validateAccounts()
     * ---------------------
     *
     * Wrapper function that handles individually validating the SSNs for each account.
     *
     *
     * @access private
     * @return void
     */
    private function validateAccounts()
    {
        try
        {
            // write section messages to screen and log file
            $this->out('Validating Account SSNs...');
            $this->logMessage('Validating Account SSNs...');

            $Accounts = array();

            // find the license numbers in the DB that match the license numbers in the file
            $this->Account = ClassRegistry::init('Accounts.Account');
            $found_accounts = $this->Account->find(
                'all',
                array(
                    'fields' => array('id', 'label', 'first_name', 'last_name', 'ssn', 'ssn_last_four'),
                    'order' => 'Account.id ASC',
                    //'limit' => 500
                    )
            );

            // loop through the accounts and process the SSN validation
            foreach ($found_accounts as $found_account)
            {
                // increment the counter
                $this->total_accounts_processed++;

                // decrypt the SSN, so to use for validation
                $orig_ssn = $found_account['Account']['ssn'];
                $decrypted_ssn = Sanitize::clean(GenLib::decryptString($orig_ssn), array('encode'));

                // set the lookup conditions
                $conditions = array('Account.ssn' => GenLib::encryptString(preg_replace('/[^0-9]/', '', $decrypted_ssn)));

                // search for a count of all accounts with the provided SSN
                $found_count = $this->Account->find('count', compact('conditions'));

                // update the counters, log message, and validation reason based on number of accounts found
                if ($found_count > 1)
                {
                    $this->total_duplicate_ssns++;
                    $this->total_bad_account_ssns++;
                    $this->logMessage('The SSN associated with this account was used more than once in the system: ' . $found_account['Account']['label']  . ' record: ' . $this->total_accounts_processed);
                    $reason = 'Duplicate SSN';
                }
                elseif ($found_count == 1)
                {
                    $this->total_good_account_ssns++;
                    $this->logMessage('The SSN associated with this account is unique in the system: ' . $found_account['Account']['label']  . ' record: ' . $this->total_accounts_processed);
                    $reason = 'Good SSN';
                }
                else
                {
                    $total_other_ssn_issues++;
                    $this->total_bad_account_ssns++;
                    $this->logMessage('The SSN associated with this account invalidated for some other reason: ' . $found_account['Account']['label'] . ' record: ' . $this->total_accounts_processed);
                    $reason = 'Other Invalid SSN';
                }

                // build a result row for the output csv
                $row = array(
                    0 => $found_account['Account']['first_name'],   // ELF First Name
                    1 => $found_account['Account']['last_name'],    // ELF Last Name
                    2 => $decrypted_ssn,                            // ELF SSN
                    3 => $reason,                                   // Validation error reason
                    4 => $this->total_accounts_processed            // record number
                );

                // if a SSN validates, do not add it to the .csv.  The invalid and duplicate SSNs will be manually removed
                // from the .csv prior to turning it over to the PM or business partners
                if($reason == 'Good SSN')
                {
                    $row[2] = 'Good SSN';
                }

                // write row to output csv
                $this->writeRow($row);
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
        // check for the output file
        if (!file_exists($this->output_file))
        {
            throw new Exception('Account SSN Validation output file could not be found for writing.');
        }

        // try to open the output file
        if (($write_file = fopen($this->output_file, "a")) == false)
        {
            throw new Exception('Failed to open the account ssn validation output file.');
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
            $this->out('Account SSN Validation Report');
            $this->out('-------------------------------');
            $this->out('');
            $this->out('Total accounts processed: ' . $this->total_accounts_processed);
            $this->out('Number of Good SSNs: ' . $this->total_good_account_ssns);
            $this->out('Number of Bad SSNs: ' . $this->total_bad_account_ssns);
            $this->out('Number of Duplicate SSNs: ' . $this->total_duplicate_ssns);
            $this->out('Number of Other SSN issues: ' . $this->total_other_ssn_issues);
            $this->out('');
            $this->out('Report File Path: ');
            $this->out($this->output_file);
            $this->out('');
            $this->out('Log File Path: ');
            $this->out($this->outputDir.$this->outputFilename);
            $this->out('');

            // log the report results
            $this->logMessage('Account SSN Validation Report');
            $this->logMessage('-------------------------------');
            $this->logMessage('');
            $this->logMessage('Total accounts processed: ' . $this->total_accounts_processed);
            $this->logMessage('Number of Good SSNs: ' . $this->total_good_account_ssns);
            $this->logMessage('Number of Bad SSNs: ' . $this->total_bad_account_ssns);
            $this->logMessage('Number of Duplicate SSNs: ' . $this->total_duplicate_ssns);
            $this->logMessage('Number of Other SSN issues: ' . $this->total_other_ssn_issues);
            $this->logMessage('');
        }
        catch (Exception $e)
        {
            throw New Exception('Error reporting results of Account SSN Validation Report.');
        }
    } // end reportResults()
}