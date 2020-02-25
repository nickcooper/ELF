<?php
/**
 * LicenseStatus model
 *
 * Extends the AppModel.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class LicenseStatus extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'LicenseStatus';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'status';

    /**
     * Sort order
     *
     * @var array
     * @access public
     */
    public $order = array('status' => 'ASC');

    /**
     * belongsTo relationships
     *
     * @var array
     * @access public
     */
    public $belongsTo = array();

    /**
     * HABTM relationships
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array(
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'license_status_id',
        ),
    );

    /**
     * getLicenseStatusList method
     *
     * @return array returns a list of license statuses
     * @access public
     */
    public function getLicenseStatusList ()
    {
        return $this->find('list');
    }

    /**
     * Retrieves status ID from label
     *
     * @param string $label Status
     *
     * @return int Status ID
     * @access public
     */
    public function getStatusId($label)
    {
        $conditions = array('LicenseStatus.status' => $label);
        if (($status = $this->find('first', compact('conditions'))) !== false)
        {
            return $status['LicenseStatus']['id'];
        }

        return false;
    }
}