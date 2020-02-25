<?php

App::import('Lib', 'GenLib');

/**
 * ClearCacheShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class ClearCacheShell extends AppShell
{
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
            // Temporarily enable caching since having it disabled will cause Cache::isInitialized() to return
            // false, which causes GenLib::clearCache() to throw an exception and never clear out anything.
            // This is set in Command/AppShell.php
            $orig = Configure::read('Cache.disable');
            Configure::write('Cache.disable', false);

            GenLib::clearCache();

            // Re-enable our previous configuration for disabling cache
            Configure::write('Cache.disable', $orig);
        }
        catch (Exception $e)
        {
            $this->stdout->styles('fatal', array('text' => 'red', 'bold' => true));
            $message = sprintf('<fatal>FAIL</fatal> %s', $e->getMessage());
            $this->out($message);

            exit(1);
        }
    }
}
