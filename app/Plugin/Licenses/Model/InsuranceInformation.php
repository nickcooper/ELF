<?php
/**
 * InsuranceInformation model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class InsuranceInformation extends LicensesAppModel
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
                'association'   => 'hasMany',
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
        'License' => array(
            'className' => 'Licenses.License',
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
        'expire_date' => array(
            'futureExpireDate' => array(
                'rule' => array('futureExpireDate'),
                'message' => 'The insurance policy expiration date must be in the future.'
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
     * futureExpireDate
     *
     * validation method to ensure the course section
     * start date is equal to or before the course section
     * end date.
     *
     * @param str $date the course section start date
     *
     * @return bool true if start date is <= end date, false otherwise.
     * @access public
     */
    public function futureExpireDate($date = null)
    {
        $retVal = false;

        $date = $this->data['InsuranceInformation']['expire_date'];

        // can we convert the dates to timestamps?
        if ($future_date = strtotime($date))
        {
            // compare the dates
            $retVal = ($future_date >= strtotime('today') ? true : false);
        }

        return $retVal;
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id InsuranceInformation id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        $insurance = $this->findById($id, array('foreign_plugin', 'foreign_obj', 'foreign_key'));

        $ForeignModel = ClassRegistry::init(sprintf('%s.%s', $insurance['InsuranceInformation']['foreign_plugin'], $insurance['InsuranceInformation']['foreign_obj']));

        return $ForeignModel->isOwnerOrManager($insurance['InsuranceInformation']['foreign_key']);
    }
}