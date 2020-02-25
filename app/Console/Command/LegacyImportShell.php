<?php

/**
 * LegacyImportShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class LegacyImportShell extends AppShell
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'AppModel',
        'Accounts.Account',
        'Accounts.WorkExperience',
        'Accounts.PracticalWorkPercentage',
        'Accounts.PracticalWorkExperience',
        'Licenses.License',
        'Licenses.InsuranceInformation',
        'Firms.Firm'
    );

    /**
     * Tasks that our shell utilizes.
     *
     * @var array
     * @access public
     */
    public $tasks = array(
        'LegacyAccount',
        'LegacyLicense',
        'LegacyFirm'
    );

    /**
     * PHP script memory limit
     *
     * @var array
     * @access private
     */
    private $_scriptMemLimit = '1024M';

    /**
     * Record counts
     *
     * @var array
     * @access public
     */
    public $counts = array(
        'total' => 0,
        'passed' => 0,
        'failed' => 0,
        'duplicate' => 0,
        'time' => null
    );

    /**
     * Menu choices
     *
     * @var array
     * @access private
     */
    private $_menu = array(
        'choices' => array(
            array('Import FULL Legacy Data',                     'importFullLegacyData'),
            array('Import Legacy Accounts',                      'importLegacyAccountData'),
            array('Import Legacy Practical Work Experience',     'importLegacyPracticalWorkExperienceData'),
            array('Import Legacy Wiring Experience',             'importLegacyWiringExperienceData'),
            array('Import Legacy Work Experience',               'importLegacyWorkExperienceData'),
            array('Import Legacy Licenses',                      'importLegacyLicenseData'),
            array('Import Legacy License Variants',              'importLegacyLicenseVariantsData'),
            array('Import Legacy Firms',                         'importLegacyFirmData'),
            array('Import Legacy Firm Licenses',                 'importLegacyFirmLicenseData'),
            array('Import Legacy Insurance Information',         'importLegacyInsuranceInformationData'),
            array('Import Legacy License Notes',                 'importLegacyLicenseNotesData'),
            array('Backup Database',                             'backupDB'),
            array('Export Database (Data Only)',                 'exportDB'),
            'Q' => array('Quit',                                 'quit'),
        ),
        'default' => 'q',
    );

    public function initialize()
    {
        // run the parent init
        parent::initialize();
    }

    public function loadLegacyObj($obj, $model)
    {
        // get the legacy account object
        if ($this->args[1] == 'ehsp')
        {
            require_once(ROOT.DS.'source'.DS.'deployment'.DS.'ehsp'.DS.$obj.'.php');
        }
        else
        {
            require_once(ROOT.DS.'source'.DS.'deployment'.DS.'dps'.DS.$obj.'.php');
        }

        $this->legacy_obj = new $obj($this->{$model});
    }

    /**
     * Display menu and return user selected choice.
     *
     * @return string User selected choice
     * @access public
     */
    public function menu()
    {
        $this->heading(__('Legacy Import Menu'));

        // generate menu
        foreach ($this->_menu['choices'] as $key => $def)
        {
            list ($desc, $method) = $def;
            $this->out(sprintf('[%s] %s', $key, __($desc)));
        }
        $this->out('');

        return strtoupper(
            $this->in(
                __('Which action would you like to take?'),
                array_keys($this->_menu['choices']),
                $this->_menu['default']
            )
        );
    }

    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        // set auth user to importer so system notes can be added by this shell
        CakeSession::write("Auth.User.id", null);
        CakeSession::write("Auth.User.label", 'Legacy Import');

        $start_time = time();

        try
        {
            // set the script memory limit
            ini_set('memory_limit', $this->_scriptMemLimit);

            $this->out();
            $this->out(str_pad(__('PHP allowed memory limit:'), 50), 0);
            $this->out(ini_get('memory_limit'));

            $this->out(str_pad(__('Log File Path:'), 50), 0);
            $this->out($this->outputDir.$this->outputFilename);

            // check for a command line argument, indicates triggered by Jenkins
            // if not present, show the menu for user input
            if (isset($this->args[0]))
            {
                $choice = $this->args[0];
            }
            else
            {
                $choice = $this->menu();
            }

            call_user_func(
                array($this, $this->_menu['choices'][$choice][1])
            );
        }
        catch (Exception $e)
        {
            $this->quit($e->getMessage(), 2);
        }

        $end_time = time();

        list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $end_time);
        $this->heading(sprintf('Import Complete (%sh, %sm, %ss)', $hours, $minutes, $seconds));

        if (file_exists($this->outputDir.$this->outputFilename))
        {
            $this->out(sprintf(__('Log File Path: %s'), $this->outputDir.$this->outputFilename), 2);
        }
    }

    /**
     * Run FULL legacy import
     *
     * @return void
     * @access public
     */
    public function importFullLegacyData()
    {
        $this->out();

        $this->logMessage('Starting Accounts Import...');
        $this->importLegacyAccountData();

        $this->logMessage('Starting PracticalWorkExperience Import...');
        $this->importLegacyPracticalWorkExperienceData();

        $this->logMessage('Starting WiringExperience Import...');
        $this->importLegacyWiringExperienceData();

        $this->logMessage('Starting Licenses Import...');
        $this->importLegacyLicenseData();

        $this->logMessage('Starting LicenseVariants Import...');
        $this->importLegacyLicenseVariantsData();

        $this->logMessage('Starting Firms Import...');
        $this->importLegacyFirmData();

        $this->logMessage('Starting FirmLicenses Import...');
        $this->importLegacyFirmLicenseData();

        $this->logMessage('Starting WorkExperience Import...');
        $this->importLegacyWorkExperienceData();

        $this->logMessage('Starting InsuranceInformations Import...');
        $this->importLegacyInsuranceInformationData();

        $this->logMessage('Starting Notes Import...');
        $this->importLegacyLicenseNotesData();
    }

    /**
     * Run legacy account import.
     *
     * @return void
     * @access public
     */
    public function importLegacyAccountData ()
    {
        // for ehsp, open a file for exporting
        if ($this->args[1] == 'ehsp')
        {
            $this->output_file = TMP.'accountUpdateLog.csv';

            if (($write_file = fopen($this->output_file, "w")) == false)
            {
                throw new Exception('Failed to open the account update log file.');
            }

            // define and write header row to output file
            $header_row = array(
                'First Name',
                'Middle Name',
                'Last Name',
                'Phone',
                'Successful Update?'
            );

            // write header row
            fputcsv($write_file, $header_row);

            // close output file
            fclose($write_file);
        }

        $sql_file = TMP.'ehsp_account_info_updates.sql';

        $this->importLegacyData('importAccounts', 'LegacyAccount');
    }

    /**
     * Run legacy practical work experience import.
     *
     * @return void
     * @access public
     */
    public function importLegacyPracticalWorkExperienceData ()
    {
        $this->importLegacyData('importPracticalWorkExperience', 'LegacyAccount');
    }

    /**
     * Run legacy wiring experience import.
     *
     * @return void
     * @access public
     */
    public function importLegacyWiringExperienceData ()
    {
        $this->importLegacyData('importWiringExperience', 'LegacyAccount');
    }

    /**
     * Run legacy work experience import.
     *
     * @return void
     * @access public
     */
    public function importLegacyWorkExperienceData ()
    {
        $this->importLegacyData('importWorkExperience', 'LegacyAccount');
    }

    /**
     * Run legacy firm import.
     *
     * @return void
     * @access public
     */
    public function importLegacyFirmData ()
    {
        $this->query("SET @FOREIGN_KEY_CHECKS=0; SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0; SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;", 'default');

        // import legacy firms
        $this->importLegacyData('importFirms', 'LegacyFirm');

        $this->query("SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS; SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;", 'default');
    }

    /**
     * Run legacy firm license import.
     *
     * @return void
     * @access public
     */
    public function importLegacyFirmLicenseData ()
    {
        $this->query("SET @FOREIGN_KEY_CHECKS=0; SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0; SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;", 'default');

        // import legacy firm licenses
        $this->importLegacyData('importFirmLicenses', 'LegacyLicense');

        $this->query("SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS; SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;", 'default');
    }

    /**
     * Run legacy license import.
     *
     * @return void
     * @access public
     */
    public function importLegacyLicenseData ()
    {
        $this->importLegacyData('importLicenses', 'LegacyLicense');
    }

    /**
     * Run legacy license notes import.
     *
     * @return void
     * @access public
     */
    public function importLegacyLicenseNotesData ()
    {
        $this->importLegacyData('importLicenseNotes', 'LegacyLicense');
    }

    /**
     * Run legacy insurance information import.
     *
     * @return void
     * @access public
     */
    public function importLegacyInsuranceInformationData ()
    {
        $this->importLegacyData('importInsuranceInformation', 'LegacyLicense');
    }

    /**
     * Run legacy license variants import.
     *
     * @return void
     * @access public
     */
    public function importLegacyLicenseVariantsData ()
    {
        $this->importLegacyData('importLicenseVariants', 'LegacyLicense');
    }

    /**
     * importLegacyData method
     *
     * @return void
     */
    private function importLegacyData ($legacy_config_key = null, $legacy_obj_name = null)
    {
        try
        {
            // start time
            $start_time = time();

            // reset the counts
            $this->resetCounts();

            // print heading
            $this->out(__(sprintf('<cyan>Importing Legacy %s...</cyan>', Inflector::humanize(Inflector::underscore($legacy_config_key)))));

            // get the legacy data
            $legacy_data = $this->query($this->{$legacy_obj_name}->legacy_obj->settings[$legacy_config_key]['query'], 'import');

            $record_index = 1;
            $record_total = count($legacy_data);

            foreach ($legacy_data as $record)
            {
                $this->counts['total']++;

                // process the record
                try
                {
                    /**
                     * import task will return
                     * $code = 1 success
                     * $code = 2 duplicate
                     * $code = 3 error
                     */
                    list($msg, $code) = $this->{$legacy_obj_name}->{$legacy_config_key}($record);

                    if ($this->params['progress'])
                    {
                        $this->updateProgress($start_time, time(), $record_total, $record_index);
                    }
                    $record_index++;

                    switch ($code)
                    {
                        case 1:
                            $this->counts['passed']++;
                            break;
                        case 2:
                            $this->counts['duplicate']++;
                            break;
                        case 3:
                            throw new Exception ($msg);
                            break;
                    }
                }
                catch (Exception $e)
                {
                    $this->counts['failed']++;
                    $this->logMessage($e->getMessage());
                    continue;
                }
            }
        }
        catch (Exception $e)
        {
            $this->logMessage($e->getMessage());
        }

        // end time
        $end_time = time();
        $this->counts['time'] = $this->calcTimePassed($start_time, $end_time);

        // report out
        $this->reportOut();
        $this->resetCounts();
    }

    public function exportDB ($datasource='default', $excludeTables=array())
    {
        // set up the DB export directory and file naming conventions
        if (isset($this->params['agency']))
        {
            $this->export_outputDir = ROOT.DS.'source'.DS.'deployment'.DS.$this->params['agency'].DS;
            $this->export_outputFilename = 'legacy.sql';
        }

        // tables we want to exclude
        $excludeTables = array(
            'abatement_statuses',
            'configurations',
            'counties',
            'course_catalogs',
            'course_catalogs_course_catalogs',
            'course_catalogs_license_types',
            'degrees',
            'dwelling_types',
            'element_license_types',
            'elements',
            'fee_modifiers',
            'fees',
            'firm_types',
            'group_programs',
            'groups',
            'license_statuses',
            'license_status_levels',
            'license_types',
            'license_type_variants',
            'output_document_types',
            'pages',
            'payment_types',
            'practical_work_experience_types',
            'practical_work_percentage_types',
            'program_certificates',
            'program_plugins',
            'programs',
            'questions',
            'register_plugins',
            'screening_questions',
            'states'
        );

        parent::exportDB($datasource, $excludeTables);
    }

    /**
     * Resets counts
     *
     * @return void
     * @access public
     */
    public function resetCounts()
    {
        $this->counts['total'] = 0;
        $this->counts['passed'] = 0;
        $this->counts['failed'] = 0;
        $this->counts['duplicate'] = 0;
        $this->counts['time'] = null;
    }

    /**
     * reportOut method
     *
     * Displays the total, passed, and failed count for legacy imports
     *
     * @param string $model Model
     *
     * @return void
     * @access public
     */
    public function reportOut()
    {
        // get the numbers
        $passed = str_pad($this->counts['passed'], 12, ' ');
        $failed = str_pad($this->counts['failed'], 12, ' ');
        $duplicate = str_pad($this->counts['duplicate'], 12, ' ');
        $total = str_pad($this->counts['total'], 12, ' ');
        $time = str_pad(sprintf('%sh %sm %ss', $this->counts['time'][0], $this->counts['time'][1], $this->counts['time'][2]), 12, ' ');

        // format the numbers for output
        $passed = (intval($passed) > 0) ? '<green>'.$passed.'</green>' : '<red>'.$passed.'</red>';
        $failed = (intval($failed) > 0) ? '<red>'.$failed.'</red>' : '<green>'.$failed.'</green>';
        $duplicate = (intval($duplicate) > 0) ? '<red>'.$duplicate.'</red>' : '<green>'.$duplicate.'</green>';
        $total = (intval($total) > 0) ? '<cyan>'.$total.'</cyan>' : '<red>'.$total.'</red>';
        $time = '<cyan>'.$time.'</cyan>';

        // output the report
        $this->hr();
        $this->out('  '.str_pad('Passed', 12, ' ').str_pad('Failed', 12, ' ').str_pad('Duplicate', 12, ' ').str_pad('Total', 12, ' ').str_pad('Run Time', 12, ' '));
        $this->out('  '.$passed.str_pad($failed, 12, ' ').str_pad($duplicate, 12, ' ').str_pad($total, 12, ' ').str_pad($time, 12, ' '));
        $this->hr();
    }

    /**
     * mapData method
     *
     * Maps the data returned from the legacy lookup into the cake-expected data array
     *
     * @param array $legacy_data
     *
     * @return array
     * @access public
     */
    public function _mapData(&$legacy_data, $method_map)
    {
        $data = array();

        foreach ($this->legacy_obj->settings[$method_map]['data_map'] as $elf_notation => $legacy_fields)
        {
            if (!is_array($legacy_fields))
            {
                $legacy_fields = array($legacy_fields);
            }

            foreach ($legacy_fields as $legacy_field)
            {
                $field = explode('.', $legacy_field);

                $table = $legacy_data[$field[0]];
                $field_1 = $field[1];

                $value = $legacy_data[$field[0]][$field_1];

                //checks for isset
                if (array_key_exists($field_1, $table))
                {
                    $data[$elf_notation] = $value;
                }

                //if there is a value, move on
                if (!empty($data[$elf_notation]))
                {
                    break;
                }
            }
        }
        return Hash::expand($data);
    }

    public function legacyFilters($legacy_data, $method_map)
    {
        $legacy_data = Hash::flatten($legacy_data);

        $filters = null;
        if (isset($this->legacy_obj->settings[$method_map]['filter']))
        {
            $filters = $this->legacy_obj->settings[$method_map]['filter'];
        }

        foreach ($legacy_data as $field => $value)
        {
            if (isset($filters[$field]))
            {
                //If not array, make array
                if (!is_array($filters[$field]))
                {
                    $filters[$field] = array($filters[$field]);
                }
                //check that function exists
                foreach ($filters[$field] as $func)
                {
                    $legacy_data[$field] = $this->legacy_obj->{$func}($value, Hash::expand($legacy_data));
                }
            }
        }
        return Hash::expand($legacy_data);
    }

    public function validationErrorsToString($errors = array())
    {
        $validation_errors = '';
        foreach ($errors as $field => $err_msg)
        {
            if (is_array($err_msg))
            {
                $err_msg = serialize($err_msg);
            }

            $validation_errors .= $err_msg.', ';
        }

        return preg_replace('/,\s$/', '', $validation_errors);
    }
}
