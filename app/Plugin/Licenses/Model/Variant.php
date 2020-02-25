<?php
/**
 * Variant model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class Variant extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Variant';

    /**
     * display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array();

    /**
     * hasMany associations
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'LicenseType' => array(
            'className' => 'Licenses.LicenseType',
            'joinTable' => 'license_type_variants',
            'foreignKey' => 'variant_id',
        ),
        'License' => array(
            'className' => 'Licenses.License',
            'joinTable' => 'license_variants',
            'foreignKey' => 'variant_id',
        )
    );

    /**
     * getVariantByAbbr method
     *
     * Finds variant data using abbr value.
     *
     * @param str $abbr expecting variant abbr
     *
     * @return array return variant record
     * @access public
     */
    public function getVariantByAbbr($abbr = null)
    {
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'Variant.abbr' => $abbr
                )
            )
        );
    }
}