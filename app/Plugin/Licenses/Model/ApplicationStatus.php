<?php
/**
 * ApplicationStatus model
 *
 * Extends the AppModel.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class ApplicationStatus extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ApplicationStatus';

    var $displayField = 'label';

    var $order = array('label' => 'ASC');

    var $hasMany = array(
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_status_id',
        ),
    );

    /**
     * getApplicationStatusList method
     *
     * @return array returns a list of application types
     */
    public function getApplicationStatusList ()
    {
        return $this->find('list');
    }
}