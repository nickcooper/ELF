<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportFeeModifierTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportFeeModifierTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Payments.Fee','Payments.FeeModifier');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'FeeModifier';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'FeeModifier.fee_id',
        1 => 'FeeModifier.label',
        2 => 'FeeModifier.type',
        3 => 'FeeModifier.fee',
        4 => 'FeeModifier.start_range',
        5 => 'FeeModifier.end_range',
    );

    /**
     * Import a single row of data
     *
     * @param arrary $row array of row data to import
     *
     * @return boolean
     * @access public
     */
    public function importRow($row)
    {
        $fee = $this->Fee->findByLabel($row[0]);

        if (!$fee)
        {
            throw new Exception(sprintf("`%s` not found in fees", $row[0]));
        }

        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        $this->data['FeeModifier']['fee_id'] = $fee['Fee']['id'];

        // save data to database
        $this->saveRecord();

        // passed
        return true;
    }
}
