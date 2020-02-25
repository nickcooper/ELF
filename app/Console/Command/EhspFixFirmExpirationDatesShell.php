<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 *
 * EhspFixFirmExpirationDatesShell
 * ================================
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 *
 */
class EhspFixFirmExpirationDatesShell extends AppShell
{
	/**
	 *
     * Load the necessary database models
     * 
     */
    public $uses = array(
        'Firms.Firm',
        'Licenses.License',
        'Licenses.LicenseType',
    );

    /**
     *
     * Specify the source file name
     * 
     * init() adds the source dir to this value
     *
     */ 
    private $source_file = 'Firms-edited.csv';
    
    /**
     *
     * Specify the source file directory
     *
     */ 
    private $source_dir = 'ehsp/';

    /**
     * Location of error output directory.
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
    private $total_counter = 0;  			// overall record counter
    private $yes_count = 0;					// count of records with a yes value
    private $no_count = 0;   				// count of records with a no value
    private $other_count = 0;     			// count of records with something other than yes/no
    private $records_processed = 0;			// count of records processed
    private $good_records = 0;				// count of good records processed
    private $bad_total_records = 0;			// count of bad records processed
    private $bad_license_records = 0;       // count of bad license records processed
    private $bad_application_records = 0;   // count of bad application records processed

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
            $this->heading('Initialize Firm Edit Processes');
            $this->init();

            // import the Firm data
            $this->heading('Import Firm Edit Data');
            $imported_data = $this->importFirmExpirationData();

            // process the Firm data
            $this->heading('Process Firm Edit Data');
           	$this->processFirmExpirationData($imported_data);

			// report the results
            $this->heading('Report Firm Edit Results');			
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
        // define the source directory
        $this->source_dir = ROOT.DS.'source'.DS.'deployment'.DS.'ehsp'.DS;
        
        // add the souce path to the source file
        $this->source_file = $this->source_dir.$this->source_file;

        // check that passed in file exists
        if (!file_exists($this->source_file))
        {
        	throw new Exception('Firm file could not be found for processing.  File not processed.');
        }

        // run the parent init
        parent::initialize();

        // set the output directory and filename
        $this->outputDir = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS;
        $this->outputFilename = 'EhspFixFirmExpirationDatesOutput.txt';

        // set permissions on the output dir
        $dir = new Folder();
        $dir->chmod($this->outputDir, 0775, true);

        // if the output file already exists, save it off and delete it so a new one will be made for this run
        $file = new File($this->outputDir.$this->outputFilename, false, 0664);
        if ($file->exists())
        {
            $file->copy($this->outputDir.$this->outputFilename.'.old_'. date('m-d-Y_H:i:s'));
            $file->delete();
        }

        $this->logMessage("Today's date: " . date('m-d-Y H:i:s'));

        $this->out('Firm Edit processes initialized...');
    } // end init()

    /**
     *
     * importFirmExpirationData()
     * ---------------------------
     *     
     * Import the Firm data .csv file
     *
     * @access private
     * @return data array of the imported rows to be processed
     *
     */
    private function importFirmExpirationData()
    {
		$this->out('Importing Firm Edit data...');

        // Open file
        if (($fh = fopen($this->source_file, "r")) !== false)
        {
            // set an array to hold the row data
            $rows_to_process = array();

            // read in the CSV
            while (($row = fgetcsv($fh, 1000, ",")) !== false) // && $this->total_counter < 10)
            {

                $this->total_counter++;

                switch ($row[31]) 
                {
                	case 'no':
                		$rows_to_process[] = $row;  // add row to rows to be processed
                		$this->no_count++;
                		break;
                	case 'yes':
                		$this->yes_count++;
                		break;
                	default:
                		$this->other_count++;
                		//debug(sprintf('this row (%s) was counted as other', $this->total_counter));
                		//debug($row);
                		break;
                }
            }

			$this->out('Firm Edit data imported...');

            // Close File
            if (!fclose($fh))
            {
                throw new Exception('Firm file could not be closed.');
            }
        }
        else
        {
            throw new Exception('Could not open firm file for processing.  File not processed.');
        }
        
        return $rows_to_process;
    } // end importFirmExpirationData

    /**
     *
     * processFirmExpirationData($imported_data)
     * ----------------------------
     * 
     * Report out counter results. 
	 *
     * @param array $imported_data  data that was imported from the Firm .csv 
     * @access private
     * @return void
     *
     */
    private function processFirmExpirationData(array $imported_data = null, $path = '') 
    {
    	if (!$imported_data)
    	{
    		throw New Exception('No Firm data to process.');
    	}

		$this->out('Processing Firm Edit data...');

		// remove column header row and update counts accordingly
		$this->other_count--;
		$this->total_counter--;

		$local_count_good = 0;
		$local_count_bad_total = 0;
        $local_count_bad_licenses = 0;
        $local_count_bad_applications = 0;
		$found_license = array(); 
        $valid_license_numbers = array(); 
		$invalid_license_numbers = array();

        $this->License = ClassRegistry::init('Licenses.License');

		foreach ($imported_data as $key => $value)
        {
	            // find the license numbers in the DB that match the license numbers in the file
            $found_license = $this->License->find(
                'first',
                array(
                    'conditions' => array(
                        'license_number' => $value[3],
                        'expire_date' => date('Y-m-d', strtotime($value[11]))
                    ),
                    'contain' => array(
                        'Application' => array(
                            'conditions' => array(
                                'expire_date' => date('Y-m-d', strtotime($value[11]))
                            )
                        )
                    ),
                )
            );

			if ($found_license)  
			{
                // set the data array of updated values		
                $data = array();		
                $data['License']['id'] = $found_license['License']['id'];
                $data['License']['expire_date'] = date('Y-m-d', strtotime($value[32]));

                $this->License->saveAll($data);

                if (GenLib::isData($found_license, 'Application.0', array('id')))
                {
                    $data = array();        
                    $data['Application']['id'] = $found_license['Application'][0]['id'];
                    $data['Application']['expire_date'] = date('Y-m-d', strtotime($value[32]));

                    $this->License->Application->save($data);

                    //$this->logMessage('Corresponding license found for: ' . $value[3]);   
                    $local_count_good++;
                    $valid_license_numbers[] = $value[3];                    
                }
                else
                {
                    $this->logMessage('Application not found for License: ' . $value[3] . ' with an expire date of: ' . $value[11] . '. Application expire date not updated to ' . $value[32] . '.');
                    $invalid_license_numbers[] = $value[3];
                    $local_count_bad_applications++;
                }
			}
			else
			{
				$this->logMessage('License not found for: ' . $value[3] . ' with an expire date of ' . $value[11] . '. License and Application expire dates not updated to ' . $value[32] . '.'); 	
				$invalid_license_numbers[] = $value[3];
				$local_count_bad_licenses++;
			}
		}
	        
        // update totals
		$this->records_processed = $local_count_good + $local_count_bad_licenses + $local_count_bad_applications;
	    $this->good_records = $local_count_good;	
	    $this->bad_license_records = $local_count_bad_licenses;
        $this->bad_application_records = $local_count_bad_applications;        
        $this->bad_total_records = $this->bad_license_records + $this->bad_application_records;

        //$this->logMessage('Successfully updated licenses listed below...');
        //$this->logMessage(implode(', ', $valid_license_numbers));
        $this->logMessage('Failed license updates listed below...');
        $this->logMessage(implode(', ', $invalid_license_numbers));

        $this->out('Firm Edit data processed...');
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
            $this->out('Firm Expiration Date Update Report');
            $this->out('-----------------------------------');
            $this->out('');
			$this->out('Total count: ' . $this->total_counter);
			$this->out("'Yes' count: " . $this->yes_count);
			$this->out("'No' count: " . $this->no_count);
			$this->out('Other count: ' . $this->other_count);
			$this->out("Records processed (from count of 'No' records): " . $this->records_processed);
			$this->out("Records successfully updated: " . $this->good_records);
			$this->out("Records failed: " . $this->bad_total_records);
            $this->out("Records licenses failed: " . $this->bad_license_records);
            $this->out("Records applications failed: " . $this->bad_application_records);
            $this->out('');
            $this->out('Log File Path: ');
            $this->out($this->outputDir.$this->outputFilename);

            // log the report results
            $this->logMessage('Firm Expiration Date Update Report');
            $this->logMessage('-----------------------------------');
            $this->logMessage('');
            $this->logMessage('Total count: ' . $this->total_counter);
            $this->logMessage("'Yes' count: " . $this->yes_count);
            $this->logMessage("'No' count: " . $this->no_count);
            $this->logMessage('Other count: ' . $this->other_count);
            $this->logMessage("Records processed (from count of 'No' records): " . $this->records_processed);
            $this->logMessage("Records successfully updated: " . $this->good_records);
            $this->logMessage("Records failed: " . $this->bad_total_records);
            $this->logMessage("Records licenses failed: " . $this->bad_license_records);
            $this->logMessage("Records applications failed: " . $this->bad_application_records);
            $this->logMessage('');
		}
		catch (Exception $e)
		{
			throw New Exception('Error reporting results of Firm Expiration Date updates.');
		}
    } // end reportResults()
}
