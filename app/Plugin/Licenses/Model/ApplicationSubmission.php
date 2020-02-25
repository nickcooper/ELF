<?php
/**
 * ApplicationSubmission model
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class ApplicationSubmission extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ApplicationSubmission';

    var $belongsTo = array(
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_id',
        )
    );
}