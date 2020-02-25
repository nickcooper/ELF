<?php
/**
 * Question model
 *
 * Extends the AppModel. Responsible for managing non-screening question data.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class Question extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Question';

    var $belongsTo = array(
        'LicenseType' => array(
            'className' => 'Licenses.LicenseType',
            'foreignKey' => 'license_type_id',
        ),
    );

    var $hasMany = array(
        'QuestionAnswer' => array(
            'className' => 'Licenses.QuestionAnswer',
            'foreignKey' => 'question_id',
        ),
    );

    var $validate = array(
        'question' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );
}