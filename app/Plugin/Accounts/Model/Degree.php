<?php
/**
 * Degree model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class Degree extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Degree';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'degree';

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array(
        'EducationDegree' => array(
            'className' => 'Accounts.EducationDegree',
            'foreignKey' => 'degree_id',
        )
    );
}