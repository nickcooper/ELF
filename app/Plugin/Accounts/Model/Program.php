<?php
/**
 * Program model
 *
 * Extends the AppModel. Responsible for managing program data.
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class Program extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Program';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Group' => array(
            'className'  => 'Accounts.Group',
            'foreignKey' => 'group_program_id',
        ),
        'LicenseType' => array(
            'className'  => 'Licenses.LicenseType',
            'foreignKey' => 'program_id',
        ),
        'Instructor' => array(
            'className'  => 'ContinuingEducation.Instructor',
            'foreignKey' => 'program_id',
        )
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
        'slug' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );

    /**
     * beforeValidation method
     *
     * @param array $options options array
     *
     * @return bool
     * @access public
     */
    public function beforeValidate($options = array())
    {
        // create the url slug
        if (isset($this->data['Program']['label']))
        {
            $this->data['Program']['slug'] = GenLib::makeSlug($this->data['Program']['label']);
        }

        return true;
    }

    /**
     * beforeSave method
     *
     * @param array $options options array
     *
     * @return bool
     * @access public
     */
    public function beforeSave($options = array())
    {
        // delete unselected groups
        if (isset($this->data['Program']['id']) && !empty($this->data['Program']['id']))
        {
            if (! $this->Group->deleteInverse($this->data, $this->data['Program']['id']))
            {
                $this->validationErrors['GroupProgram']['id'] = __('Could not delete group association(s).');
                return false;
            }
        }

        return true;
    }

    /**
     * getProgramById method
     *
     * @param int $id expecting program ID
     *
     * @return array
     * @access public
     */
    public function getProgramById($id = null)
    {
        // return results
        return $this->find(
            'first',
            array(
                'contain' => array(
                    'Group'
                ),
                'conditions' => array(
                    'Program.id' => $id
                )
            )
        );
    }

    /**
     * getProgramByAbbr method
     *
     * @param str $abbr expecting program abbreviation
     *
     * @return array
     */
    public function getProgramByAbbr($abbr = null)
    {
        // return results
        return $this->find(
            'first',
            array(
                'contain' => array(
                    'Group'
                ),
                'conditions' => array(
                    'Program.abbr' => $abbr
                )
            )
        );
    }
}