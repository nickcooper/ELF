<?php
/**
 * GroupProgram model
 *
 * Extends the AppModel. Responsible for managing group program data.
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class GroupProgram extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'GroupProgram';

    /**
     * display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array();

    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    public $hasMany = array();

    /**
     * hasAndBelongsToMany
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array();

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'program_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
            )
        ),
        'enabled' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
        ),
    );

    /**
     * addGroupProgram method
     *
     * @param array $data expecting array of program group data
     *
     * @return mixed returns false or new group program data
     * @access public
     */
    public function addGroupProgram($data = array())
    {
        // attempt to insert the data
        if ($this->add($data))
        {
            return $this->getGroupProgramById($this->getLastInsertID());
        }

        // fail
        throw new Exception(
            sprintf(__('Program Group (%s) could not be created.'), $data['GroupProgram']['label'])
        );
    }

    /**
     * getGroupProgamList method
     *
     * @return array
     * @access public
     */
    public function getGroupProgramList()
    {
        // conditions - exclude super admin and default groups
        $conditions = array(
            'NOT' => array(
                'GroupProgram.label' => array(
                    Configure::read('app.groups.super_admin'),
                    Configure::read('app.groups.default'),
                ),
            ),
        );

        // return results
        return $this->find('list', array('conditions' => $conditions));
    }

    /**
     * getGroupProgramById method
     *
     * @param int $id expecting program group ID
     *
     * @return array
     * @acces public
     */
    public function getGroupProgramById($id = null)
    {
        // return results
        return $this->findById($id);
    }

    /**
     * updateGroupProgram method
     *
     * @param array $data expecting array of program group data
     *
     * @return mixed returns fail or group program data
     * @access public
     */
    public function updateGroupProgram($data = array())
    {
        // attempt to update the data
        if ($this->edit($data))
        {
            return $this->getGroupProgramById($data['GroupProgram']['id']);
        }

        // fail
        throw new Exception(
            sprintf(__('Program Group (%s) could not be updated.'), $data['GroupProgram']['label'])
        );
    }
}