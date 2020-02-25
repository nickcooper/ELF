<?php

/**
 * VanillaAccountImportShell
 *
 * Reads an account file and creates user accounts listed within the appropriate group specified in the file.
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class VanillaAccountImportShell extends AppShell
{
    /**
     *
     * Load the necessary database models
     *
     */
    public $uses = array(
        'Accounts.Account',
    );
    /**
     * Tasks that our shell utilizes.
     *
     * @var array
     * @access public
     */
    public $tasks = array(
        'ImportAccount',
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

            $this->out('Vanilla Account Import Shell started...');

            // set the script memory limit
            ini_set('memory_limit', $this->_scriptMemLimit);
            $this->out(str_pad(__('PHP allowed memory limit: '), 50).ini_get('memory_limit'));

            // delete existing SuperAdmin accounts
            $this->deleteSuperAdmins();

            // Run Vanilla Task
            $this->importVanilla();

            $this->out();

            $this->hr();

            $end_time = time();

            list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $end_time);

            $this->out(sprintf('Vanilla Account Import Shell finished! Execution time: (%sh, %sm, %ss)', $hours, $minutes, $seconds), 2);
        }
        catch (Exception $e)
        {
            // fail - If exception, return 1 (fail code) so capistrano does rollback deploy.
            $this->logMessage($e->getMessage(), 2);
            exit(1);
        }
    }

    /**
     * deleteSuperAdmins method
     *
     * Deletes existing superAdmin accounts in the system.
     *
     * @return void
     * @access public
     */
    public function deleteSuperAdmins()
    {
        $this->hr();
        $this->out('Deleting existing SuperAdmin accounts...');

        // find the existing SuperAdmin accounts
        $this->Account = ClassRegistry::init('Accounts.Account');
        $found_accounts = $this->Account->find(
            'all',
            array(
                'fields' => array('id', 'group_id', 'label', 'username'),
                'conditions' => array(
                    'Account.group_id' => '2',
                ),
            )
        );

        // delete each SuperAdmin account
        foreach($found_accounts as $account)
        {
            // build the sql command. Turn off foreign key checks.
            // PRESUMPTION: SuperAdmin accounts are only II EEs, and they do not have licenses/data that will be
            // orphaned by deleting the account.
            $sql_command = sprintf('SET foreign_key_checks = 0; ');
            $sql_command .= sprintf('DELETE FROM accounts WHERE id = %s; ', $account['Account']['id']);
            $sql_command .= sprintf('SET foreign_key_checks = 1;');

            $this->Account->query($sql_command);
        }
    }

    /**
     * importVanilla method
     *
     * Builds user accounts for the user's listed in the data file.
     *
     * @return void
     * @access public
     */
    public function importVanilla()
    {
        // set the start timestamp
        $start_time = time();

        $this->hr();
        $this->out('Vanilla import started...');
        $this->out('Start time: '. date('D Y/m/d  h:i:s'));

        // set the import type
        $this->import_type = 'Vanilla';

        // set the path of the source file that lists the user accounts to build
        $file = ROOT.DS.'source'.DS.'deployment'.DS.'vanilla'.DS.'vanillaAccounts.csv';
        $file_exists = file_exists($file);
        if ($file_exists)
        {
            $this->outSuccess(sprintf('Looking for Vanilla Accounts CSV file: %s', $file));

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
            $this->outFailure(sprintf('Looking for Vanilla Accounts CSV file: %s', $file));
            //$this->quit(__('Missing Files'), 2);
        }

        // set the end timestamp
        $end_time = time();
        $this->out('End time: '. date('D Y/m/d  h:i:s'));

        list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $end_time);
        $this->out(sprintf('Vanilla task finished! Execution time: (%sh, %sm, %ss)', $hours, $minutes, $seconds), 2);
    }

    /**
     * processRecord method
     *
     * Process a row in the input data file.
     *
     * @param  array $row a record from the input data file
     *
     * @return void
     * @access public
     */
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

    /**
     * isRowBlank method
     *
     * Checks for a blank row provided in the input data file
     *
     * @param  array $row a record from the input data file
     *
     * @return void
     * @access public
     */
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

    /**
     * isRowNewSection method
     *
     * Checks for a new section provided in the input data file
     *
     * @param  array $row a record from the input data file
     *
     * @return void
     * @access public
     */
    public function isRowNewSection($row)
    {
        if (preg_match('/\[(.*?)\]/', $row[0], $matches))
        {
            $this->outSectionCount();
            $this->section = $matches[1];
            $this->task = "Import".Inflector::classify(str_replace(' ', '_', strtolower($this->section)));

            if (!in_array($this->task, $this->tasks))
            {
                $this->out($this->task.' task not found.');
            }

            return true;
        }
        return false;
    }

    /**
     * isRowColumnHeader method
     *
     * Checks for a row's column header provided in the input data file
     *
     * @param  array $row a record from the input data file
     *
     * @return void
     * @access public
     */
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

    /**
     * outSectionCount method
     *
     * Prints out summary of a section's import results
     *
     * @return void
     * @access public
     */
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