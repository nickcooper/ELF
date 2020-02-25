<?php

/**
 * DeployShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class DeployShell extends AppShell
{
    /**
     * Tasks that our shell utilizes.
     *
     * @var array
     * @access public
     */
    public $tasks = array(
        'ImportVanillaGroup',
        'ImportAbatementStatus',
        'ImportAccount',
        'ImportApplicationType',
        'ImportApplicationSection',
        'ImportApplicationSectionsToLicenseTypesAssociation',
        'ImportAppLicCreditHour',
        'ImportConfiguration',
        'ImportCounty',
        'ImportCourseCatalog',
        'ImportCourseCatalogsToLicenseTypesAssociation',
        'ImportDegreesOfEducation',
        'ImportDwellingType',
        'ImportFee',
        'ImportFeeModifier',
        'ImportFirmType',
        'ImportLicenseStatusLevel',
        'ImportLicenseStatus',
        'ImportLicenseType',
        'ImportLicenseTypesToFirmsAssociation',
        'ImportLicenseTypesToVariantsAssociation',
        'ImportManager',
        'ImportPaymentType',
        'ImportPracticalWorkExperienceTypesToProgramsAssociation',
        'ImportPracticalWorkPercentageType',
        'ImportGroup',
        'ImportGroupPaymentType',
        'ImportProgram',
        'ImportProgramGroup',
        'ImportProgramCertificateAssociation',
        'ImportProgramsToRegisteredPluginsAssociation',
        'ImportQuestionsToLicenseTypesAssociation',
        'ImportRegisteredPlugin',
        'ImportReplacedCourseCatalogAssociation',
        'ImportScreeningQuestionsToLicenseTypesAssociation',
        'ImportState',
        'ImportVariant',
        'ImportWorkExperienceType',
    );

    public $task = null;

    /**
     * PHP script memory limit
     *
     * @var array
     * @access private
     */
    private $_scriptMemLimit = '1024M';

    /**
     * PHP script memory limit
     *
     * @var string  Name of the agency.
     * @access private
     */
    private $agency = null;

    public $import_type = null;

    public $section = null;

    public $counts = array();

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
            ini_set('auto_detect_line_endings',true);

            $start_time = time();

            $this->out('Deploy shell started...');

            // set the script memory limit
            ini_set('memory_limit', $this->_scriptMemLimit);
            $this->out(str_pad(__('PHP allowed memory limit: '), 50).ini_get('memory_limit'));

            // Run Vanilla Task
            $this->importVanilla();

            // set the permissions
            $this->dispatchShell('acl', 'create', 'aco', '', 'controllers'); // create primary parent node 'controllers'
            $this->dispatchShell('acl', 'grant', 'Group.2', 'controllers'); // give Super Admin all access permission

            $this->out();

            $this->hr();

            // Set agency if passed
            $this->agency = isset($this->params['agency']) ? $this->params['agency'] : null;

            // Check for agency param and run agency task if defined.
            if ($this->agency)
            {
                $this->out(sprintf('Agency defined: <cyan>%s</cyan>', $this->agency));

                // Run Agency Task
                $this->importAgency();
            }
            else
            {
                $this->out('Agency not defined. Skipping...');
            }

            // Import Fees
            $this->importFees();

            $end_time = time();

            list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $end_time);
            $this->hr();
            $this->out(sprintf('Deploy shell finished! Execution time: (%sh, %sm, %ss)', $hours, $minutes, $seconds), 2);
        }
        catch (Exception $e)
        {
            // fail - If exception, return 1 (fail code) so capistrano does rollback deploy.
            $this->logMessage($e->getMessage(), 2);
            exit(1);
        }
    }

    public function importVanilla()
    {
        $start_time = time();

        $this->hr();
        $this->out('Vanilla import started...');
        $this->out('Start time: '. date('D Y/m/d  h:i:s'));

        $this->import_type = 'Vanilla';

        $file = ROOT.DS.'source'.DS.'deployment'.DS.'vanilla'.DS.'deploy.csv';
        $file_exists = file_exists($file);
        if ($file_exists)
        {
            $this->outSuccess(sprintf('Looking for Vanilla CSV file: %s', $file));

            // Open file
            if (($fh = fopen($file, "r")) !== false)
            {
                while (($row = fgetcsv($fh, 1000, ",")) !== false)
                {
                    // Check to see if row is blank, if so continue.
                    if ($this->isRowBlank($row))
                    {
                        continue;
                    }

                    // Check to see if row is a section name, if so set section.
                    if ($this->isRowNewSection($row))
                    {
                        continue;
                    }
                    // Check to see if row is column names, if so continue.
                    if ($this->isRowColmunHeader($row))
                    {
                        continue;
                    }

                    $this->processRecord($row);

                }
                // Close File
                fclose($fh);
            }
            else
            {
                $this->quit('Failed to open '.$file, 2);
            }

            $this->outSectionCount();
            $this->section = null;
        }
        else
        {
            $this->outFailure(sprintf('Looking for Vanilla CSV file: %s', $file));
            //$this->quit(__('Missing Files'), 2);
        }

        $end_time = time();
        $this->out('End time: '. date('D Y/m/d  h:i:s'));

        list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $end_time);
        $this->out(sprintf('Vanilla task finished! Execution time: (%sh, %sm, %ss)', $hours, $minutes, $seconds), 2);
    }

    public function importAgency()
    {
        $start_time = time();
        $this->hr();

        $this->out('Agency import started...');
        $this->out('Start time: '. date('D Y/m/d  h:i:s'));
        $this->import_type = 'Agency';

        if ($this->agency == null)
        {
            $this->quit('Agency is undefined', 2);
        }

        $file = ROOT.DS.'source'.DS.'deployment'.DS.$this->agency.DS.'deploy.csv';
        $file_exists = file_exists($file);
        if ($file_exists)
        {
            $this->outSuccess(sprintf('Looking for %s CSV file: %s', $this->agency, $file));

            // Open file
            if (($fh = fopen($file, "r")) !== false)
            {
                while (($row = fgetcsv($fh, 1000, ",")) !== false)
                {
                    // Check to see if row is blank, if so continue.
                    if ($this->isRowBlank($row))
                    {
                        continue;
                    }

                    // Check to see if row is a section name, if so set section.
                    if ($this->isRowNewSection($row))
                    {
                        continue;
                    }
                    // Check to see if row is column names, if so continue.
                    if ($this->isRowColmunHeader($row))
                    {
                        continue;
                    }

                    $this->processRecord($row);

                }
                // Close File
                fclose($fh);
            }
            else
            {
                $this->quit('Failed to open '.$file, 2);
            }

            $this->outSectionCount();
            $this->section = null;
        }
        else
        {
            $this->outFailure(sprintf('Looking for %s CSV file: %s', $this->agency, $file));
            //$this->quit(__('Missing Files'), 2);
        }

        $end_time = time();

        list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $end_time);
        $this->out('End time: '. date('D Y/m/d  h:i:s'));
        $this->out(sprintf('Agency task finished! Execution time: (%sh, %sm, %ss)', $hours, $minutes, $seconds), 2);
    }

    public function importFees()
    {
        $start_time = time();
        $this->hr();

        $this->out('Fees import started...');
        $this->out('Start time: '. date('D Y/m/d  h:i:s'));
        $this->import_type = 'Fee';

        if ($this->agency == null)
        {
            $this->quit('Agency is undefined', 2);
        }

        $file = ROOT.DS.'source'.DS.'deployment'.DS.$this->agency.DS.'fees.csv';
        $file_exists = file_exists($file);
        if ($file_exists)
        {
            $this->outSuccess(sprintf('Looking for %s CSV file: %s', $this->agency, $file));

            // Open file
            if (($fh = fopen($file, "r")) !== false)
            {
                while (($row = fgetcsv($fh, 1000, ",")) !== false)
                {
                    // Check to see if row is blank, if so continue.
                    if ($this->isRowBlank($row))
                    {
                        continue;
                    }

                    // Check to see if row is a section name, if so set section.
                    if ($this->isRowNewSection($row))
                    {
                        continue;
                    }
                    // Check to see if row is column names, if so continue.
                    if ($this->isRowColmunHeader($row))
                    {
                        continue;
                    }

                    $this->processRecord($row);

                }
                // Close File
                fclose($fh);
            }
            else
            {
                $this->quit('Failed to open '.$file, 2);
            }

            $this->outSectionCount();
            $this->section = null;
        }
        else
        {
            $this->outFailure(sprintf('Looking for %s CSV file: %s', $this->agency, $file));
            //$this->quit(__('Missing Files'), 2);
        }

        $end_time = time();

        list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $end_time);
        $this->out('End time: '. date('D Y/m/d  h:i:s'));
        $this->out(sprintf('Fee task finished! Execution time: (%sh, %sm, %ss)', $hours, $minutes, $seconds), 2);
    }

    public function processRecord($row)
    {
        switch ($this->section)
        {
            default:
                try
                {
                    if (in_array($this->task, $this->tasks))
                    {
                        $this->{$this->task}->importRow($row);
                        // Increment count
                        if (!isset($this->counts[$this->import_type][$this->section]))
                        {
                            $this->counts[$this->import_type][$this->section] = 0;
                        }
                        $this->counts[$this->import_type][$this->section]++;
                    }
                }
                catch (Exception $e)
                {
                    $this->out($e->getMessage());
                }
                break;
        }
    }

    public function isRowBlank($row)
    {
        foreach ($row as $value)
        {
            $value = trim($value);
            if (!empty($value))
            {
                return false;
            }
        }

        return true;
    }

    public function isRowNewSection($row)
    {
        if (preg_match('/\[(.*?)\]/', $row[0], $matches))
        {
            $this->outSectionCount();
            $this->section = $matches[1];
            $this->task = "Import".Inflector::classify(str_replace(' ', '_', strtolower($this->section)));

            $this->out(sprintf('  %s import...', $this->section));

            if (!in_array($this->task, $this->tasks))
            {
                $this->out($this->task.' task not found.');
            }

            return true;
        }
        return false;
    }

    public function isRowColmunHeader($row)
    {
        if (preg_match('/!.*?/', $row[0]))
        {
            foreach ($row as $value)
            {
                if (!preg_match('/!.*?/', $value) && $value != '')
                {
                    $this->quit(sprintf('Column name [%s] missing `!`', $value), 2);
                }
            }
            return true;
        }
        return false;
    }

    public function outSectionCount()
    {
        if (!isset($this->counts[$this->import_type][$this->section]))
        {
            $this->counts[$this->import_type][$this->section] = 0;
        }
        if ($this->section)
        {
            if ($this->counts[$this->import_type][$this->section] > 0)
            {
                $this->outSuccess(str_pad(sprintf('%s imported', $this->section), 50).$this->counts[$this->import_type][$this->section].' records');
            }
            else
            {
                $this->outFailure(str_pad(sprintf('%s not imported', $this->section), 50).$this->counts[$this->import_type][$this->section].' records');
            }

        }
        return true;
    }

    /**
     * Define option parser.
     *
     * @return void
     * @access public
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        // Add agency option
        $parser->addOption(
            'agency',
            array(
                'help'    => __('Specify which agency to deploy.'),
                'default' => null,
            )
        );

        //configure parser
        return $parser;
    }
}