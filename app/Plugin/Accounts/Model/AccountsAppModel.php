<?php
/**
 * Accounts App model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class AccountsAppModel extends AppModel
{
    /**
     * Model behaviors.
     *
     * @var Array
     * @access public
     */
    public $actsAs = array(
        'Logging.Auditable',
    );
}