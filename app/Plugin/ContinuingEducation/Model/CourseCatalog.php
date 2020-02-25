<?php
/**
 * CourseCatalog Model
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class CourseCatalog extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseCatalog';

    /**
     * Model Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Searchable.Searchable',
    );

    /**
     * Display Field
     *
     * @var array
     * @access public
     */
    public $displayField = 'label';

    /**
     * belongsTo Relationships
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Program' => array(
            'className'  => 'Accounts.Program',
            'foreignKey' => 'program_id',
        ),
    );

    /**
     * hasMany Relationships
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Course' => array(
            'className'  => 'ContinuingEducation.Course',
            'foreignKey' => 'course_catalog_id',
        ),
        'CourseCatalogLicenseType' => array(
            'className'  => 'ContinuingEducation.CourseCatalogsLicenseType',
            'foreignKey' => 'course_catalog_id',
        ),
    );

    /**
     * hasAndBelongsToMany relationships
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array(
        'LicenseType' => array(
            'className'             => 'Licenses.LicenseType',
            'foreignKey'            => 'course_catalog_id',
            'associationForeignKey' => 'license_type_id',
        ),
    );

    /**
     * Validation Rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'label' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter a course name',
            ),
        ),
        'abbr' => array(
            'required' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Please enter an abbreviation'
            ),
            'length' => array(
                'rule'    => array('between', 1, 6),
                'message' => 'Course abbreviations must be between 1 and 6 characters long',
            ),
            'unique' => array(
                'rule'    => 'isUnique',
                'message' => 'This abbreviation is already in use',
            ),
        ),
        'program_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please choose a program',
            ),
        ),
        'test_attempts' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the number of allowed course attempts',
            ),
        ),
        'code_hours' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the number of code hours for this course',
            ),
            'float' => array(
                'rule' => '/^[0-9]*(\.[0-9]{1,2})?$/',
                'message' => 'Please enter a number value.',
            ),
        ),
        'non_code_hours' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the number of non-code hours for this course',
            ),
            'float' => array(
                'rule' => '/^[0-9]*(\.[0-9]{1,2})?$/',
                'message' => 'Please enter a number value.',
            ),
        ),
    );

    /**
     * afterSave Callback
     *
     * @param boolean $created true if this is a new record
     *
     * @return boolean
     */
    public function afterSave($created)
    {
        // Add a default UNIQUE course catalog abbr if empty
        if (!preg_match('/[a-z0-9]/i', $this->data['CourseCatalog']['abbr']))
        {
            $this->read();
            $this->saveField('abbr', sprintf('CC%s', $this->getLastInsertID()));
        }
    }

    /**
     * Course Catalog Details
     *
     * @param int    $id       Course catalog record id
     * @param string $contains contains array
     *
     * @return array
     */
    public function details($id, $contains = false)
    {
        return $this->find(
            'first',
            array(
                'conditions' => array('CourseCatalog.id' => $id),
                'contain'    => array('Program.label'),
            )
        );
    }
}