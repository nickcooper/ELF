<?php
/**
 * ApplicationType model
 *
 * Extends the AppModel.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class ApplicationType extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ApplicationType';

    var $displayField = 'label';

    var $order = array('label' => 'ASC');

    var $hasMany = array(
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_type_id',
        ),
        'AppLicCreditHour' => array(
            'className' => 'Licenses.AppLicCreditHour',
            'foreignKey' => 'application_type_id'
        ),
    );

    /**
     * getApplicationTypeList method
     *
     * @return array returns a list of application types
     */
    public function getApplicationTypeList()
    {
        return $this->find('list');
    }
}