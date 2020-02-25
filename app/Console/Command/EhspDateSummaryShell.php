<?php

/**
 * EhspDateSummaryShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class EhspDateSummaryShell extends AppShell
{
    /**
     * load database models
     */
    public $uses = array(
        'Licenses.License',
        'ContinuingEducation.CourseRoster',
    );

    /**
     * number of records to process at one time
     */
    private $proc_num = 1000;

    /**
     * report file
     */
    private $report_dir_path 	= TMP;
    private $report_filename 	= 'ehsp_date_summary_report.csv';
    private $report_file 		= '';
    private $report_contents 	= '';

    /**
     * file line ending
     */
    private $line_ending = PHP_EOL;

    /**
     * source file success count
     */
    private $counts = array(
    	'increment' 	=> 0,
        'applications' 	=> array('pass' => 0, 'fail' => 0, 'skip' => 0),
        'courses' 		=> array('pass' => 0, 'fail' => 0, 'skip' => 0)
    );




    //---------------------------------
    // MAIN PROCESS METHODS
    //---------------------------------

    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        try
        {
            // initialize the object
            $this->init();

            // test the setup
            $this->heading('Validating Shell Setup');
            $this->validate();

            // create the report
            $this->heading('Summarizing Dates');
            $this->dateSummary();
        }
        catch (Exception $e)
        {
            // fail and exit
            $this->outFailure(sprintf('%s', $e->getMessage()));
            $this->out('');

            // fail so that Jenkins will report a failure occured
            exit(1);
        }
    }

    /**
     * Process dateSummary
     */
    private function dateSummary ()
    {
        try
        {
            // turn off license foreign data
            $this->License->includeForeignData = false;

            // get max id of all license records
            $lic_max_id = $this->License->query(
                'SELECT max(l.id) AS max_id FROM licenses AS l;'
            );
            $lic_max_id = $lic_max_id[0][0]['max_id'];

            // open the report file handler
            $rfh = fopen($this->report_file, 'a');

            $report = "";

            // loop the records
            $report_count = 0;
            for ($i=1; $i <= $lic_max_id; $i++)
            {
                try
                {
                    // attempt to find a record
                    $license = $this->License->find(
                        'first',
                        array(
                            'contain' => array('LicenseType', 'LicenseStatus'),
                            'conditions' => array(
                                'License.id' => $i,
                                'License.foreign_obj' => array('Account', 'Firm', 'TrainingProvider')
                            )
                        )
                    );

                    // if no license found skip it
                    if (!$license) { continue; }

                    debug($license['License']['id']);

                    // increment the loop
                    $report_count++;
                    $this->counts['increment']++;

                    // get some foreign obj data
                    $fo = $license['License']['foreign_obj'];
                    $ForeignModel = ClassRegistry::init(Inflector::pluralize($fo).'.'.$fo);
                    $foreign_obj = $ForeignModel->find(
                        'first',
                        array(
                            'contain' => array('PrimaryAddress'),
                            'conditions' => array($fo.'.id' => $license['License']['foreign_key'])
                        )
                    );

                    // some data values
                    $lic_expire_date = GenLib::dateFormat($license['License']['expire_date'], 'm-d-Y');
                    $app_expire_date = GenLib::dateFormat($this->License->getLicenseAppExpDate($license['License']['id']), 'm-d-Y');
                    $course_expire_date = '';
                    $ssn_last_four = '';

                    if ($fo == 'Account')
                    {
                        $course_expire_date = GenLib::dateFormat($this->CourseRoster->getCourseExpiration($license['License']['id']), 'm-d-Y');
                        $ssn_last_four = $foreign_obj['Account']['ssn_last_four'];
                    }

                    // default data array
                    $data = array();

                    // format the report data
                    $data['ojbect'] = $license['License']['foreign_obj'];
                    $data['id'] = $license['License']['id'];
                    $data['label'] = $license['License']['label'];
                    $data['number'] = $license['License']['license_number'];
                    $data['type'] = $license['LicenseType']['label'];
                    $data['status'] = $license['LicenseStatus']['status'];
                    $data['exp_date'] = $lic_expire_date;
                    $data['app_exp_date'] = $app_expire_date;
                    $data['course_exp_date'] = $course_expire_date;
                    $data['ssn_last_four'] = $ssn_last_four;
                    $data['phone'] = $foreign_obj['PrimaryAddress']['phone1'];
                    $data['address'] = implode(
                        ' ',
                        array(
                            $foreign_obj['PrimaryAddress']['addr1'],
                            $foreign_obj['PrimaryAddress']['addr2'],
                            $foreign_obj['PrimaryAddress']['city'],
                            $foreign_obj['PrimaryAddress']['state'],
                            $foreign_obj['PrimaryAddress']['postal'],
                        )
                    );

                    // add the data to the report
                    $report .= '"'.implode('","', $data).'"';

                    // add the report line ending
                    $report .= $this->line_ending;

                    // write to file
                    if ($report_count >= 1000)
                    {
                        fwrite($rfh, $report);
                        $report = '';
                        $report_count = 0;
                    }
                }
                catch (Exception $e)
                {
                    fclose($rfh);
                    debug($e->getMessage());
                    exit();
                }
            }

            // make sure we write the last report string to the file
            if (!empty($report))
            {
                fwrite($rfh, $report);
            }

            // close file
            fclose($rfh);
        }
        catch (Exception $e)
        {
            $this->outFailure('`-_ MAJOR SCRIPT MALFUNCTION _-`');
            $this->outFailure($e->getMessage());

            exit();
        }

        // cp report to user home
        exec(sprintf('cp %s ~/%s', $this->report_file, $this->report_filename));
    }

    /**
     * Validate the setup
     */
    private function validate ()
    {
        try
        {
            // attempt to validate the configuration values
            switch (false)
            {
                // report file
                case file_exists($this->report_file):
                    throw new Exception (sprintf('Report file %s', $this->report_file));
                break;
            }

            $this->outSuccess('Shell setup validates.');
            $this->out('');
        }
        catch (Exception $e)
        {
            throw new Exception (sprintf('Failed to validate setup. %s is missing or invalid.', $e->getMessage()));
        }
    }

    /**
     * Initialize the object
     *
     * Setup some object vars, etc.
     */
    private function init ()
    {
        // add the tmp dir path to report file
        $this->report_file = TMP.$this->report_filename;

        // create/reset the report file, truncates previous contents
        $rfh = fopen($this->report_file, "w");

        fwrite(
            $rfh,
            sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"%s',
                'Object Type',
                'License ID',
                'License Label',
                'License #',
                'License Type',
                'License Status',
                'Lic Exp Date',
                'App Exp Date',
                'Course Exp Date',
                'SSN',
                'Phone',
                'Address',
                $this->line_ending
            )
        );

        // close the report file
        fclose($rfh);
    }

    /**
     * Write string to report contents
     */
    private function reportFile ($str = '')
    {
        $this->report_contents .= sprintf('%s%s', $str, $this->line_ending);
    }
}