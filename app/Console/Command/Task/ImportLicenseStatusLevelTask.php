<?php

/**
 * ImportLicenseStatusLevelTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportLicenseStatusLevelTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('LicenseStatusLevel');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'LicenseStatusLevel';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'LicenseStatusLevel.level',
        1 => 'LicenseStatusLevel.descr',
    );
}
