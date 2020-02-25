<?php
/**
 * Abatement Phase model
 *
 * @package Abatements.Model
 * @author  Iowa Interactive, LLC.
 */
class AbatementPhase extends AbatementsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'AbatementPhase';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Abatement' => array(
            'className' => 'Abatements.Abatement',
            'foreignKey' => 'abatement_id'
        ),
    );

    /**
     * Validation rules.
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'begin_date' => array(
            'rule' => array('date', 'ymd'),
            'message' => array('Enter a valid start date.'),
        ),
        'end_date' => array(
            'rule' => array('date', 'ymd'),
            'message' => array('Enter a valid end date.'),
        ),
    );

    /**
     * afterSave callback
     *
     * @param bool $created true/false if record was inserted
     *
     * @return void
     */
    function afterSave($created)
    {
        // reload the abatement phase data
        $phase = $this->read();

        // get the parent abatement record
        $abatement_data = $this->Abatement->details($phase['AbatementPhase']['abatement_id']);

        // update the parent abatement record's begin and end dates
        $this->updateParentAbatementStartEndDates($abatement_data['AbatementPhase']);
    }

    /**
     * updateParentAbatementStartEndDates callback
     *
     * @param array $abatement_phases an array of abatement phases associated to a given abatement
     *
     * @return void
     */
    function updateParentAbatementStartEndDates($abatement_phases = null)
    {
        try
        {
            $tmp_begin_date = null;
            $tmp_end_date = null;
            $data = null;

            // loop through the phases and determine the earliest begin date and latest end date
            // of them all
            foreach ($abatement_phases as $abatement_phase)
            {
                // find the earliest begin date
                if ($tmp_begin_date == null)
                {
                    $tmp_begin_date = $abatement_phase['begin_date'];
                }
                elseif ($abatement_phase['begin_date'] < $tmp_begin_date)
                {
                    $tmp_begin_date = $abatement_phase['begin_date'];
                }

                // find the latest end date
                if ($tmp_end_date == null)
                {
                    $tmp_end_date = $abatement_phase['end_date'];
                }
                elseif ($abatement_phase['end_date'] > $tmp_end_date)
                {
                    $tmp_end_date = $abatement_phase['end_date'];
                }
            }

            // update the parent abatement record's begin and end date
            if (!empty($abatement_phases[0]['id']))
            {
                $data['Abatement']['id'] = $abatement_phases[0]['abatement_id'];
                $data['Abatement']['begin_date'] = $tmp_begin_date;
                $data['Abatement']['end_date'] = $tmp_end_date;

                $this->Abatement->edit($data);
            }
        }
        catch (Exception $e)
        {
            throw new Exception(
                sprintf(
                    __('The Start/End dates for Abatement (%s) could not be updated.'),
                    $abatement_phases[0]['abatement_id']
                )
            );
        }
    }
}