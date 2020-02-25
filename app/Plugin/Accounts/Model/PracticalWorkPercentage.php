<?php
/**
 * PracticalWorkPercentage model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class PracticalWorkPercentage extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PracticalWorkPercentage';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'employer';

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
        'PracticalWorkPercentageType' => array(
            'className'  => 'Accounts.PracticalWorkPercentageType',
            'foreignKey' => 'practical_work_percentage_type_id',
        ),
    );

    /**
     * hasOne associations
     *
     * @var array
     * @access public
     */
    public $hasOne = array();

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
                    'PracticalWorkPercentage.id' => $id,
                    'PracticalWorkPercentage.account_id' => CakeSession::read('Auth.User.id')
                )
            )
        );
    }

    /**
     * getPercentageById method
     *
     * @param int $id expecting practical work percentage id
     *
     * @return array
     */
    public function getPercentageById($id=null)
    {
        return $this->findById($id);
    }

    /**
     * Gets a label for our element.
     *
     * @return string Label
     * @access public
     */
    public function getElementLabel()
    {
        $conditions = array(
            'element_plugin' => 'accounts',
            'element'        => Inflector::pluralize(Inflector::underscore($this->name)),
            'foreign_plugin' => 'Account',
            'foreign_obj'    => 'Account',
            'data_keys'      => sprintf('Account.%s', Inflector::singularize($this->name)),
        );
        $element = ClassRegistry::init('Licenses.Element')->find('first', compact('conditions'));
        return $element['Element']['label'];
    }

    /**
     * Retrieves alias for our element.
     *
     * @return string Label
     * @acess public
     */
    public function getAlias()
    {
        return $this->getElementLabel();
    }

    /**
     * Adds a practical work percentage.
     *
     * @param array $data Data
     *
     * @return boolean True or false
     * @access public
     *
     * @throws Exception If 'other' type is selected but no description is provided.
     */
    public function addPercentage($data=array())
    {
        // don't allow saving when selected type is 'other' and the description is empty
        $other_type_id = $this->PracticalWorkPercentageType->getTypeIdFromLabel('other');

        if ($data[$this->name]['practical_work_percentage_type_id'] == $other_type_id)
        {
            if (strlen($data[$this->name]['descr']) == 0)
            {
                throw new Exception(__("Please sepecify a description when 'Other' is selected."));
            }
        }

        return $this->save($data);
    }

    /**
     * updatePercentage method
     *
     * @param array $data expecting cake data array
     *
     * @return bool|array returns false or updated data
     * @access public
     */
    public function updatePercentage($data=array())
    {
        // attempt to update the data
        if ($this->edit($data))
        {
            return $this->getPercentageById($data['PracticalWorkPercentage']['id']);
        }

        // fail
        throw new Exception(__('Practical work percentage could not be updated.'));
    }
}