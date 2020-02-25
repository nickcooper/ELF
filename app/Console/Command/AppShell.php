<?php

Configure::write('Cache.disable', true);
App::uses('CakeSession', 'Model/Datasource', 'ConnectionManager', 'Model');

/**
 * AppShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class AppShell extends Shell
{
    /**
     * Models we have access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('AppModel');

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
     * Location of database export output directory.
     *
     * @var array
     * @access public
     */
    public $export_outputDir = null;

    /**
     * The database export file name.
     *
     * @var string
     * @access public
     */
    public $export_outputFilename = null;


    /**
     * heading method
     *
     * Simple method to format shell headings
     *
     * @param str $heading Heading text to display in shell output.
     *
     * @return void
     * @access public
     */
    public function heading($heading='')
    {
        $this->out('');
        $this->out(str_repeat('=', strlen($heading)+6));
        $this->out('|  <cyan>'.$heading.'</cyan>  |');
        $this->out(str_repeat('=', strlen($heading)+6));
        $this->out('');
    }

    /**
     * initialize function
     *
     * @return void
     * @access public
     */
    public function initialize()
    {
        $mtime = $this->getMicroTime();
        $this->outputDir = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS;
        $this->outputFilename = $mtime.'.txt';

        $this->export_outputDir = ROOT.DS.'app'.DS.'tmp'.DS;
        $this->export_outputFilename = $mtime.'.sql';

        // set some styles for output
        $this->stdout->styles('green', array('text' => 'green'));
        $this->stdout->styles('red', array('text' => 'red'));
        $this->stdout->styles('yellow', array('text' => 'yellow'));
        $this->stdout->styles('cyan', array('text' => 'cyan'));

        parent::initialize();
    }


    /**
     * memoryUsage method
     *
     * Simple method to display memory usage
     *
     * @return void
     * @access public
     */
    public function memoryUsage()
    {
        $conversion = 'bytes';
        $mem_usage = memory_get_usage(true);

        if ($mem_usage < 1048576)
        {
            $conversion = 'kilobytes';
            $mem_usage = round($mem_usage/1024, 2);
        }
        else
        {
            $conversion = 'megabytes';
            $mem_usage = round($mem_usage/1048576, 2);
        }

        $this->out('');
        $this->out(sprintf('Memory Usage: %s %s', $mem_usage, $conversion));
        $this->out('');
    }

    /**
     * quit method
     *
     * @param str &$msg   Quit string message to disply in shell output on quit
     * @param int $status Quit code
     *
     * @return void
     * @access public
     */
    public function quit(&$msg="", $status=1)
    {
        // check for quit status
        switch($status) {
        // normal/prompted quit
        case 1:
            $msg = sprintf(__('<red>Exit</red> -- %s'), $msg ? $msg : __('Quitting Shell'));
            break;
        // recognized error
        case 2:
            $msg = sprintf(__('<red>Script Error</red> -- %s'), $msg ? $msg : __('Undefined'));
            break;
        // unrecognized error quit
        case 3:
        default:
            $msg = sprintf(__('<red>Unexpected Error</red> -- %s'), $msg ? $msg : __('Undefined'));
            break;
        }
        $this->out($msg, 2);

        unset($msg, $status);

        $this->_stop();
    }

    /**
     * backupDB method
     *
     * Backs up the entire database.
     *
     * @param str $datasource Which cake data source configuration to use.
     *
     * @return void
     * @access public
     */
    public function backupDB($datasource='default')
    {
        $this->heading(__('Attempting Database Backup'));

        // load the db configuration
        $dataSource = ConnectionManager::getDataSource($datasource);
        $dbConfig = $dataSource->config;

        $backupDir = sprintf('%s/source/database/backups', ROOT);
        $backupFile = sprintf('%s/%s-%s-%s.sql.bz2', $backupDir, $datasource, $dbConfig['database'], date('YmdHis'));

        if (! file_exists($backupDir))
        {
            if (! mkdir($backupDir, 0755, true))
            {
                throw new Exception(sprintf(__('Backup dir %s could not be created'), $backupDir));
            }
        }

        // attempt to make a backup
        $cmd = sprintf(
            'mysqldump -h%s -u%s -p%s %s | bzip2 -9 > %s',
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['login']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['database']),
            escapeshellarg($backupFile)
        );
        passthru($cmd, $retval);

        if (is_file($backupFile) && filesize($backupFile) > 100)
        {
            $this->out(__('Database backup successful.'));
            $this->out(sprintf(__('Filename: %s'), $backupFile));

            return true;
        }

        @unlink($backupFile);
        $this->quit(__('Database backup attempt failed.'), 2);
    }

    /**
     * Exports a database. This is essentially the same as `$this->backupDB()`
     * but this method doesn't drop and re-create tables, only exports
     * data for insert.
     *
     * @param string $datasource    Datasource (default: 'default')
     * @param array  $excludeTables An array of tables to exclude from the export.
     *
     * @return void
     * @access public
     */
    public function exportDB($datasource='default', $excludeTables=array())
    {
        $this->heading(__('Attempting Database Export'));

        $ds = ConnectionManager::getDataSource($datasource);
        $tables = $ds->listSources();
        $dbConfig = $ds->config;

        if (! file_exists($this->export_outputDir))
        {
            if (! mkdir($this->export_outputDir, 0755, true))
            {
                throw new Exception(sprintf(__('Export dir %s could not be created'), $this->export_outputDir));
            }
        }

        // include views
        $excludeTables = array_merge($excludeTables, $this->_getDatabaseViews($datasource));

        $fp = fopen($this->export_outputDir.$this->export_outputFilename, 'w');

        fwrite($fp, sprintf('-- %s Export', $dbConfig['database']));
        fwrite($fp, PHP_EOL);
        fwrite($fp, sprintf('-- Generated %s', date('Y-m-d H:i:s')));
        fwrite($fp, PHP_EOL);
        fwrite($fp, PHP_EOL);
        fwrite($fp, 'SET foreign_key_checks=0;');
        fwrite($fp, PHP_EOL);

        foreach ($tables as $table)
        {
            if (in_array($table, $excludeTables))
            {
                continue;
            }

            fwrite($fp, PHP_EOL);
            fwrite($fp, sprintf('-- %s', $table));
            fwrite($fp, PHP_EOL);
            fwrite($fp, sprintf('TRUNCATE `%s`;', $table));
            fwrite($fp, PHP_EOL);

            $cmd = sprintf(
                'mysqldump --no-create-info --complete-insert -h%s -u%s -p%s %s %s',
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['login']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['database']),
                escapeshellarg($table)
            );

            exec($cmd, $output, $retval);
            $output = join(PHP_EOL, $output);
            $output .= PHP_EOL;

            fwrite($fp, $output, strlen($output));

            $this->out(sprintf(__('Exported table: %s'), $table));
        }

        fwrite($fp, PHP_EOL);
        fwrite($fp, 'SET foreign_key_checks=1;');
        fwrite($fp, PHP_EOL);

        fclose($fp);

        // compress backup
        $cmd = sprintf('bzip2 -9 %s', escapeshellarg($this->export_outputDir.$this->export_outputFilename));
        passthru($cmd, $retval);

        if ($retval == 0)
        {
            $this->out(__('Database export successful.'));
            $this->out(sprintf(__('Filename: %s.bz2'), $this->export_outputDir.$this->export_outputFilename));
            return true;
        }

        @unlink($this->export_outputDir.$this->export_outputFilename);
        $this->quit(__('Database export attempt failed.'), 2);
    }

    /**
     * Queries the database given SQL
     *
     * @param string  $sql                    SQL query
     * @param string  $dbConfig               Database config to use (default: 'default')
     * @param boolean $ignoreForeignKeyChecks Whether or not to ignore foreign key checks (default: false)
     *
     * @return array Search results from query
     */
    public function query($sql, $dbConfig='default', $ignoreForeignKeyChecks=false)
    {
        $this->AppModel->useTable = false;
        $this->AppModel->useDbConfig = $dbConfig;

        // NOTE: MySQL-only
        if ($ignoreForeignKeyChecks)
        {
            $sql = join(
                '; ',
                array('SET foreign_key_checks=0', $sql, 'SET foreign_key_checks=1;' )
            );
        }

        return $this->AppModel->query($sql);
    }

    /**
     * Records an error message to a CSV spreadsheet
     *
     * @param string $message Error message
     *
     * @return void
     * @access public
     */
    public function logMessage($message=null)
    {
        $outputDir = $this->outputDir;

        if (! file_exists($outputDir))
        {
            mkdir($outputDir, 0775, true);
        }

        $fp = null;
        $line = array();
        if (! file_exists($outputDir.$this->outputFilename))
        {
            $fp = fopen($outputDir.$this->outputFilename, 'w+');
            $line = sprintf('"%s"', join('","', $line)).PHP_EOL;
            fwrite($fp, $line, strlen($line));
        }
        else
        {
            $fp = fopen($outputDir.$this->outputFilename, 'a');
        }

        $line = array(date('Y-m-d H:i:s'), $message);
        $line = sprintf('"%s"', join('","', $line)).PHP_EOL;

        fwrite($fp, $line, strlen($line));
        fclose($fp);
    }

    /**
     * Retrieves a list of views for a datasource.
     *
     * @param string $datasource Datasource (default: 'default')
     *
     * @return array Views
     * @access private
     */
    private function _getDatabaseViews($datasource='default')
    {
        $views = array();
        $viewKey = sprintf(
            'Tables_in_%s',
            ConnectionManager::getDataSource($datasource)->config['database']
        );

        $results = $this->query("SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'", $datasource);
        foreach ($results as $result)
        {
            $views[] = $result['TABLE_NAMES'][$viewKey];
        }

        return $views;
    }

    /**
     * Returns the hours, minutes, seconds that have passed between two timestamps.
     *
     * @param str $start starting timestamp
     * @param str $end   ending timestamp
     *
     * @return array hours, minutes, seconds
     * @access public
     */
    public function calcTimePassed($start = null, $end = null)
    {
        // default return value
        $retVal = array(0, 0, 0); // h, m, s

        // validate the inputs are digits only
        foreach (array('start', 'end') as $timestamp)
        {
            if (! preg_match('/[0-9]+/', ${$timestamp}))
            {
                return $retVal;
            }
        }

        $diff = $end - $start;
        if ($diff > 0)
        {
            $retVal = array(
                $diff / 3600 % 24,  // hours
                $diff / 60 % 60,    // minutes
                $diff % 60          // seconds
            );
        }

        return $retVal;
    }

    /**
     * Display succuess message to shell output
     *
     * @param str $msg success message
     *
     * @return void
     * @access public
     */
    public function outSuccess($msg = 'Undefined')
    {
        $this->out('    [ <green>SUCCESS</green> ] '.$msg);
    }

    /**
     * Display failure message to shell output
     *
     * @param str $msg failure message
     *
     * @return void
     * @access public
     */
    public function outFailure($msg = 'Undefined')
    {
        $this->out('    [ <red>FAILURE</red> ] '.$msg);
    }

    /**
     * Define option parser.
     *
     * @return obj
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

        $parser->addOption(
            'progress',
            array(
                'help'    => __('Shows progress of import while importing.'),
                'default' => null,
                'boolean' => true
            )
        );

        //configure parser
        return $parser;
    }

    /**
     * Truncate table
     *
     * @param str $table Table name to truncate
     *
     * @return void
     * @access public
     */
    public function truncateTable($table)
    {
        if ($this->query(sprintf('TRUNCATE %s', $table), 'default', true))
        {
            $this->outSuccess(sprintf('Truncate %s table.', $table));
            return true;
        }

        $this->outFailure(sprintf('Truncate %s table.', $table));

        return false;
    }

    /**
     * Get formatted microtime
     *
     * @return str
     * @access public
     */
    public function getMicroTime()
    {
        list($unix, $ms) = explode('.', microtime(true));
        return date('YmdHis', $unix).$ms;
    }

    /**
     * Display progress of a process in shell output
     *
     * @param str $start_time        start time
     * @param str $current_time      current time
     * @param str $total_records     total process records
     * @param str $completed_records total completed records
     *
     * @return void
     * @access public
     */
    public function updateProgress($start_time, $current_time, $total_records, $completed_records)
    {
        $percent = $completed_records / $total_records;

        $complete_bars = round(24 * $percent);
        $incomplete_bars = 24 - $complete_bars;

        $bar = sprintf('[<green>%s</green><red>%s</red>]', str_repeat('=', $complete_bars), str_repeat('-', $incomplete_bars));

        list($hours, $minutes, $seconds) = $this->calcTimePassed($start_time, $current_time);

        $time = sprintf('%s:%s:%s', str_pad($hours, 2, "0", STR_PAD_LEFT), str_pad($minutes, 2, "0", STR_PAD_LEFT), str_pad($seconds, 2, "0", STR_PAD_LEFT));

        $record_count = str_pad(sprintf('%s/%s', $completed_records, $total_records), 15, ' ', STR_PAD_LEFT);

        $percent_complete = str_pad(sprintf('(%s%%)', sprintf("%01.2f", $percent * 100)), 9, ' ', STR_PAD_LEFT);

        $progess = sprintf(
            '  %s %s %s %s',
            $time,
            $bar,
            $record_count,
            $percent_complete
        );

        if ($completed_records != 1)
        {
            print str_repeat(chr(8), 63);
        }

        $newline = ($total_records == $completed_records) ? 1: 0;

        $this->out($progess, $newline);
    }
}
