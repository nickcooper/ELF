<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportProgramGroupTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportProgramGroupTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('AppModel', 'Accounts.GroupProgram', 'Accounts.Program', 'Accounts.Group');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Group';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Group.label',
        1 => 'Group.descr',
        2 => 'Group.home',
        3 => 'Group.program_id',
        4 => 'Group.group_program_id',
        5 => 'Group.enabled',
        6 => 'Group.admin'
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
        try
        {
            // get the program data
            if (!$program = $this->Program->findByAbbr($row[0]))
            {
                throw new Exception(sprintf('Failed to find program (%s)', $row[0]));
            }

            // get the group data
            if (!$group_program = $this->GroupProgram->findByLabel($row[1]))
            {
                throw new Exception(sprintf('Failed to find group (%s)', $row[1]));
            }

            // build the group
            if (!$group = $this->Group->makeGroups(array($group_program['GroupProgram']['id']), $program['Program']))
            {
                throw new Exception('Could not build the group.');
            }

            // format the group data for mapping
            $group = array(
                $group['Group'][0]['label'],
                $group['Group'][0]['descr'],
                $group['Group'][0]['home'],
                $group['Group'][0]['program_id'],
                $group['Group'][0]['group_program_id'],
                ($group_program['GroupProgram']['enabled'] ? 1 : 0),
                ($group['Group'][0]['admin'] ? 1 : 0)
            );

            // save the group
            parent::importRow($group);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }
}
