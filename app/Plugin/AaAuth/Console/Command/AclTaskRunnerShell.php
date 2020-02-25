<?php

/**
 * AclTaskRunnerShell
 *
 * @package    App.Plugin.AaAuth
 * @subpackage App.Plugin.AaAuth.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class AclTaskRunnerShell extends AppShell
{
    public $tasks = array('AaAuth.AclControllers');
    //public $uses = array('Account','Aro','Group', 'Aco', 'Acl');

    /**
     * Main entry point
     *
     * @return void
     * @access public
     */
    function main()
    {
        $this->AclControllers->execute();
    }
}