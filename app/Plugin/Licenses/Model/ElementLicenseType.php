<?php
/**
 * ElementLicenseType model
 *
 * Extends the AppModel. Responsible for managing elmenet
 * and license type associations.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class ElementLicenseType extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ElementLicenseType';

    var $displayField = 'label';

    var $belongsTo = array(
        'LicenseType' => array(
            'className' => 'Licenses.LicenseType',
            'foreignKey' => 'license_type_id',
        ),
        'Element' => array(
            'className' => 'Licenses.Element',
            'foreignKey' => 'element_id',
        )
    );

    /**
     * getElementsByLicenseTypeID method
     *
     * @param int $id license type ID expected
     *
     * @return array
     */
    public function getElementsByLicenseTypeID($id = null)
    {
        // default
        $elements = array();

        // query for the elements
        return $this->find(
            'all',
            array(
                'contain' => array('Element'),
                'conditions' => array('ElementLicenseType.license_type_id' => $id),
                'contain' => array('Element'),
                'order' => array('ElementLicenseType.order ASC')
            )
        );
    }
}