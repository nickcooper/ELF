<?php
/**
 * WorkExperience model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class WorkExperience extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'WorkExperience';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'employer';

    /**
     * virtualFields
     *
     * @var array
     * @access public
     */
    var $virtualFields = array(
        'current' => 'CASE WHEN WorkExperience.end_date IS NULL THEN 1 ELSE 0 END'
    );

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        )
    );

    /**
     * hasOne associations
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Address.foreign_obj' => 'WorkExperience')
        )
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array(
        'WorkExperienceType' =>
            array(
                'className'             => 'Accounts.WorkExperienceType',
                'joinTable'             => 'work_experiences_work_experience_types',
                'foreignKey'            => 'work_experience_id',
                'associationForeignKey' => 'work_experience_type_id',
            )
    );

    /**
     * Validation Rules
     *
     * @var Array
     * @access public
     */
    public $validate = array(
        'employer'      => array('notempty' => array('rule' => array('notempty'))),
        'start_date'    => array('notempty' => array('rule' => array('notempty'))),
    );

    /**
     * getExperienceById method
     *
     * @param int $id expecting work experience id
     *
     * @return array
     */
    public function getExperienceById($id = null)
    {
        $data = $this->findById($id);
        if ($data['WorkExperience']['end_date'] == null)
        {
            $data['WorkExperience']['current'] = 1;
        }

        return $data;
    }

    /**
     * updateExperience method
     *
     * @param array $data expecting cake data array
     *
     * @return bool|array returns false or updated data
     */
    public function updateExperience($data = array())
    {
        if ($data['WorkExperience']['current'])
        {
            $data['WorkExperience']['end_date'] = null;
        }
        // attempt to update the data
        if ($this->edit($data))
        {
            return $this->getExperienceById($data['WorkExperience']['id']);
        }

        // fail
        throw new Exception('Work Experience could not be updated.');
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id WorkExperience id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        return $this->hasAny(
            array(
                'WorkExperience.id' => $id,
                'WorkExperience.account_id' => CakeSession::read("Auth.User.id")
            )
        );
    }
}