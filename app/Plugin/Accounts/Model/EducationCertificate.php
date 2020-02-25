<?php
/**
 * EducationCertificate model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class EducationCertificate extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'EducationCertificate';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
    );

    /**
     * hasOne associations
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'ProgramCertificate' => array(
            'className' => 'Accounts.ProgramCertificate',
            'foreignKey' => 'id',
        ),
    );

    var $hasMany = array(
    );

    /**
     * virtualFields
     *
     * @var array
     * @access public
     */
    var $virtualFields = array(
        'certificate' => '
            CASE WHEN (SELECT pc.certificate FROM program_certificates
            AS pc
            WHERE pc.id = EducationCertificate.program_certificate_id) = "Other"
            THEN EducationCertificate.other
            ELSE (SELECT pc.certificate FROM program_certificates
            AS pc
            WHERE pc.id = EducationCertificate.program_certificate_id)
            END'
    );
}