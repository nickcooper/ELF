<?php
/**
 * LicenseVariant model
 *
 * @package License.Model
 * @author  Iowa Interactive, LLC.
 */
class LicenseVariant extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'LicenseVariant';

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
                'allowed_types' => array('application/pdf'),
                'assoc_type'    => 'hasOne',
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
            'className'  => 'Licenses.License',
            'foreignKey' => 'license_id',
        ),
        'Variant' => array(
            'className'  => 'Licenses.Variant',
            'foreignKey' => 'variant_id',
        )
    );

    /**
     * hasOne
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'Upload' => array(
            'className'  => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Upload.foreign_obj' => 'LicenseVariant',
                'Upload.identifier'  => 'Upload',
            )
        ),
    );
}