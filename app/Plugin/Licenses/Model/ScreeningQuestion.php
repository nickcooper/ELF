<?php
/**
 * ScreeningQuestion model
 *
 * Extends the AppModel. Responsible for managing screening question data.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class ScreeningQuestion extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ScreeningQuestion';

    var $belongsTo = array(
        'LicenseType' => array(
            'className' => 'Licenses.LicenseType',
            'foreignKey' => 'license_type_id',
        ),
    );

    var $hasMany = array(
        'ScreeningAnswer' => array(
            'className' => 'Licenses.ScreeningAnswer',
            'foreignKey' => 'screening_question_id',
        ),
    );


    var $hasAndBelongsToMany = array(
    );


    var $validate = array(
        'question' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );
}