<?php
/**
 * LicenseType model
 *
 * Extends the AppModel. Responsible for managing license type data.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class LicenseType extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'LicenseType';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Order
     *
     * @var array
     * @access public
     */
    public $order = array('label' => 'ASC');

    public $belongsTo = array(
        'Program' => array(
            'className' => 'Accounts.Program',
            'foreignKey' => 'program_id'
        )
    );

    public $hasMany = array(
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'license_type_id',
        ),
        'LicenseTypeConversion' => array(
            'className' => 'Licenses.LicenseTypeConversion',
            'foreignKey' => 'license_type_id',
        ),
        'ElementLicenseType' => array(
            'className' => 'Licenses.ElementLicenseType',
            'foreignKey' => 'license_type_id',
        ),
        'AppLicCreditHour' => array(
            'className' => 'Licenses.AppLicCreditHour',
            'foreignKey' => 'license_type_id'
        ),
        'Question' => array(
            'className' => 'Licenses.Question',
            'foreignKey' => 'license_type_id',
        ),
        'ScreeningQuestion' => array(
            'className' => 'Licenses.ScreeningQuestion',
            'foreignKey' => 'license_type_id',
        ),
    );


    public $hasAndBelongsToMany = array(
        'CourseCatalog' => array(
            'className' => 'ContinuingEducation.CourseCatalog',
            'joinTable' => 'course_catalogs_license_types',
            'foreignKey' => 'license_type_id',
            'associationForeignKey' => 'course_catalog_id',
        ),
        'Variant' => array(
            'className' => 'Licenses.Variant',
            'joinTable' => 'license_type_variants',
            'foreignKey' => 'license_type_id',
        )
    );


    public $validate = array(
        'type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );

    /**
     * getLicenseTypeList method
     *
     * @return array returns a list of license types
     */
    public function getLicenseTypeList ()
    {
        return $this->find('list', array('conditions' => array('LicenseType.avail_for_initial' => 1)));
    }

    /**
     * getLicenseTypeById method
     *
     * @param int $id license type ID expected
     *
     * @return array
     */

    public function getLicenseTypeById ($id = null)
    {
        // contain
        $contain = array();

        // return results
        return $this->find(
            'first',
            array(
                'conditions' => array('LicenseType.id' => $id),
                'contain' => $contain
            )
        );
    }

    /**
     * getLicenseTypeBySlug method
     *
     * @param str $slug license type slug expected
     *
     * @return array
     */

    public function getLicenseTypeBySlug ($slug = null)
    {
        // contain
        $contain = array();

        // return results
        return $this->find(
            'first',
            array(
                'conditions' => array('LicenseType.slug' => $slug),
                'contain' => $contain
            )
        );
    }

    /**
     * getLicenseTypeByAbbr method
     *
     * @param str $abbr license type abbr expected
     *
     * @return array
     */

    public function getLicenseTypeByAbbr ($abbr = null)
    {
        // contain
        $contain = array();

        // return results
        return $this->find(
            'first',
            array(
                'conditions' => array('LicenseType.abbr' => $abbr),
                'contain' => $contain
            )
        );
    }

    /**
     * getConversionTypes method
     *
     * @param int $id license type id
     *
     * @return array
     */

    public function getConversionTypes ($id = null)
    {
        // get the license type record and allowed conversion types
        $contain = array('LicenseTypeConversion');

        $license_type = $this->find(
            'first',
            array(
                'conditions' => array('LicenseType.id' => $id),
                'contain' => $contain
            )
        );

        // get the conversion ids
        $convert_ids = array();

        foreach ($license_type['LicenseTypeConversion'] as $conversion)
        {
            $convert_ids[] = $conversion['convert_type_id'];
        }

        // return a list of conversion types with id and label
        return $this->find(
            'list',
            array(
                'conditions' => array('LicenseType.id' => $convert_ids)
            )
        );
    }

    /**
     * getAppElements method
     *
     * returns an array of view elements assigned to each license application.
     *
     * @param int|str $input expecting license type id or license type slug.
     *
     * @return array
     */
    public function getAppElements ($input = null)
    {
        $app_elements = array();

        // determine if the input is a license record ID or a LicenseType slug
        switch (true)
        {
        // id
        case preg_match('/^[1-9]{1}[0-9]*$/', $input):

            $app_elements = $this->ElementLicenseType->getElementsByLicenseTypeID($input);
            break;

        // slug
        default:

            if (!$license_type = $this->getLicenseTypeBySlug($input))
            {
                $this->Session->setFlash(__('Invalid license type.', true));
                $this->redirect(array('action' => 'index'));
            }

            $app_elements = $this->ElementLicenseType->getElementsByLicenseTypeID($license_type['LicenseType']['id']);
            break;
        }

        return $app_elements;
    }
}