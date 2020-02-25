<?php
/**
 * AppLicCreditHour model
 *
 * Extends the AppModel.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class AppLicCreditHour extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'AppLicCreditHour';

    /**
     * belongsTo relationships
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'ApplicationType' => array(
            'className' => 'Licenses.ApplicationType',
            'foreignKey' => 'application_type_id',
        ),
        'LicenseType' => array(
            'className' => 'Licenses.LicenseType',
            'foreignKey' => 'license_type_id',
        ),
    );

    /**
     * getCreditHours method
     *
     * @param str $application_type_id application type id
     * @param str $license_type_id     license type id
     *
     * @return array
     */

    public function getCreditHours($application_type_id = null, $license_type_id = null)
    {
        $credit_hours = $this->find(
            'first',
            array(
                'fields' => array(
                    'code_hours',
                    'total_hours'
                ),
                'conditions' => array(
                    'AppLicCreditHour.application_type_id' => $application_type_id,
                    'AppLicCreditHour.license_type_id' => $license_type_id,
                )
            )
        );

        return array($credit_hours['AppLicCreditHour']['code_hours'], $credit_hours['AppLicCreditHour']['total_hours']);
    }
}