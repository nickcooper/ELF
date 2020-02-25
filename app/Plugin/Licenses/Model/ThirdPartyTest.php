<?php
/**
 * ThirdPartyTest model
 *
 * Extends the License AppModel. Responsible for managing third party test data.
 *
 * @package License.Model
 * @author  Iowa Interactive, LLC.
 */
class ThirdPartyTest extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ThirdPartyTest';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Searchable.Searchable',
        'Uploads.Upload' => array(
            'Upload' => array(
                'save_location' => 'files',
                'allowed_types' => array('application/pdf', 'image/jpeg', 'image/png'),
                'association' => 'hasMany',
            ),
        ),
    );

    /**
     * belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_id',
        ),
    );

    /**
     * hasMany
     *
     * @var array
     * @access public
     */
    var $hasMany = array(
        'Upload' => array(
            'className' => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Upload.foreign_obj' => 'ThirdPartyTest',
                'Upload.identifier' => 'Upload'
            )
        ),
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array();

}