<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 *
 * EhspUpdateAbatementStartEndDatesShell
 * ===========================================
 *
 * Purpose - Loop through each phase record for each abatement, and set the start and end dates on the abatement
 *           record to the earliest and latest phase date.
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 *
 */
class EhspUpdateAbatementStartEndDatesShell extends AppShell
{
	/**
	 *
     * Load the necessary database models
     *
     */
    public $uses = array(
        'Abatements.Abatement',
        'Abatements.AbatementPhase',
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
    private $total_abatements_processed = 0;    // overall abatement record counter
    private $total_successful_updates = 0;      // overall abatement record counter
    private $total_failures = 0;                // overall abatement record counter

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
            $this->heading('Initialize Abatement Update Processes');
            $this->init();

            // update the abatement start and end dates
            $this->heading('Updating Abatement Start and End Dates');
            $this->updateAbatements();

            // report the results
            $this->heading('Report Abatement Update Results');
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
        $this->outputFilename = 'EhspUpdateAbatementStartEndDates.log';

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
        $this->sql_file = TMP.'ehsp_abatement_start_end_dates.sql';

        // create/reset the sql file, truncates previous contents
        $sfh = fopen($this->sql_file, "w");

        // close the sql file
        fclose($sfh);

        // write the initial log message
        $this->logMessage("Today's date: " . date('m-d-Y H:i:s'));

        // write message to the screen
        $this->out('Abatement update processes initialized...');
    } // end init()

    /**
     *
     * updateAbatements()
     * ---------------------
     *
     * Determines the earliest start and latest end phase date for each abatement record.
     *
     *
     * @access private
     * @return void
     */
    private function updateAbatements()
    {
        try
        {
            // write section messages to screen and log file
            $this->out('Updating Abatement start and end dates...');
            $this->logMessage('Updating Abatement start and end dates...');

            $Abatements = array();

            // find all abatements in the system
            $this->Abatement = ClassRegistry::init('Abatements.Abatement');
            $found_abatements = $this->Abatement->find(
                'all',
                array(
                    'contain' => array('AbatementPhase'),
                )
            );

            foreach ($found_abatements as $found_abatement)
            {
                $tmp_begin_date = null;
                $tmp_end_date = null;
                $data = null;

                // loop through the phases and determine the earliest begin date and latest end date
                // of them all
                foreach($found_abatement['AbatementPhase'] as $abatement_phase)
                {
                    // find the earliest begin date
                    if ($tmp_begin_date == null)
                    {
                        $tmp_begin_date = $abatement_phase['begin_date'];
                    }
                    elseif ($abatement_phase['begin_date'] < $tmp_begin_date)
                    {
                        $tmp_begin_date = $abatement_phase['begin_date'];
                    }

                    // find the latest end date
                    if ($tmp_end_date == null)
                    {
                        $tmp_end_date = $abatement_phase['end_date'];
                    }
                    elseif ($abatement_phase['end_date'] > $tmp_end_date)
                    {
                        $tmp_end_date = $abatement_phase['end_date'];
                    }
                }

                // write the sql
                $sql_contents = null;

                // write a sql statement to update the license record
                if (!empty($found_abatement))
                {
                    // add the sql to the sql file to update the account middle initial
                    $sql_contents .= sprintf(
                        'UPDATE abatements SET abatements.begin_date = "%s", abatements.end_date = "%s"
                        WHERE abatements.id = %s LIMIT 1;%s',
                        $tmp_begin_date,
                        $tmp_end_date,
                        $found_abatement['Abatement']['id'],
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
                    $this->logMessage(
                        'Updating start and end dates for abatement id: ' . $found_abatement['Abatement']['id']
                    );
                }
                else
                {
                    // update the failure counter
                    $this->total_failures++;

                    // log message
                    $this->logMessage('Abatement ' . $found_abatement['Abatement']['id'] . ' could not be found.');
                }

                // update the total accounts processed
                $this->total_abatements_processed++;
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
            $this->out('Abatement Start and End Date Update Report');
            $this->out('------------------------------------------');
            $this->out('');
            $this->out('Total abatements processed: ' . $this->total_abatements_processed);
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
            $this->logMessage('Abatement Start and End Date Update Report');
            $this->logMessage('------------------------------------------');
            $this->logMessage('');
            $this->logMessage('Total abatements processed: ' . $this->total_abatements_processed);
            $this->logMessage('Number of Successful Updates: ' . $this->total_successful_updates);
            $this->logMessage('Number of Failed Updates: ' . $this->total_failures);
            $this->logMessage('');
        }
        catch (Exception $e)
        {
            throw New Exception('Error reporting results of Abatement Start and End Date Update.');
        }
    } // end reportResults()
}