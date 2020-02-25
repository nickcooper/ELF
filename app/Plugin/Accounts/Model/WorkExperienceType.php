<?php
/**
 * WorkExperienceType model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class WorkExperienceType extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'WorkExperienceType';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Default order
     *
     * @var string
     * @access public
     */
    public $order = array('WorkExperienceType.order' => 'ASC');
}