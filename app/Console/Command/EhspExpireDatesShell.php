<?php

/**
 * EhspExpireDatesShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class EhspExpireDatesShell extends AppShell
{
    /**
     * load database models
     */
    public $uses = array(
        'Licenses.License',
        'ContinuingEducation.CourseRoster'
    );

    /**
     * source file name
     */
    private $source_file = 'ehsp_expire_dates.csv';

    /**
     * report file
     */
    private $report_dir_path 	= TMP;
    private $report_filename 	= 'ehsp_expire_dates_report.csv';
    private $report_file 		= '';
    private $report_contents 	= '';

    /**
     * sql file
     */
    private $sql_dir_path    = TMP;
    private $sql_filename    = 'ehsp_expire_dates.sql';
    private $sql_file        = '';
    private $sql_contents    = '';

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
            // turn off foreign key constraints
            $this->CourseRoster->query('SET foreign_key_checks = 0;');

             // initialize the object
            $this->init();

            // test the setup
            $this->heading('Validating Shell Setup');
            $this->validate();

            // import the data
            $this->heading('Processing Expire Dates');
            $this->ProcExpireDates();

            // turn on foreign key constraints
            $this->CourseRoster->query('SET foreign_key_checks = 1;');
        }
        catch (Exception $e)
        {
            // turn on foreign key constraints
            $this->CourseRoster->query('SET foreign_key_checks = 1;');

            // fail and exit
            $this->outFailure(sprintf('%s', $e->getMessage()));
            $this->out('');

            // fail so that Jenkins will report a failure occured
            exit(1);
        }
    }

    /**
     * Process expire date source file
     */
    private function ProcExpireDates ()
    {
        try
        {
            // open the file
            if (!$fh = fopen($this->source_file, 'r'))
            {
                throw new Exception(sprintf('Failed to open source file %s.', $this->source_file));
            }

            // loop the file, skip column headings (row 1)
            while (($row = fgetcsv($fh, 1000, ",")) !== false)
            {
                try
                {
                    // reset the report var
                    $report = '';

                    // increment the loop
                    $this->counts['increment']++;

                    // skip the column headings
                    if ($this->counts['increment'] == 1)
                    {
                        // add column headings to report file
                        $this->report_contents = sprintf(
                            '"%s","Act App Expire", "App Update","App Msg","Course Update","Course Msg"',
                            implode('","', $row)
                        );

                        // don't process them though, skip to the next record
                        continue;
                    }

                    // add the report line ending
                    $report .= $this->line_ending;

                    // process the file row
                    if (!count($row))
                    {
                        throw new Exception ('Row was empty.');
                    }

                    /**
                     * extract some useful values from the source data
                     *
                     * first_name           (source col 0: First Name)
                     * last_name            (source col 1: Last Name)
                     * license_number       (source col 5: Last Name)
                     * legacy_app_date      (source col 32: old_ExpDate)
                     * elf_app_date         (source col 33: New_Exp)
                     * correct_app_date     (source col 34: Correct_Exp)
                     * legacy_course_date   (source col 36: Refresh_old)
                     * elf_course_date      (source col 37: Refresh_new)
                     * correct_course_date  (source col 38: Refresher_Correct)
                     */
                    extract($this->extractVars($row));

                    // default report values
                    $report .= sprintf('"%s"', implode('","', $row));

                    // if the correct date is empty use the legacy date
                    $correct_app_date = (strtotime($correct_app_date) ? $correct_app_date : $legacy_app_date);
                    $correct_course_date = (strtotime($correct_course_date) ? $correct_course_date : $legacy_course_date);

                    // look up the license and current application
                    $this->License->includeForeignData = false;
                    $license = $this->License->find(
                        'first',
                        array(
                            'conditions' => array(
                                'License.license_number' => $license_number,
                                'License.foreign_obj' => 'Account'
                            ),
                            'contain' => array('CurrentApplication', 'LicenseType')
                        )
                    );

                    // make sure we have a license with a valid/active application
                    if (!$license || !GenLib::isData($license, 'CurrentApplication', array('id')))
                    {
                        throw new Exception ('License or Application does not exist');
                    }

                    // add actual license application expire date to report
                    $report .= sprintf(',"%s"', $license['CurrentApplication']['expire_date']);

                    try
                    {
                        // process the application dates
                        $this->procAppDates($license, $elf_app_date, $correct_app_date);

                        // add the success to the report for this row
                        $report .= sprintf(
                            ',"PASS","Updated application date from %s to %s"',
                            GenLib::dateFormat($elf_app_date, 'Y-d-m'),
                            GenLib::dateFormat($correct_app_date, 'Y-m-d')
                        );
                    }
                    catch (Exception $app_error)
                    {
                        // add the failure to the report for this row
                        $report .= sprintf(
                            ',"FAIL","%s"',
                            $app_error->getMessage()
                        );
                    }

                    try
                    {
                        // process the course dates
                        $this->procCourseDates($license, $elf_course_date, $correct_course_date);

                        // add the success to the report for this row
                        $report .= sprintf(
                            ',"PASS","Updated course date from %s to %s"',
                            GenLib::dateFormat($elf_app_date, 'Y-d-m'),
                            GenLib::dateFormat($correct_app_date, 'Y-m-d')
                        );
                    }
                    catch (Exception $course_error)
                    {
                        // add the failure to the report for this row
                        $report .= sprintf(
                            ',"FAIL","%s"',
                            $course_error->getMessage()
                        );
                    }
                }
                catch (Exception $e)
                {
                    // add the failure to the report for this row
                    $report .= sprintf(
                        '"FAIL","%s","FAIL","%s"',
                        $e->getMessage(),
                        $e->getMessage()
                    );
                }

                // Add the results to the report file contents
                $this->report_contents .= $report;
            }
        }
        catch (Exception $e)
        {
            $this->outFailure('`-_ MAJOR SCRIPT MALFUNCTION _-`');
            $this->outFailure($e->getMessage());

            exit();
        }

        // close the file handler
        fclose($fh);

        // open the report file handler
        $rfh = fopen($this->report_file, 'a');

        // write to file
        fwrite($rfh, $this->report_contents);

        // close file
        fclose($rfh);

        // cp report to user home
        exec(sprintf('cp %s ~/%s', $this->report_file, $this->report_filename));

        // run the sql updates
        $this->License->query($this->sql_contents);

        // open the sql file handler
        $sfh = fopen($this->sql_file, 'a');

        // write to file
        fwrite($sfh, $this->sql_contents);

        // close file
        fclose($sfh);

        // cp sql to user home
        exec(sprintf('cp %s ~/%s', $this->sql_file, $this->sql_filename));
    }




    //---------------------------------
    // SUPPORTING MEHTODS
    //---------------------------------

    /**
     * Process the course dates
     */
    private function procCourseDates ($license = array(), $elf_course_date = false, $correct_course_date = false)
    {
        // validate correct_course_date is a valid date/time
        if (!strtotime($correct_course_date))
        {
            throw new Exception ('Correct course expire date could not be converted to a unix timestamp');
        }

        // look up the account and course rosters
        $account = $this->CourseRoster->Account->find(
            'first',
            array(
                'conditions' => array('Account.id' => $license['License']['foreign_key']),
                'contain' => array('CourseRoster' => array('CourseSection'))
            )
        );

        // get the expire dates for course/reciprocal
        $credit_expire_data = $this->CourseRoster->calcCourseCredits($license['License']['id']);

        // convert the str date values to date objects
        $elf_course_date_obj = new DateTime($elf_course_date);
        $correct_course_date_obj = new DateTime($correct_course_date);
        $credit_expire_course_date_obj = new DateTime($credit_expire_data['CourseRoster']['expire_date']);

        // if the elf_course_date does not match either course exipre date - fail
        if ($elf_course_date_obj != $credit_expire_course_date_obj)
        {
            throw new Exception ('Source file refresher dates does not match ELF course or reciprocal date.');
        }

        // course cycle in months
        $cycle = $credit_expire_data['CourseRoster']['CourseSection']['CourseCatalog']['cycle'];

        // future date = +1 cycle
        $credit_expire_course_future_date_obj = new DateTime($credit_expire_data['CourseRoster']['expire_date']);
        $credit_expire_course_future_date_obj->add(new DateInterval(sprintf('P%sM', $cycle)));

        // is the source correct date >= to current date +1 cycle
        if ($correct_course_date_obj >= $credit_expire_course_future_date_obj)
        {
            throw new Exception (sprintf('Course expire date is %s months older than source file correct date', $cycle));
        }

        // past date = -1 cycle
        $credit_expire_course_past_date_obj = new DateTime($credit_expire_data['CourseRoster']['expire_date']);
        $credit_expire_course_past_date_obj->sub(new DateInterval(sprintf('P%sM', $cycle)));

        // is the source correct date <= to current date -1 cycle
        if ($correct_course_date_obj <= $credit_expire_course_past_date_obj)
        {
            throw new Exception (sprintf('Course expire date is %s months newer than source file correct date', $cycle));
        }

        // add the sql to the sql queue to update course expire date
        $this->sql_contents .= sprintf(
            'UPDATE course_rosters SET course_rosters.expire_date = "%s" WHERE course_rosters.id = %s LIMIT 1; ',
            $correct_course_date_obj->format('Y-m-d'),
            $credit_expire_data['CourseRoster']['id']
        );

        // add the sql to the sql queue to update license expire date
        $this->sql_contents .= sprintf(
            'UPDATE licenses SET licenses.expire_date = "%s" WHERE licenses.id = %s AND DATE(licenses.expire_date) = DATE("%s") LIMIT 1; ',
            $correct_course_date_obj->format('Y-m-d'),
            $license['License']['id'],
            $elf_course_date_obj->format('Y-m-d')
        );

        return true;
    }

    /**
     * Process the applcation dates
     */
    private function procAppDates ($license = array(), $elf_app_date = false, $correct_app_date = false)
    {
        // validate correct_app_date is a valid date/time
        if (!strtotime($correct_app_date))
        {
            throw new Exception ('Correct application expire date could not be converted to a unix timestamp');
        }

        // convert the str date values to date objects
        $elf_app_date_obj = new DateTime($elf_app_date);
        $correct_app_date_obj = new DateTime($correct_app_date);
        $current_app_date_obj = new DateTime($license['CurrentApplication']['expire_date']);

        // does the elf app date match our applications expire date
        if ($elf_app_date_obj != $current_app_date_obj)
        {
            throw new Exception ('Application expire date does not match source file');
        }

        // future date = +1 cycle -3 days
        $current_app_future_date_obj = new DateTime($license['CurrentApplication']['expire_date']);
        $current_app_future_date_obj->add(new DateInterval(sprintf('P%sM', $license['LicenseType']['cycle'])))->sub(new DateInterval('P3D'));

        // is the source correct date >= to current date +1 cycle -3 days
        if ($correct_app_date_obj >= $current_app_future_date_obj)
        {
            throw new Exception (sprintf('Application expire date is %s months older than source file correct date', $license['LicenseType']['cycle']));
        }

        // past date = -1 cycle +3 days
        $current_app_past_date_obj = new DateTime($license['CurrentApplication']['expire_date']);
        $current_app_past_date_obj->sub(new DateInterval(sprintf('P%sM', $license['LicenseType']['cycle'])))->add(new DateInterval('P3D'));

        // is the source correct date <= to current date -1 cycle +3 days
        if ($correct_app_date_obj <= $current_app_past_date_obj)
        {
            throw new Exception (sprintf('Application exipre date is %s months newer than source correct file correct date', $license['LicenseType']['cycle']));
        }

        // add the sql to the sql queue to update application expire date
        $this->sql_contents .= sprintf(
            'UPDATE applications SET applications.expire_date = "%s" WHERE applications.id = %s LIMIT 1; ',
            $correct_app_date_obj->format('Y-m-d'),
            $license['CurrentApplication']['id']
        );

        // add the sql to the sql queue to update license expire date
        $this->sql_contents .= sprintf(
            'UPDATE licenses SET licenses.expire_date = "%s" WHERE licenses.id = %s AND DATE(licenses.expire_date) = DATE("%s") LIMIT 1; ',
            $correct_app_date_obj->format('Y-m-d'),
            $license['License']['id'],
            $elf_app_date_obj->format('Y-m-d')
        );

        return true;
    }

    /**
     * Extract some useful values for processing
     */
    private function extractVars ($row = array())
    {
        $return_data = array(
            // general licensee stuff
            'first_name'        => $row[0],
            'last_name'         => $row[1],
            'license_number'    => $row[5],

            // application expire date
            'legacy_app_date'   => (!preg_match('/[^\s]/', $row[32]) ? false : $row[32]), // old_ExpDate
            'elf_app_date'   	=> (!preg_match('/[^\s]/', $row[33]) ? false : $row[33]), // New_Exp
            'correct_app_date'  => (!preg_match('/[^\s]/', $row[34]) ? false : $row[34]), // Correct_Exp

            // course expire date
            'legacy_course_date'	=> (!preg_match('/[^\s]/', $row[36]) ? false : $row[36]), // Refresh_old
            'elf_course_date'       => (!preg_match('/[^\s]/', $row[37]) ? false : $row[37]), // Refresh_new
            'correct_course_date'   => (!preg_match('/[^\s]/', $row[38]) ? false : $row[38]), // Refresher_Correct
        );

        return $return_data;
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
                // source file
                case file_exists($this->source_file):
                    throw new Exception (sprintf('Source file %s', $this->source_file));
                break;
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
        // add the souce path to the source file
        $this->source_file = sprintf('%s/source/deployment/ehsp/%s', ROOT, $this->source_file);

        // add the tmp dir path to report file
        $this->report_file = TMP.$this->report_filename;

        // create/reset the report file, truncates previous contents
        $rfh = fopen($this->report_file, "w");

        fwrite($rfh, '');

        // close the report file
        fclose($rfh);

        // add the tmp dir path to sql file
        $this->sql_file = TMP.$this->sql_filename;

        // create/reset the sql file, truncates previous contents
        $sfh = fopen($this->sql_file, "w");

        fwrite($sfh, '');

        // close the report file
        fclose($sfh);
    }

    /**
     * Write string to report contents
     */
    private function reportFile ($str = '')
    {
        $this->report_contents .= sprintf('%s%s', $str, $this->line_ending);
    }
}