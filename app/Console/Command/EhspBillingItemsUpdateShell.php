<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 *
 * EhspBillingItemsUpdateShell
 * ===========================================
 *
 * Purpose - Update the billing items table with historical billing data.
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 *
 */
class EhspBillingItemsUpdateShell extends AppShell
{
	/**
	 *
     * Load the necessary database models
     *
     */
    public $uses = array(
        'Licenses.License',
        'Licenses.Application',
        'Payments.Payment',
        'Payments.PaymentItem',
        'Payments.BillingItem',
        'Reports.Report'
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
    private $total_BI_PI_items_processed = 0;       // overall billing and payment items record counter
    private $total_successful_updates = 0;          // overall billing items record counter
    private $total_failures = 0;                    // overall billing items record counter

    /**
     * file line ending
     */
    private $line_ending = PHP_EOL;

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
            $this->out('inside billing items update shell...');

            // initialize the object
            $this->heading('Initialize Billing Items Update Processes');
            $this->init();

            // rebuild the billing items table
            $this->heading('Update the Billing Items table');
            $this->rebuildBillingItemsTable();

            // report the results
            $this->heading('Report Billing Items Update Results');
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

        // initialize db
        $this->initDB();

        // set the output directory and logfile name
        $this->outputDir = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS;
        $this->outputFilename = 'EhspBillingItemsUpdate.log';

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
        $this->output_file = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS.'EhspBillingItemsUpdate.csv';

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
            throw new Exception('Failed to open the billing items output file.');
        }

        // define and write header row to output file
        $header_row = array(
            'Foreign Plugin',
            'Foreign Object',
            'FO Record ID',
            'License Record ID',
            'License #',
            'Application Record ID',
            'Payment Item Record ID',
            'Payment Item Fee Amount',
            'Payment Date',
            'Payment Received Date',
            'BI/PI record count',
            'Successful Update?',
            'Total Successful Updates',
            'Total Failures'
        );

        // write the header row to the output file
        fputcsv($write_file, $header_row);

        // write the initial log message
        $this->logMessage("Today's date: " . date('m-d-Y H:i:s'));

        // write message to the screen
        $this->out('Billing items update processes initialized...');
    } // end init()

    /**
     *
     * initDB()
     * ----------
     *
     * initialize the DB tables
     *
     * @access private
     * @return void
     */
    private function initDB()
    {
        $this->out('Initializing DB...');

        $this->query("TRUNCATE billing_items", 'default', true);

        $this->out('DB initialized...');
    }

    /**
     *
     * rebuildBillingItemsTable()
     * ---------------------------
     *
     * Rebuilds the billing items table based on existing payment items data
     *
     * @access private
     * @return void
     */
    public function rebuildBillingItemsTable()
    {
        $payment_items = $this->PaymentItem->find(
            'all',
            array(
                'contain' => array(
                    'Application' => array(
                        'License'
                    ),
                    'Payment',
                ),
                'conditions' => array(
                ),
                'order' => array('PaymentItem.id' => 'asc')
            )
        );

        $this->out(count($payment_items));


        // loop through the payment items
        foreach($payment_items as $payment_item)
        {
            $log_contents = null;

            // update the record counter
            $this->total_BI_PI_items_processed++;

            // only process initial applications that have fee data
            if (isset($payment_item['PaymentItem']['fee']))
            {
                // start building the billing item
                $BillingItem = ClassRegistry::init('Payments.BillingItem');
                $billing_item = array(
                    'BillingItem' => array(
                        'foreign_plugin' => $payment_item['Application']['License']['foreign_plugin'],
                        'foreign_obj' => $payment_item['Application']['License']['foreign_obj'],
                        'foreign_key' => $payment_item['Application']['License']['foreign_key'],
                        'fee' => $payment_item['PaymentItem']['fee'],
                        'data'=> serialize($payment_item['Application'])
                    )
                );

                // default the billing date, if the payment creation date is null
                if(is_null($payment_item['Payment']['created']))
                {
                    $billing_item['BillingItem']['date'] = '1900-01-01 00:00:00';
                }
                else
                {
                    $billing_item['BillingItem']['date'] = $payment_item['Payment']['created'];
                    $billing_item['BillingItem']['label'] = $payment_item['PaymentItem']['label'];
                    $billing_item['BillingItem']['owner'] = $payment_item['PaymentItem']['owner'];
                }

                // save the record
                $BillingItem->saveAll($billing_item);

                // update the successful record counter
                $this->total_successful_updates++;

                // build a result row for the output csv
                $row = array(
                    0 => $payment_item['Application']['License']['foreign_plugin'],                    // foreign_plugin
                    1 => $payment_item['Application']['License']['foreign_obj'],                       // foreign_obj
                    2 => $payment_item['Application']['License']['foreign_key'],                       // foreign record id
                    3 => $payment_item['Application']['License']['id'],                                // license record id
                    4 => $payment_item['Application']['License']['license_number'],                    // license number
                    5 => $payment_item['Application']['id'],                         // application record id
                    6 => $payment_item['PaymentItem']['id'],       // payment item record id
                    7 => $payment_item['PaymentItem']['fee'],      // payment item fee amount
                    8 => $payment_item['Payment']['created'],                     // payment/billing date
                    9 => $payment_item['Payment']['payment_received_date'],  // payment received date
                    10 => $this->total_BI_PI_items_processed,                      // billing/payment items record count
                    11 => 'Yes',                                                   // Successful update?
                    12 => $this->total_successful_updates,                         // total successful updates count
                    13 => $this->total_failures                                    // total update failures count
                );

            }
            else
            {
                // update the failure record counter
                $this->total_failures++;

                // build a log file record
                $log_contents .= sprintf(
                    'Failed Update record: Processing plugin: %s, obj: %s, key: %s, lic rec id: %s, lic num: %s, app id: %s%s',
                    $payment_item['Application']['License']['foreign_plugin'],
                    $payment_item['Application']['License']['foreign_obj'],
                    $payment_item['Application']['License']['foreign_key'],
                    $payment_item['Application']['License']['id'],
                    $payment_item['Application']['License']['license_number'],
                    $payment_item['Application']['id'],
                    $this->line_ending
                );

                // open the log file handler
                $lfh = fopen($this->outputDir.$this->outputFilename, 'a');

                // write the log record to the file
                fwrite($lfh, $log_contents);

                // close file
                fclose($lfh);

                // build a result row for the output csv
                $row = array(
                    0 => $payment_item['Application']['License']['foreign_plugin'],                    // foreign_plugin
                    1 => $payment_item['Application']['License']['foreign_obj'],                       // foreign_obj
                    2 => $payment_item['Application']['License']['foreign_key'],                       // foreign record id
                    3 => $payment_item['Application']['License']['id'],                                // license record id
                    4 => $payment_item['Application']['License']['license_number'],                    // license number
                    5 => $payment_item['Application']['id'],                         // application record id
                    6 => 'Check Application via license record',                   // payment item record id
                    7 => 'Check Application via license record',                   // payment item fee amount
                    8 => $payment_item['Payment']['created'],                     // payment/billing date
                    9 => 'Check Application via license record',                   // payment received date
                    10 => $this->total_BI_PI_items_processed,                      // billing/payment items record count
                    11 => 'No',                                                    // Successful update?
                    12 => $this->total_successful_updates,                         // total successful updates count
                    13 => $this->total_failures                                    // total update failures count
                );

            }

            // write row to output csv
            $this->writeRow($row);
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
            throw new Exception('Billing Items Update output file could not be found for writing.');
        }

        if (($write_file = fopen($this->output_file, "a")) == false)
        {
            throw new Exception('Failed to open the billing items update output file.');
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
            $this->out('Billing Items Update Report');
            $this->out('--------------------------------');
            $this->out('');
            $this->out('Total BI/PI items processed: ' . $this->total_BI_PI_items_processed);
            $this->out('Number of Successful Updates: ' . $this->total_successful_updates);
            $this->out('Number of Failed Updates: ' . $this->total_failures);
            $this->out('');
            $this->out('CSV File Path: ');
            $this->out($this->output_file);
            $this->out('');

            // log the report results
            $this->logMessage('Billing Items Update Report');
            $this->logMessage('--------------------------------');
            $this->logMessage('');
            $this->logMessage('Total BI/PI items processed: ' . $this->total_BI_PI_items_processed);
            $this->logMessage('Number of Successful Updates: ' . $this->total_successful_updates);
            $this->logMessage('Number of Failed Updates: ' . $this->total_failures);
            $this->logMessage('');
        }
        catch (Exception $e)
        {
            throw New Exception('Error reporting results of Billing Items Update.');
        }
    } // end reportResults()
}