<?php
/**
 * Instructor Model
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class Instructor extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Instructor';

    /**
     * display field
     *
     * @var string
     * @access public
     */
    public $displayField = false;

    /**
     * Model Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Uploads.Upload' => array(
            'Upload' => array(
                'save_location' => 'files',
                'allowed_types' => array('image/jpeg')
            ),
        ),
        'Searchable.Searchable',
    );

    /**
     * belongsTo Associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Program' => array(
            'className'  => 'Accounts.Program',
            'foreignKey' => 'program_id',
        ),
        'Account' => array(
            'className'  => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
    );

    /**
     * hasMany Relationships
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Upload' => array(
            'className'  => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Upload.foreign_plugin' => 'ContinuingEducation',
                'Upload.foreign_obj'    => 'Instructor',
                'Upload.identifier'     => 'Upload',
            ),
        ),
    );

    /**
     * Overriding add funciton so we can throw an exception if the instructor already exists
     *
     * @param array  $data    Data to be saved
     * @param string $options options array
     *
     * @return boolean success
     * @access public
     *
     * @throws exception if invalid program
     * @throws exception if invalid account
     */
    public function add($data = null, $options = array())
    {
        if (empty($data[$this->name]['program_id'])
            || ! $this->Program->exists($data[$this->name]['program_id'])
        )
        {
            throw new Exception(__('Invalid Program'));
        }

        if (empty($data[$this->name]['account_id'])
            || ! $this->Account->exists($data[$this->name]['account_id'])
        )
        {
            throw new Exception(__('Invalid Account'));
        }

        // is there a previous instructor record?
        $instructor = $this->find(
            'first',
            array(
                'conditions' => array(
                    "{$this->name}.account_id" => $data[$this->name]['account_id'],
                    "{$this->name}.program_id" => $data[$this->name]['program_id'],
                )
            )
        );

        // return true if previous or newly added instructor, else false
        return ($instructor || parent::add($data) ? true : false);
    }

    /**
     * Overriding the AppModel defined getList function so we can return a list of accounts
     *
     * @param int    $program_id id for a program to limit your result to
     * @param string $options    options array
     *
     * @return array
     * @access public
     */
    public function getList($program_id = null, $options = null)
    {
        $ids = $this->_getInstructorUserIds($program_id);
        return $this->Account->getList(array('Account.id' => $ids));
    }

    /**
     * Overriding details function to include account and upload
     *
     * @param int   $id       instructor id
     * @param mixed $contains data to contain
     *
     * @return array
     * @access public
     */
    public function details($id, $contains = null)
    {
        if (empty($contains))
        {
            $contains = array(
                'Account' => array(
                    'License' => array(
                        'LicenseStatus',
                        'LicenseType',
                    ),
                    'EducationDegree' => array(
                        'Upload',
                    ),
                    'EducationCertificate',
                    'WorkExperience',
                ),
                'Upload',
            );
        }

        return parent::details($id, $contains);
    }

    /**
     * Get list of ids for training instructors
     *
     * @param mixed   $conditions           Query conditions
     * @param boolean $include_not_approved Set to true to include instructors who have not been approved
     * @param boolean $include_disabled     Set to true to include instructors who have been disabled
     *
     * @return array
     * @access public
     */
    private function _getInstructorUserIds($conditions = null, $include_not_approved = false, $include_disabled = false)
    {
        if (! $include_not_approved)
        {
            $conditions['Instructor.approved'] = true;
        }

        if (! $include_disabled)
        {
            $conditions['Instructor.enabled'] = true;
        }

        return $this->find(
            'list',
            array(
                'conditions' => $conditions,
                'fields' => array(
                    'Instructor.id', 'Instructor.account_id',
                ),
            )
        );
    }

    /**
     * Get a listing of programs
     *
     * @return array returns a list of license types
     * @access public
     */
    public function getProgramList()
    {
        $this->Program = ClassRegistry::init('Accounts.Program');
        return $this->Program->getList(
            array(),
            array('empty' => '')
        );
    }
}