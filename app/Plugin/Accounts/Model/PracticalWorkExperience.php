<?php
/**
 * PracticalWorkExperience model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class PracticalWorkExperience extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PracticalWorkExperience';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className'  => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
        'PracticalWorkExperienceType' => array(
            'className'  => 'Accounts.PracticalWorkExperienceType',
            'foreignKey' => 'practical_work_experience_type_id',
        ),
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'practical_work_experience_type_id' => array(
            'rule'    => 'numeric',
            'message' => 'Please select a practical work experience type.',
        ),
    );

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id Account id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        // get the work experience percentage record
        return $record = $this->find(
            'first',
            array(
                'conditions' => array(
                    'PracticalWorkExperience.id' => $id,
                    'PracticalWorkExperience.account_id' => CakeSession::read('Auth.User.id')
                )
            )
        );
    }

    /**
     * Adds a practical work experience.
     *
     * @param array $data Data
     *
     * @return boolean True or false
     * @access public
     *
     * @throws Exception If 'other' type is selected but no description is provided.
     */
    public function savePracticalWorkExperience($data=array())
    {
        // don't allow saving when selected type is 'other' and the description is empty
        $other_type_id = $this->PracticalWorkExperienceType->getTypeIdFromLabel('Other');

        if ($data[$this->name]['practical_work_experience_type_id'] == $other_type_id)
        {
            if (strlen($data[$this->name]['description']) == 0)
            {
                throw new Exception(__("Please sepecify a description when 'Other' is selected."));
            }
        }

        return $this->add($data);
    }
}