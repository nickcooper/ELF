<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportApplicationSectionTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportApplicationSectionTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Element');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'Element';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'Element.label',
        1 => 'Element.element_plugin',
        2 => 'Element.element',
        3 => 'Element.foreign_plugin',
        4 => 'Element.foreign_obj',
        5 => 'Element.data_keys',
        6 => 'Element.description'
    );


    /**
     * importRow method
     *
     * @param array $row data provided by the import csv file
     *
     * @return true if successful
     * @access public
     */
    public function importRow($row)
    {
        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        // search for an already existing element
        $element = $this->Element->find(
            'first',
            array(
                'conditions' => array(
                    'Element.label' => $this->data['Element']['label'],
                    'Element.element_plugin' => $this->data['Element']['element_plugin'],
                    'Element.element' => $this->data['Element']['element'],
                    'Element.foreign_plugin' => $this->data['Element']['foreign_plugin'],
                    'Element.foreign_obj' => $this->data['Element']['foreign_obj']
                )
            )
        );

        // if no previous element found, save this data as a new element
        // otherwise, add the id of the existing element, then update that record with the new data
        if (!$element)
        {
            parent::saveRecord();
        }
        else
        {
            $this->data['Element']['id'] = $element['Element']['id'];
            $this->Element->save($this->data);
        }

        // passed
        return true;
    }
}
