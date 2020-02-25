<?php
/**
 * ImportRegisteredPluginTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportRegisteredPluginTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('RegisterPlugin');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'RegisterPlugin';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'RegisterPlugin.label',
        1 => 'RegisterPlugin.descr',
        2 => 'RegisterPlugin.plugin',
        3 => 'RegisterPlugin.path',
        4 => 'RegisterPlugin.uri',
        5 => 'RegisterPlugin.home',
        6 => 'RegisterPlugin.enable',
    );

    /**
     * Import a single row of data
     *
     * @param arrary $row array of row data to import
     *
     * @return void
     * @access public
     */
    public function importRow($row)
    {
        $row[6] = $row[6] == 'Yes' ? 1 : 0;

        parent::importRow($row);
    }
}
