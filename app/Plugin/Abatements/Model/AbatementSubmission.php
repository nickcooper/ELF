<?php
/**
 * AbatementSubmission model
 *
 * @package Abatements.Model
 * @author  Iowa Interactive, LLC.
 */
class AbatementSubmission extends AbatementsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'AbatementSubmission';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'date';

    /**
     * Model behaviors.
     *
     * @var Array
     * @access public
     */
    public $actsAs = array(
        'Searchable.Searchable'
    );

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Abatement' => array(
            'className'  => 'Abatements.Abatement',
            'foreignKey' => 'abatement_id',
        )
    );

    /**
     * Adds submission record for abatement.
     *
     * @param id     $abatement_id Abatement id
     * @param array  $phase_data   Phase data
     * @param string $type         type string
     *
     * @return array $type submission type (Initial|Revision).
     * @access public
     */
    public function addSubmission($abatement_id = null, $phase_data = null, $type = null)
    {
        if (!$this->Abatement->exists($abatement_id))
        {
            throw new Exception(sprintf(__('Abatement (%s) could not be found'), $abatement_id));
        }

        if (empty($type))
        {
            throw new Exception('Abatement submission type not given');
        }

        $data = array(
            'AbatementSubmission' => array(
                'abatement_id' => $abatement_id,
                'type' => $type,
                'date' => date('Y-m-d H:i:s'),
                'data' => serialize($phase_data)
            )
        );

        if (!$this->save($data))
        {
            throw new Exception('Abatement submission could not be saved');
        }

        return true;
    }
}