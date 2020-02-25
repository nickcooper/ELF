<?php
/**
 * AddressBookAppModel
 *
 * @package AddressBook.Model
 * @author  Iowa Interactive, LLC.
 */
class AddressBookAppModel extends AppModel
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