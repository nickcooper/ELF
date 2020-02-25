<?php

/**
 * CreditHourImportShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class CreditHourImportShell extends AppShell
{
    /**
     * load database models
     */
    public $uses = array(
        'Accounts.Program',
        'Accounts.Account',
        'Licenses.LicenseType',
        'CourseCatalogsLicenseTypes', // dynamic model
        'ContinuingEducation.CourseRoster',
        'ContinuingEducation.CourseSection',
        'ContinuingEducation.CourseCatalog',
        'ContinuingEducation.TrainingProvider',
        'ContinuingEducation.Instructor',
    );
     
    /**
     * training instructor's name
     */ 
    private $instructor_account = null;
    private $instructor_account_id = null;
    
    /**
     * training provider's name
     */ 
    private $provider = 'Training Provider';
    private $provider_id = null;
    
    /**
     * data map
     * 
     * This isn't really used but here for reference.
     */ 
    private $data_map = array(
        0 => 'License.license_number',
        1 => 'License.legacy_number',
        2 => null, // old legacy number
        3 => 'License.issued_date',
        4 => 'Account.last_name',
        5 => 'Account.first_name',
        6 => 'CourseCatalog.label',
        7 => 'CourseSection.course_section_number',
        8 => 'CourseCatalog.code_hours',
        9 => 'CourseCatalog.non_code_hours',
        10 => 'CourseSection.end_date',
        11 => null, // actuall instructor name
    );
    
    /**
     * tables to truncate before each import
     */ 
    private $truncate_tables = array(
        'course_catalogs',
        'course_catalogs_license_types',
        'course_locations',                 // not using
        'course_rosters',
        'course_sections',
        'courses',                          // not using
        'instructor_assignments',           // not using
        'instructors',                      // not using
        'training_providers'                // not using
    );
    
    /**
     * available programs
     */
    private $programs = array();
    
    /**
     * available license types
     */
    private $license_types = array();
    
    /**
     * source file name
     * 
     * init() adds the source dir to this value
     */ 
    private $source_file = 'roster.csv';
    
    /**
     * source file directory
     */ 
    private $source_dir = 'rosters/';
    
    /**
     * source file increment count
     */ 
    private $increment = 0;
    
    
    
    
    //---------------------------------
    // MAIN PROCESS METHOD
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
            
            // test the configuration
            $this->heading('Validating Shell Configuration');
            $this->validateConfig();
            
            // truncate the tables
            $this->heading('Truncating Database Tables');
            $this->truncateTables();
            
            // create the training provider
            $this->heading('Create Training Provider');
            $this->createProvider();
            
            // create the instructor
            $this->heading('Create Training Instructor');
            $this->createInstructor();
            
            // import the data
            $this->heading('Importing Credit Hours');
            $this->importCreditHours();
            
            // email the success message
            $this->heading('Emailing Success Message');
            $this->emailSuccess();
            
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
     * Import the credit hours
     */
    private function importCreditHours () 
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
                // increment the loop
                $this->increment++;
                
                // skip the column headings
                if ($this->increment == 1)
                {
                    continue;
                }
                
                // process the file row
                if (count($row))
                {
                    // get the course catalog data
                    $course_catalog = $this->courseCatalogData($row);
                    
                    // get the course section data
                    $course_section = $this->courseSectionData($row, $course_catalog);
                    
                    // get the course roster
                    $course_roster = $this->courseRosterData($row, $course_section);
                }
            }
        }
        catch (Exception $e)
        {
            // close file
            fclose($fh);
            
            // throw error
            throw new Exception (sprintf('Failed to import data at line (%s) in file. %s', $this->increment, $e->getMessage()));
        }
        
        // close the file handler
        fclose($fh);
        
        $this->outSuccess(sprintf('Credit hour import complete. Total records imported was %s.', $this->increment));
        $this->out();
    }
    
    
    

    //---------------------------------
    // SUPPORTING MEHTODS
    //---------------------------------
    
    /**
     * Email success message 
     */
    private function emailSuccess () 
    {
        // format the mail recipients
        $recipients = implode(', ', Configure::read('ContinuingEducation.minimal.success_mail_list'));
        
        // format the message
        $msg = sprintf('Successfully imported %s course credit hours at %s.', $this->increment, date('H:i:s Y-m-d'));
        
        // send it
        if (! mail($recipients, 'ELF Credit Hour Import', $msg))
        {
            throw new Exception ('Could not send success email.');
        }
    }  
    
    /**
     * Create/fetch the course roster data 
     */
    private function courseRosterData ($row = null, $course_section = array()) 
    {
        try
        {
            // grab some values from the params
            $course_section_id = $course_section['CourseSection']['id'];
            $license_number = $row[0];
            
            // grab the license record account id
            $this->LicenseType->License->includeForeignData = false;
            
            if (!$license = $this->LicenseType->License->findByLicenseNumber($license_number))
            {
                throw new Exception(sprintf('Unable to find license for license number %s.', $license_number));
            }
            
            // extract the account id from the license record
            $account_id = $license['License']['foreign_key'];
            
            // look for a previous course roster record
            $course_roster = $this->CourseRoster->findByCourseSectionIdAndAccountId($course_section_id, $account_id);
            
            // create a new one if not found
            if (!$course_roster)
            {
                // many of these values are hardcoded for DPS, 
                // may need to change for future agencies if
                // anyone else is going to use minimal Cont. Ed.
                $course_roster = array(
                    'CourseRoster' => array(
                        'course_section_id' => $course_section_id,
                        'account_id' => $account_id,
                    )
                );
                
                // save the new course roster item
                $this->CourseRoster->create();
                
                if ($this->CourseRoster->save($course_roster))
                {
                    // assign the insert id back to the course section data array
                    $course_roster['CourseRoster']['id'] = $this->CourseRoster->getLastInsertId();
                    
                    // complete the course roster record
                    if (!$this->CourseRoster->complete($course_roster['CourseRoster']['id']))
                    {
                        throw new Exception ('Failed to complete course roster record.');
                    }
                }
                else
                {
                    throw new Exception('Failed to create new course roster record.');
                }
            }
            
            return $course_roster;
        }
        catch (Exception $e)
        {
            throw new Exception (sprintf('Failed to get course roster data. %s', $e->getMessage()));
        }
    }
    
    /**
     * Create/fetch the course section data 
     */
    private function courseSectionData ($row = null, $course_catalog = array()) 
    {
        try
        {
            // grab some values from the row data
            $label = $course_catalog['CourseCatalog']['label'];
            $course_catalog_id = $course_catalog['CourseCatalog']['id'];
            $course_section_number = $row[7];
            $start_date = $end_date = GenLib::dateFormat($row[10], 'Y-m-d');
            
            // look for a previous course section record
            $course_section = $this->CourseSection->findByLabel($label);
            
            // create a new one if not found
            if (!$course_section)
            {
                // many of these values are hardcoded for DPS, 
                // may need to change for future agencies if
                // anyone else is going to use minimal Cont. Ed.
                $course_section = array(
                    'CourseSection' => array(
                        'course_catalog_id' => $course_catalog_id,
                        'address_id' => 0,
                        'training_provider_id' => $this->provider_id,
                        'account_id' => $this->instructor_account_id,
                        'label' => $label,
                        'course_section_number' => $course_section_number,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'enabled' => 1
                    )
                );
                
                // turn off the start date prior course approval validation
                $this->CourseSection->validator()->remove('start_date', 'priorCourseApproval');
                
                // save the new course section item
                $this->CourseSection->create();
                
                if ($this->CourseSection->save($course_section) && $insert_id = $this->CourseSection->getLastInsertId())
                {
                    // assign the insert id back to the course section data array
                    $course_section['CourseSection']['id'] = $insert_id;
                }
                else
                {
                    throw new Exception(sprintf('Failed to create new course section record. %s', $course_section_number));
                }
            }
            
            return $course_section;
        }
        catch (Exception $e)
        {
            throw new Exception (sprintf('Failed to get course section data. %s', $e->getMessage()));
        }
    }
    
    /**
     * Create/fetch the course catalog data 
     */
    private function courseCatalogData ($row = null) 
    {
        try
        {
            // grab some values from the row data
            $label = $row[6];
            $code_hours = $row[8];
            $non_code_hours = $row[9];
            
            // look for a previous course catalog record
            $course_catalog = $this->CourseCatalog->findByLabel($label);
            
            // create a new one if not found
            if (!$course_catalog)
            {
                // many of these values are hardcoded for DPS, 
                // may need to change for future agencies if
                // anyone else is going to use minimal Cont. Ed.
                $course_catalog = array(
                    'CourseCatalog' => array(
                        'program_id' => $this->programs[0]['Program']['id'], // limited to one program for now
                        'label' => $label,
                        'abbr' => $this->increment,
                        'descr' => '',
                        'code_hours' => $code_hours,
                        'non_code_hours' => $non_code_hours,
                        'test_attempts' => 0,
                        'enabled' => 1,
                        'cycle' => 0, 
                    )
                );
                
                // save the new course catalog item
                $this->CourseCatalog->create();
                
                if ($this->CourseCatalog->save($course_catalog) && $insert_id = $this->CourseCatalog->getLastInsertId())
                {
                    // assign the insert id back to the course catalog data array
                    $course_catalog['CourseCatalog']['id'] = $insert_id;
                    
                    $course_to_types = array();
                    
                    // add the new course to EVERY license type - what other choice do we have?!
                    foreach ($this->license_types as $type)
                    {
                        $course_to_types[] = array(
                            'course_catalog_id' => $insert_id,
                            'license_type_id' => $type['LicenseType']['id'],
                            'initial' => 1,
                            'renewal' => 1
                        );
                    }
                    
                    // save the pivot data
                    $this->CourseCatalogsLicenseTypes->create();
                    
                    if (!$this->CourseCatalogsLicenseTypes->saveAll($course_to_types))
                    {
                        throw new Exception ('Could not associate course to license types.');
                    }
                }
                else
                {
                    throw new Exception(sprintf('Failed to create new course catalog record. %s', $label));
                }
            }
            
            return $course_catalog;
        }
        catch (Exception $e)
        {
            throw new Exception (sprintf('Failed to get course catalog data. %s', $e->getMessage()));
        }
    }
    
    /**
     * Create the training instructor 
     */
    private function createInstructor () 
    {
        // get the elf account by label
        if (!$account = $this->Account->findByLabel($this->instructor_account))
        {
            throw new Exception(sprintf('Failed to find local account for %s.', $this->instructor_account));
        }
        
        // set the instructor id
        $this->instructor_account_id = $account['Account']['id'];
        
        $this->outSuccess(sprintf('Matched instructor name to account for %s.', $this->instructor_account));
        $this->out();
    }
    
    /**
     * Create the training provider 
     */
    private function createProvider () 
    {
        try
        {
            // look for a previous provider record
            $provider = $this->TrainingProvider->findByLabel($this->provider);
            
            // create a new one if not found
            if (!$provider)
            {
                // format the data for new provider save
                $provider = array(
                    'TrainingProvider' => array(
                        'label' => $this->provider,
                        'abbr' => sprintf('TP%s', $this->increment)
                    )
                );
                
                // save the new provider
                $this->TrainingProvider->create();
                
                if (!$this->TrainingProvider->save($provider))
                {
                    throw new Exception ('Failed to save record.');
                }
                
                $this->provider_id = $this->TrainingProvider->getLastInsertId();
            }
        
            $this->outSuccess(sprintf('Created training provider record for %s.', $this->provider));
            $this->out();
        }
        catch (Exception $e)
        {
            throw new Exception (sprintf('Failed to get training provider data. %s', $e->getMessage()));
        }
    }
    
    /**
     * Truncate continuing ed. tables for import
     */
    private function truncateTables () 
    {
        // loop the tables and truncate
        try
        {
            foreach ($this->truncate_tables as $table)
            {
                $this->CourseRoster->query(sprintf('TRUNCATE TABLE %s; ALTER TABLE %s AUTO_INCREMENT = 1;', $table, $table));
                
                $this->outSuccess(sprintf('Truncated %s table.', $table));
            }
            
            $this->out('');
        }
        catch (Exception $e)
        {
            throw new Exception (sprintf('Failed to truncate tables. %s', $e->getMessage()));
        }
    }
    
    /**
     * Initialize the object
     * 
     * Setup some object vars, etc.
     */
    private function init () 
    {
        // define the source directory
        $this->source_dir = sprintf('%s%s', TMP, $this->source_dir);
        
        // add the souce path to the source file
        $this->source_file = $this->source_dir.$this->source_file;
         
        // define the instructor 
        $this->instructor_account = Configure::read('ContinuingEducation.minimal.instructor');
        
        // define the programs
        $this->programs = $this->Program->find('all');
        
        // define the programs
        $this->license_types = $this->LicenseType->find('all');
    }
    
    /**
     * Validate the configuration
     */
    private function validateConfig () 
    {
        try 
        {
            // attempt to validate the configuration values
            switch (false)
            {
                // source dir
                case file_exists($this->source_dir):
                    throw new Exception ('Source directory');
                break;
                
                // source file
                case file_exists($this->source_file):
                    throw new Exception (sprintf('Source file %s', $this->source_file));
                break;
                
                // instructor's name
                case preg_match('/[0-9a-z]/i', Configure::read('ContinuingEducation.minimal.instructor')):
                    throw new Exception ('Course instructor');
                break;
                    
                // provider's name
                case preg_match('/[0-9a-z]/i', $this->provider):
                    throw new Exception ('Course provider');
                break;
                    
                // program list
                case count($this->programs):
                    throw new Exception ('Program data');
                break;
                    
                // license type list
                case count($this->license_types):
                    throw new Exception ('License Type data');
                break;
            }
                
            $this->outSuccess('Shell configuration validates.');
            $this->out('');
        }
        catch (Exception $e)
        {
            throw new Exception (sprintf('Failed to validate configuration. %s is missing or invalid.', $e->getMessage()));
        }
    }
}