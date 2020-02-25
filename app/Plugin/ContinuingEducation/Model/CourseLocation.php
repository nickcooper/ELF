<?php
/**
 * CourseLocation Model
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class CourseLocation extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseLocation';

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
     * hasOne Associations
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Address.foreign_obj' => 'CourseLocation'
            ),
        ),
    );

    /**
     * belongsTo Associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'TrainingProvider' => array(
            'className' => 'ContinuingEducation.TrainingProvider',
            'foreignKey' => 'training_provider_id'
        )
    );

    /**
     * Model Validation Rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'contact_person' => array(
            'rule' => 'notEmpty',
            'message' => 'Please enter a contact person'
        ),
        'contact_phone' => array(
            'rule' => 'notEmpty',
            'message' => 'Please enter a contact phone number'
        ),
    );

    /**
     * details function
     *
     * @param int    $id       id of the location you want details of
     * @param string $contains contains array
     *
     * @return array
     */
    public function details($id, $contains = false)
    {
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'CourseLocation.id' => $id
                ),
                'contain' => array(
                    'TrainingProvider','Address'
                )
            )
        );
    }

    /**
     * Overrides getList so we can pass along address information
     *
     * For all course locations pass the string all for the training provider id
     *
     * The return for this function is actually a call to Address::getList() and will be indexed by address ids
     * so deletion of this record from a training provider license will be able to occur without child dependency issues
     *
     * @param array $conditions query conditions
     * @param array $options    options to be passed to Model::find
     *
     * @return array
     */
    public function getList($conditions = null, $options = null)
    {
        if (!is_array($conditions))
        {
            $conditions = array();
        }

        // unless otherwise specified we only want enabled locations
        if (empty($conditions['CourseLocation.enabled']))
        {
            $conditions['CourseLocation.enabled'] = true;
        }

        $foreign_keys = $this->find(
            'list',
            array(
                'conditions' => $conditions,
                'fields' => array('id', 'id'),
            )
        );

        $conditions = array();

        $conditions['Address.foreign_obj'] = $this->name;
        $conditions['Address.foreign_key'] = $foreign_keys;

        $result = $this->Address->getList($conditions, $options);

        return $result;
    }

    /**
     * Returns pending instructor count
     *
     * @return array
     */
    public function pendingInstructorCount()
    {
        $instructor = ClassRegistry::init('Instructor');
        return $instructor->pendingCount();
    }

    /**
     * Gets a list of CourseLocation ids for a given training provider
     *
     * @param int $training_provider_id id of training provider you want locations for
     *
     * @return array
     */
    private function _getLocationsForTrainingProvider($training_provider_id)
    {
        return $this->find(
            'list', array(
                'fields' => array("{$this->name}.id", "{$this->name}.id"),
                'conditions' => array("{$this->name}.training_provider_id" => $training_provider_id)
            )
        );
    }
}