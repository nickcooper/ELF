<?php
/**
 * InstructorAssignment Model
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class InstructorAssignment extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'InstructorAssignment';

    /**
     * belongsTo Model Relationships
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'TrainingProvider' => array(
            'className' => 'ContinuingEducation.TrainingProvider',
            'foreignKey' => 'training_provider_id',
        ),
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
    );

    /**
     * afterSave Callback
     *
     * @param boolean $created true if it was a newly created record
     *
     * @return boolean success
     */
    public function afterSave($created)
    {
        if ($created)
        {
            // Set the training provider license record to pending when a new
            // instructor is added to the license

            //debug($this->data);die();
        }

        return true;
    }

    /**
     * Approves all InstructorAssignments for a given Training Provider
     *
     * @param int  $training_provider_id Training Provider Id
     * @param date $approved_date        Date that you'd like to be set as the approved date
     *
     * @return boolean
     */
    public function approveInstructorsForTrainingProvider($training_provider_id, $approved_date=null)
    {
        if (empty($approved_date))
        {
            $approved_date = date('Y-m-d H:i:s');
        }
        else
        {
            $approved_date = date('Y-m-d H:i:s', strtotime($approved_date));
        }

        return $this->updateAll(
            array(
                'approved' => true,
                'approved_date' => "'$approved_date'",
            ),
            array(
                'training_provider_id' => $training_provider_id,
            )
        );
    }

    /**
     * Overriding the AppModel defined getList function so we can return a list of accounts
     * This will actually be indexed by account id instead of instructor_approval ids because we
     * never want to have children of these nodes
     *
     * @param int    $training_provider_id Training provider record id
     * @param string $options              options array
     *
     * @return array
     */
    public function getList($training_provider_id = null, $options = null)
    {
        $account_ids = $this->find(
            'list',
            array(
                'fields' => array('id', 'account_id'),
                'conditions' => array(
                    'InstructorAssignment.training_provider_id' => $training_provider_id
                ),
            )
        );

        return $this->Account->getList(array('Account.id' => $account_ids));
    }
}