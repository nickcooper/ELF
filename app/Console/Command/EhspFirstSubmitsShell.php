<?php

/**
 * EhspFirstSubmitsShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class EhspFirstSubmitsShell extends AppShell
{
    /**
     * load database models
     */
    public $uses = array(
        'Licenses.Application',
    );

    /**
     * file line ending
     */
    private $line_ending = PHP_EOL;

    /**
     * sql file
     */
    private $sql_dir_path    = TMP;
    private $sql_filename    = 'ehsp_first_submits.sql';
    private $sql_contents    = '';

    /**
     * source file success count
     */
    private $counts = array(
    	'increment' 	=> 0,
        'applications' 	=> array('pass' => 0, 'fail' => 0, 'skip' => 0),
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
            // run process
            $this->heading('Generating SQL Statements');
            $this->addFirstSubmission();

            // open the sql file handler
            $sfh = fopen($this->sql_dir_path.$this->sql_filename, 'a');

            // write to file
            fwrite($sfh, $this->sql_contents);

            // close file
            fclose($sfh);

            // cp sql to user home
            exec(sprintf('cp %s ~/%s', $this->sql_dir_path.$this->sql_filename, $this->sql_filename));

            // run the sql updates
            $this->heading('Running SQL Statements');
            $this->Application->query($this->sql_contents);
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
     * Process addFirstSubmission
     */
    private function addFirstSubmission ()
    {
        try
        {
            // get max id of all license records
            $max_id = $this->Application->query(
                'SELECT max(id) AS max_id FROM applications;'
            );
            $max_id = $max_id[0][0]['max_id'];

            // loop the records
            for ($i=1; $i <= $max_id; $i++)
            {
                try
                {
                    // attempt to find a record
                    $application = $this->Application->find(
                        'first',
                        array(
                            'contain' => array('ApplicationSubmission'),
                            'conditions' => array('Application.id' => $i)
                        )
                    );

                    // if no license found skip it
                    if (!$application || count($application['ApplicationSubmission']) > 0)
                    {
                        $this->counts['applications']['skip']++;
                        continue;
                    }

                    debug($application['Application']['id']);

                    // increment the loop
                    $this->counts['increment']++;

                    // default data array
                    $data = array();

                    // create a new submit record and update application with submit id
                    $data = array(
                        'Application' => array(
                            'id' => $application['Application']['id'],
                        ),
                        'CurrentSubmission' => array(
                            'application_id' => $application['Application']['id'],
                            'submit_paid_date' => $application['Application']['submit_paid_date'],
                            'materials_received' => $application['Application']['materials_received'],
                            'approved_date' => $application['Application']['approved_date'],
                            'application_data' => null,
                        )
                    );

                    // create the submission record
                    $this->sql_contents .= sprintf(
                        'INSERT INTO application_submissions (application_id, submit_paid_date, materials_received, approved_date) VALUES ("%s", "%s", "%s", "%s"); %s',
                        $application['Application']['id'],
                        $application['Application']['submit_paid_date'],
                        $application['Application']['materials_received'],
                        $application['Application']['approved_date'],
                        $this->line_ending
                    );

                    // capture the last insert id
                    $this->sql_contents .= 'SET @submit_id = LAST_INSERT_ID(); '.$this->line_ending;

                    // update the appliaction record
                    $this->sql_contents .= sprintf(
                        'UPDATE applications SET application_submission_id = @submit_id WHERE applications.id = %s LIMIT 1; %s',
                        $application['Application']['id'],
                        $this->line_ending
                    );

                    $this->counts['applications']['pass']++;
                }
                catch (Exception $e)
                {
                    debug($e->getMessage());
                    exit();
                }
            }
        }
        catch (Exception $e)
        {
            $this->outFailure('`-_ MAJOR SCRIPT MALFUNCTION _-`');
            $this->outFailure($e->getMessage());

            exit();
        }
    }
}