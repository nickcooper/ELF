<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportConfigurationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportConfigurationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Configuration', 'Accounts.Program');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Configuration';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Configuration.program_id',
        1 => 'Configuration.plugin',
        2 => 'Configuration.name',
        3 => 'Configuration.value',
        4 => 'Configuration.options',
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
        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        // get the program_id
        $this->data['Configuration']['program_id'] = null;

        if ($program = $this->Program->findByAbbr($row[0]))
        {
            $this->data['Configuration']['program_id'] = $program['Program']['id'];
        }

        // check to see if this configuration program/plugin/key combo already exists
        $previous_record = $this->Configuration->find(
            'first',
            array(
                'conditions' => array(
                    'Configuration.program_id' => $this->data['Configuration']['program_id'],
                    'Configuration.plugin' => $this->data['Configuration']['plugin'],
                    'Configuration.name' => $this->data['Configuration']['name']
                )
            )
        );

        if ($previous_record)
        {
            $this->data['Configuration']['id'] = $previous_record['Configuration']['id'];
        }

        // save data to database
        $this->saveRecord();

        // passed
        return true;
    }

    /**
     * saveRecord
     *
     * @return void
     */
    public function saveRecord()
    {
        // primary save model
        $PrimaryModel = $this->{$this->primary_model};

        // initiate a new object record for the primary model to insert
        $PrimaryModel->create();

        if (isset($this->data['Configuration']['options']) && !empty($this->data['Configuration']['options']))
        {
            // convert pipe delimited to serialized array
            $this->data['Configuration']['options'] = serialize(explode('|', $this->data['Configuration']['options']));
        }

        // attempt data save
        if (!$PrimaryModel->save($this->data) || $PrimaryModel->getAffectedRows() == 0)
        {
            if (count($PrimaryModel->validationErrors) > 0)
            {
                $errors = Set::flatten($PrimaryModel->validationErrors);
                foreach ($errors as $key => $error)
                {
                    $this->out($key.' => '.$error);
                }
            }

            throw new Exception('Failed to save data.');
        }
    }
}
