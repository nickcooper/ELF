<?php
/**
 * Group model
 *
 * Extends the AppModel. Responsible for managing group data.
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class Group extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Group';

    /**
     * display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Acl' => array('type' => 'requester'),
    );

    /**
     * belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Program' => array(
            'className' => 'Accounts.Program',
            'foreignKey' => 'program_id',
        ),
        'GroupProgram' => array(
            'className' => 'Accounts.GroupProgram',
            'foreignKey' => 'group_program_id',
        ),
    );

    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Account' => array(
            'className'  => 'Accounts.Accounts',
            'foreignKey' => 'group_id',
        ),
    );

    /**
     * hasAndBelongsToMany
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array(
        'PaymentType' => array(
            'className'  => 'Payments.PaymentType',
        ),
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'enabled' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
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
        // Update the ARO for the ACL to have an alias because cake isn't smart enough to do that on it's own
        $this->updateAroAlias();
    }

    /**
     * parentNode method
     *
     * This method is required by ACL
     *
     * @return null
     * @access public
     */
    public function parentNode()
    {
        return null;
    }

    /**
     * getGroupById method
     *
     * @param int $id expecting group ID
     *
     * @return array
     * @access public
     */
    public function getGroupById($id)
    {
        return $this->findById($id);
    }


    /**
     * makeGroups method
     *
     * Takes an array of GroupProgram IDs and creates group data for insert.
     *
     * @param array $group_program_ids expecting array of program group IDs
     * @param array $program           expecting array of program name and ID
     *
     * @return array
     * @access public
     */
    public function makeGroups($group_program_ids = array(), $program = array('id' => '', 'abbr' => ''))
    {
        $retVal = array();

        if (is_array($group_program_ids) && count($group_program_ids))
        {
            foreach ($group_program_ids as $id)
            {
                // get the program group
                $group_program = $this->GroupProgram->getGroupProgramById($id);

                // does the group already exist?
                if ($group = $this->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Group.program_id' => $program['id'],
                            'Group.group_program_id' => $group_program['GroupProgram']['id']
                        )
                    )
                ))
                {
                    $retVal['Group'][] = $group['Group'];
                    continue; // skip to the next record
                }

                // else create a new group
                $retVal['Group'][] = array(
                    'label' => sprintf('%s (%s)', $group_program['GroupProgram']['label'], $program['abbr']),
                    'descr' => $group_program['GroupProgram']['descr'],
                    'home' => $group_program['GroupProgram']['home'],
                    'program_id' => $program['id'],
                    'group_program_id' => $group_program['GroupProgram']['id'],
                    'admin' => $group_program['GroupProgram']['admin'],
                );
            }
        }

        return $retVal;
    }

    /**
     * deleteInverse method
     *
     * Used to delete groups that have been unselected from group checklists.
     *
     * Will delete groups from the database that are NOT in the $data['Group'] array.
     * If $data['Group'] is emtpy or missing then all groups will be deleted for the
     * given program_id. If program_id is false then false is returned.
     *
     * @param array $data       expecting array of group data
     * @param int   $program_id expecting program ID
     *
     * @return boolean True or false
     * @access public
     */
    public function deleteInverse($data = array(), $program_id = false)
    {
        // check for the required program ID
        if (!preg_match('/^[1-9]{1}[0-9]*$/', $program_id))
        {
            return false;
        }

        // get a list of ids to exclude from delete
        $ids = array();
        if (isset($data['Group']) && is_array($data['Group']))
        {
            foreach ($data['Group'] as $group)
            {
                if (isset($group['id']))
                {
                    $ids[] = $group['id'];
                }
            }
        }

        // assign the query conditions
        $conditions = array('Group.program_id' => $program_id);
        if (count($ids) > 0)
        {
            $conditions['AND'] = array(
                'NOT' => array('Group.id' => $ids),
            );
        }

        if ($this->deleteAll(array($conditions), false))
        {
            // pass
            return true;
        }

        // fail
        return false;
    }

    /**
     * getPaymentTypes method
     *
     * Gets a list of payment types based on the Auth User group
     *
     * @return array list of payment types
     * @access public
     */
    public function getPaymentTypes()
    {

        $group_id = CakeSession::read("Auth.User.group_id");

        $list = $this->find(
            'all',
            array(
                'contain' => array(
                    'PaymentType' => array(
                        'fields' => array(
                            'PaymentType.id',
                            'PaymentType.label',
                        ),
                    )
                ),
                'conditions' => array(
                    'Group.id' => $group_id
                )
            )
        );

        return Hash::combine($list, '{n}.PaymentType.{n}.id', '{n}.PaymentType.{n}.label');
    }
}