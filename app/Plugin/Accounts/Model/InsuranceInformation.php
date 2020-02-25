<?php
/**
 * InsuranceInformation model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class InsuranceInformation extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'InsuranceInformation';

    /**
     * Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Uploads.Upload' => array(
            'Upload' => array(
                'save_location' => 'files',
                'allowed_types' => array('application/pdf', 'image/png', 'image/jpg', 'image/jpeg'),
            ),
        ),
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
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'label' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a description for this insurance policy.',
                ),
        ),
        'expire_date' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter an insurance expiration date.',
            ),
        ),
        'start_date' => array(
            'startDateBeforeEndDate' => array(
                'rule' => array('startDateBeforeEndDate'),
                'message' => 'Course start date must be before course end date.'
            ),
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
     * hasAndBelongsToMany associations
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array();

    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Upload' => array(
            'className'  => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Upload.foreign_obj' => 'InsuranceInformation',
                'Upload.identifier'  => 'Upload',
            ),
        ),
    );

    /**
     * getInsuranceInformationById method
     *
     * @param int $id expecting work experience id
     *
     * @return array
     */
    public function getInsuranceInformationById($id = null)
    {
        return $this->findById($id);
    }

    /**
     * updateInsuranceInformation method
     *
     * @param array $data expecting cake data array
     *
     * @return bool|array returns false or updated data
     */
    public function updateInsuranceInformation($data = array())
    {
        // attempt to update the data
        if ($this->edit($data))
        {
            return $this->getInsuranceInformationById($data['InsuranceInformation']['foreign_key']);
        }

        // fail
        throw new Exception('Insurance information could not be updated.');
    }

    /**
     * startDateBeforeEndDate
     *
     * validation method to ensure the course section
     * start date is equal to or before the course section
     * end date.
     *
     * @param str $start_date the course section start date
     *
     * @return bool true if start date is <= end date, false otherwise.
     * @access public
     */
    public function startDateBeforeEndDate($start_date = null)
    {
        $retVal = false;

        // dates
        $start_date = $start_date['start_date'];
        $end_date = $this->data['CourseSection']['end_date'];

        // can we convert the dates to timestamps?
        if ($start = strtotime($start_date) && $end = strtotime($end_date))
        {
            // compare the dates
            $retVal = ($start <= $end ? true : false);
        }

        return $retVal;
    }
}