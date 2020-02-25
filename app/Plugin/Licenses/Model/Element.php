<?php
/**
 * Element model
 *
 * Extends the AppModel. Responsible for managing view elements
 * used by license applications.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class Element extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Element';

    var $displayField = 'label';

    var $hasMany = array(
        'ElementLicenseType' => array(
            'className' => 'Licenses.ElementLicenseType',
            'foreignKey' => 'ElementLicenseType.element_id',
        )
    );
}