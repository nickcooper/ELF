<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeEventListener', 'Event');
/**
 *
 * EhspLicenseExpireDateRecalcAndCompareShell
 * ===========================================
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 *
 */
class EhspLicenseExpireDateRecalcAndCompareShell extends AppShell
{
	/**
	 *
     * Load the necessary database models
     * 
     */
    public $uses = array(
        'Licenses.License',
        'Licenses.LicenseType',
        'Licenses.LicenseStatus',
        'Licenses.LicenseExpireReason',
        'ContinuingEducation.CourseRoster',
    );

    public $actsAs = array(
        'Containable',
        'Payments.Payable',
        'Searchable.Searchable',
        'Licenses.License'
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
    private $total_licenses = 0;                // overall license record counter
    private $licenses_processed = 0;            // count of records processed

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
            $this->heading('Initialize License Expiration Date Recalculation Processes');
            $this->init();

            // recalculate the license expiration date
            $this->heading('Recalculate the License Expiration Dates');
            $this->recalculateWrapper();

            // report the results
            $this->heading('Report License Expiration Date Recalculation Results');         
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
        $this->outputFilename = 'EhspLicenseExpireDateRecalcAndCompare.log';

        // set permissions on the output dir
        $dir = new Folder();
        $dir->chmod($this->outputDir, 0775, true);

        // if the log file already exists, save a .old version and delete the original so a new one will be made for this run
        $file = new File($this->outputDir.$this->outputFilename, false, 0664);
        if ($file->exists())
        {
            $file->copy($this->outputDir.$this->outputFilename.'.old_'. date('m-d-Y_H:i:s'));
            $file->delete();
        }

        // set the output csv file name
        $this->output_file = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS.'EhspLicenseExpireDateRecalcAndCompare.csv';

        // if the output csv file already exists, save a .old version and delete the original so a new one will be made for this run
        $file = new File($this->output_file, false, 0664);
        if ($file->exists())
        {
            $file->copy($this->output_file.'.old_'. date('m-d-Y_H:i:s'));
            $file->delete();
        }

        // open the output csv file so the header row can be written before processing begins
        if (($write_file = fopen($this->output_file, "a")) == false) 
        {
            throw new Exception('Failed to open the license expire date recalculation file.');
        }

        // define and write header row to output file
        $header_row = array(
            'License Number',
            'License Status',
            'Original Expiration Date',
            'Recalculated Expiration Date',
            'Application Expiration Date',
            'Course Expiration Date',
            'Reciprocal Expiration Date',
            'Interim Expiration Date',
            'Dates Equal?',
            'Processed Record Number'
        );

        // write the header row to the output file
        fputcsv($write_file, $header_row);

        // set up the sql file
        $this->sql_file = TMP.'ehsp_recalc_expire_date_updates.sql';

        // create/reset the sql file, truncates previous contents
        $sfh = fopen($this->sql_file, "w");

        // close the sql file
        fclose($sfh);

        // write the initial log message
        $this->logMessage("Today's date: " . date('m-d-Y H:i:s'));

        // write message to the screen
        $this->out('License recalculation processes initialized...');
    } // end init()

    /**
     *
     * recalculateWrapper()
     * ---------------------    
     *
     * Wrapper function that handles individually recalculating the expire dates for each license record. 
     * 
     *
     * @access private
     * @return void     
     */
    private function recalculateWrapper() 
    {
        try
        {
            // write section messages to screen and log file
            $this->out('Recalculating license expiration dates...');
            $this->logMessage('Recalculating license expiration dates...');

            $licenses = array();
            $license_types = array(2,3,4,5,6); // set valid license types (no firms or training providers)

            $total_count = $this->License->find('count', array(
                'conditions' => array(
                    'License.license_status_id !=' => 9,            // no Incomplete licenses
                    'License.expire_date !=' => null,               // no licenses where expire date is null
                    'License.license_type_id' => $license_types)    // only valid license types
                )
            );

            // collect the licenses to be recalculated
            $licenses = $this->License->find('all', array(
                'conditions' => array(
                    'License.license_status_id !=' => 9,            // no Incomplete licenses
                    'License.expire_date !=' => null,               // no licenses where expire date is null
                    'License.license_type_id' => $license_types     // only valid license types
                ), 
                'fields' => array(                                  // return fields
                    'License.id',
                    'License.license_number',
                    'License.license_status_id',
                    'License.expire_date'
                ),
                'order' => array('License.id' => 'asc')
                )
            );

            // send each license through the recalculation process
            foreach ($licenses as $license)
            {
                $this->recalculate($license['License']['id'], $license);
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     *
     * recalculate($license_id)
     * -------------------------    
     *
     * Recalculates the expire dates for a given license record. 
     * 
     * @access private
     * @return void     
     *
     * @param $license_id - the license id of the record being processed
     * @param $license - the full license record being processed
     */
    private function recalculate($license_id, $license) 
    {
        try
        {
            // load needed models
            $CourseRoster = ClassRegistry::init('ContinuingEducation.CourseRoster');

            // get the dates of things that expire a license
            $expire_dates = array(
                'application'   => $this->License->getLicenseAppExpDate($license['License']['id']),
                'interim'       => $this->License->getLicenseAppInterimDate($license['License']['id']),
                'course'        => $CourseRoster->getCourseExpiration($license['License']['id']),
                'reciprocal'    => $CourseRoster->getReciprocalExpiration($license['License']['id'])
            );

            // default value
            $earliest_expire_date = null;
            $earliest_expire_reason = null;
            
            // loop the expire dates and choose the earliest
            foreach ($expire_dates as $reason => $date)
            {
                // if null skip it
                if (empty($date))
                {
                    continue;
                }

                // convert date to unixtime
                $date = strtotime($date);
                
                // if we haven't set the earliest date set it and skip to next record
                if ($earliest_expire_date === null)
                {
                    $earliest_expire_date = $date;
                    $earliest_expire_reason = $reason;
                    continue;
                }
                
                // if earliest date has a value then compare and use earliest
                if ($date < $earliest_expire_date)
                {
                    $earliest_expire_date = $date;
                    $earliest_expire_reason = $reason;
                }
            }
            
            // update the license record's expire_date with earliest expiring date
            if ($earliest_expire_date !== null)
            {
                $earliest_expire_date = date('Y-m-d', $earliest_expire_date);
            }

            // set the description
            switch ($earliest_expire_reason)
            {
                case 'application':
                    $description = "application expiration";
                    break;
                case 'interim':
                    $description = "interim expiration";
                    break;
                case 'course':
                    $description = "course expiration";
                    break;
                case 'reciprocal':
                    $description = "reciprocal expiration";
                    break;
                default:
                    $description = 'unknown';
                    break;
            }

            $this->License = ClassRegistry::init('Licenses.License');

            $equal = 'Yes';
            $formatted_license_expire_date = date('Y-m-d', strtotime($license['License']['expire_date']));

            // determine if the old and recalculated expiration dates are equal
            if ($formatted_license_expire_date != $earliest_expire_date)
            {
                $equal = 'No';
            }

            // get the associated license status value
            $license_status = $this->License->LicenseStatus->findById($license['License']['license_status_id']);
            
            // build a result row for the output csv
            $row = array(
                0 => $license['License']['license_number'],         // ELF license number
                1 => $license_status['LicenseStatus']['status'],    // license status
                2 => $formatted_license_expire_date,                // current expiration date, formatted
                3 => $earliest_expire_date,                         // recalculated expiration date
                4 => $expire_dates['application'],                  // application expiration date
                5 => $expire_dates['course'],                       // course expiration date (if exists)
                6 => $expire_dates['reciprocal'],                   // reciprocal expiration date (if exists)
                7 => $expire_dates['interim'],                      // interim expiration date (if exists)
                8 => $equal,                                        // are the old and recalculated dates equal?
                9 => $this->total_licenses                          // record counter (used for processing)
            );

            // write row to output csv
            $this->writeRow($row);

            // write the sql
            $sql_contents = null;

            // write a sql statement to update the license record
            if (!empty($earliest_expire_date) && !empty($license['License']['id']))
            {
                // add the sql to the sql file to update the account middle initial
                $sql_contents .= sprintf(
                    'UPDATE licenses SET licenses.expire_date = "%s" WHERE licenses.id = %s LIMIT 1;%s',
                    $earliest_expire_date,
                    $license['License']['id'],
                    $this->line_ending
                );
            }

            // write a sql statement to set write the expire reason
            if (!empty($license['License']['id']) && !empty($earliest_expire_date) && !empty($description))
            {
                // add the sql to the sql queue to update the phone number for the address associated to the account
                $sql_contents .= sprintf(
                    'INSERT INTO `license_expire_reasons` (`license_id`, `expire_date`, `descr`, `created`, `modified`) VALUES (%s, "%s", "%s", "%s", "%s");%s',
                    $license['License']['id'],
                    $earliest_expire_date,
                    $description,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    $this->line_ending
                );
            }

            // open the sql file handler
            $sfh = fopen($this->sql_file, 'a');

            // write to file
            fwrite($sfh, $sql_contents);

            // close file
            fclose($sfh);

            // update counters
            $this->total_licenses++;
            $this->licenses_processed++;
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
            throw new Exception('License Expiration Recalculation output file could not be found for writing.');
        }

        if (($write_file = fopen($this->output_file, "a")) == false)
        {
            throw new Exception('Failed to open the license expiration recalculation output file.');
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
            $this->out('License Expiration Date Recalculation Report');
            $this->out('---------------------------------------------');
            $this->out('');
            $this->out('Total license count: ' . $this->total_licenses);
            $this->out('Licenses processed: ' . $this->licenses_processed);
            $this->out('');
            $this->out('Report File Path: ');
            $this->out($this->output_file);
            $this->out('');            
            $this->out('Log File Path: ');
            $this->out($this->outputDir.$this->outputFilename);
            $this->out('');            

            // log the report results
            $this->logMessage('License Expiration Date Recalculation Report');
            $this->logMessage('---------------------------------------------');
            $this->logMessage('');
            $this->logMessage('Total licenses count: ' . $this->total_licenses);
            $this->logMessage('Licenses processed: ' . $this->licenses_processed);
            $this->logMessage('');
        }
        catch (Exception $e)
        {
            throw New Exception('Error reporting results of License Expiration Date Recalculation.');
        }
    } // end reportResults()
}