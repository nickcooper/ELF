<?php

/**
 * EhspDeleteBadExpirationsShell
 * 
 * This shell will find course expiration records
 * that should have been replaced (deleted) by a
 * replacement course's expiration record and delete
 * the bad expiration record.
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class EhspDeleteBadExpirationsShell extends AppShell
{
    /**
     * load database models
     */
    public $uses = array(
        'Licenses.Expiration'
    );
    
    /**
     * report file
     */ 
    private $report_file = array(
        'file' => 'ehsp_delete_bad_expirations_report.csv',
        'columns' => array(
            'License Number'        => 'License.license_number',
            'Bad Course'            => 'CourseCatalog.abbr',
            'Result'                => '',
            'Error'                 => ''
        )
    );
    
    /**
     * file line ending
     */ 
    private $line_ending = PHP_EOL;
    
    /**
     * result counts
     */ 
    private $count = array(
        'increment' => 0,
        'pass'      => 0, 
        'fail'      => 0
    );
    
    /**
     * produce the report file only,
     * do not delete any records.
     */
    private $report_only = false;
    
    
    
    
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
            $this->Expiration->query('SET foreign_key_checks = 0;');
            
             // initialize the object
            $this->init();
            
            // test the setup
            $this->heading('Validating Shell Setup');
            $this->validate();
            
            // delete the records
            $this->heading('Delete Bad Expirations');
            $this->deleteExpirations();
            
            // turn on foreign key constraints
            $this->Expiration->query('SET foreign_key_checks = 1;');
        }
        catch (Exception $e)
        {
            // turn on foreign key constraints
            $this->Expiration->query('SET foreign_key_checks = 1;');
            
            // fail and exit
            $this->outFailure(sprintf('%s', $e->getMessage()));
            $this->out('');
            
            // fail
            exit(1);
        }
    }
    
    /**
     * Delete expiration records.
     */
    private function deleteExpirations () 
    {
        try 
        {
            // get a list of expiration records to delete
            $bad_expirations = $this->Expiration->query('
                SELECT *
                FROM expirations AS Expiration
                JOIN licenses AS License on License.id = Expiration.parent_key
                JOIN license_types AS LicenseType ON LicenseType.id = License.license_type_id
                JOIN course_catalogs_license_types AS CourseCatalogLicenseType ON CourseCatalogLicenseType.course_catalog_id = Expiration.foreign_key
                JOIN course_catalogs AS CourseCatalog ON CourseCatalog.id = CourseCatalogLicenseType.course_catalog_id
                WHERE Expiration.parent_obj = "License"
                    AND Expiration.foreign_obj = "CourseCatalog"
                    AND (
                        (LicenseType.id =2 AND CourseCatalog.id IN (7,8,9,10,11))
                        OR (LicenseType.id = 6 AND CourseCatalog.id IN (8, 7))
                    )
                GROUP BY Expiration.id;
            ');
            
            // loop and delete the records
            foreach ($bad_expirations as $key => $exp)
            {
                // increment the total
                $this->count['increment']++;
                
                if (!$this->Expiration->delete($exp['Expiration']['id'], false))
                {
                    // increment the failed
                    $this->count['fail']++;
                    
                    // report out
                    $this->reportFile(
                        sprintf(
                            '"%s", "%s", "FAIL", "Failed to delete %s course expiration for license %s."',
                            $exp['License']['license_number'],
                            $exp['CourseCatalog']['abbr'],
                            $exp['CourseCatalog']['abbr'],
                            $exp['License']['license_number']
                        )
                    );
                }

                // increment the passed
                $this->count['pass']++;
            }
            
        }
        catch (Exception $e)
        {
            // throw error
            throw new Exception (sprintf('Failed to delete bad expiration record. (%s)', $e->getMessage()));
        }
        
        // report the counts
        $this->reportOut();
    }
    
    
    

    //---------------------------------
    // SUPPORTING MEHTODS
    //---------------------------------
    
    /**
     * Initialize the object
     * 
     * Setup some object vars, etc.
     */
    private function init () 
    {
        // add the app/tmp/ path to the report file
        $this->report_file['file'] = TMP.$this->report_file['file'];
        
        // create/reset the report file, truncates previous contents
        $rfh = fopen($this->report_file['file'], "w");
        fwrite($rfh, sprintf(
            '"%s"%s',
            implode('","', array_keys($this->report_file['columns'])),
            $this->line_ending)
        );
        
        fclose($rfh);
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
                case file_exists($this->report_file['file']):
                    throw new Exception (sprintf('Report file %s', $this->report_file['file']));
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
     * Write string to report file
     */
    private function reportFile ($str = '')
    {
        // open the report file handler
        $rfh = fopen($this->report_file['file'], 'a');
        
        // write to file
        fwrite($rfh, sprintf('%s%s', $str, $this->line_ending));
        
        // close file
        fclose($rfh);
    }
    
    /**
     * reportOut
     */
    private function reportOut()
    {
        // display counts to command line
        $this->outSuccess(sprintf('Total Records Processed: %s', $this->count['increment']));
        $this->outSuccess(sprintf('Bad Expirations Deleted: %s', $this->count['pass']));
        $this->outFailure(sprintf('Bad Expirations Failed: %s', $this->count['fail']));
        
        // report counts in report file
        $this->reportFile($this->line_ending);          // empty line
        $this->reportFile('-----------------------');   // divider line
        $this->reportFile($this->line_ending);          // empty line
        
        $this->reportFile(sprintf('Results processed on %s', date('Y-m-d')));
        $this->reportFile(sprintf('"%s", "Total Records Processed"', $this->count['increment']));
        $this->reportFile(sprintf('"%s", "Bad Expirations Deleted"', $this->count['pass']));
        $this->reportFile(sprintf('"%s", "Bad Expirations Failed"', $this->count['fail']));
        
        $this->out();
    }
}