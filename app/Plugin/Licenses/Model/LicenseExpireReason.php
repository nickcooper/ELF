<?php
/**
 * License Expire Reason model
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class LicenseExpireReason extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'LicenseExpireReason';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'descr';

    /**
     * belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'license_id',
        )
    );

    /**
     * setReason
     *
     * set the reason for expiration
     *
     * @param str $license_id  License ID
     * @param str $expire_date Expire date
     * @param str $description Description
     *
     * @return bool true
     * @access public
     */
    public function setReason($license_id, $expire_date, $description)
    {
        $data = array();

        if ($exists = $this->findByLicenseId($license_id))
        {
            $data['LicenseExpireReason']['id'] = $exists['LicenseExpireReason']['id'];
        }

        $data['LicenseExpireReason']['license_id'] = $license_id;
        $data['LicenseExpireReason']['expire_date'] = $expire_date;
        $data['LicenseExpireReason']['descr'] = $description;

        if (!$this->save($data))
        {
            throw new Exception('Unable to save license expiration reason.');
        }

        return true;
    }
}