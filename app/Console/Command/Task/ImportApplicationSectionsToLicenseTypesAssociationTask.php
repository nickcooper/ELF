<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportApplicationSectionsToLicenseTypesAssociationTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportApplicationSectionsToLicenseTypesAssociationTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.LicenseType', 'Licenses.Element', 'Licenses.ElementLicenseType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'ElementLicenseType';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'ElementLicenseType.license_type_id',
        1 => 'ElementLicenseType.element_id',
        2 => 'ElementLicenseType.label',
        3 => 'ElementLicenseType.order',
        4 => 'ElementLicenseType.initial_required',
        5 => 'ElementLicenseType.renewal_required',
        6 => 'ElementLicenseType.conversion_required',
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
        $license_type = $this->LicenseType->findByAbbr($row[0]);
        if (!$license_type)
        {
            throw new Exception(sprintf("`%s` not found in license_types", $row[1]));
        }

        $element = $this->Element->find(
            'first',
            array(
                'conditions' => array(
                    'Element.label' => $row[1],
                    'Element.foreign_obj' => $license_type['LicenseType']['foreign_obj']
                )
            )
        );

        if (!$element)
        {
            // if foreign obj related element not found check for application based elements
            $element = $this->Element->find(
                'first',
                array(
                    'conditions' => array(
                        'Element.label' => $row[1],
                        'Element.foreign_obj' => 'Application'
                    )
                )
            );
            if (!$element)
            {
                throw new Exception(sprintf("`%s` not found in variants", $row[1]));
            }
        }

        $row[0] = $license_type['LicenseType']['id'];
        $row[1] = $element['Element']['id'];
        $row[4] = $row[4] == 'Yes' ? 1 : 0;
        $row[5] = $row[5] == 'Yes' ? 1 : 0;
        $row[6] = $row[6] == 'Yes' ? 1 : 0;

        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        // save data to database
        $this->saveRecord();

        // passed
        return true;
    }
}
