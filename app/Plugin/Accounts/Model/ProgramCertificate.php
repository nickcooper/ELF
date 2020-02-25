<?php
/**
 * ProgramCertificate model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class ProgramCertificate extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ProgramCertificate';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'certificate';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Program' => array(
            'className' => 'Accounts.Program',
            'foreignKey' => 'program_id',
        ),
    );

    /**
     * Default order
     *
     * @var string
     * @access public
     */
    public $order = array('ProgramCertificate.order' => 'ASC');
}