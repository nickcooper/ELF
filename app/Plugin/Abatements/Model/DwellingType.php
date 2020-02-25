<?php
/**
 * Abatement model
 *
 * @package Abatements.Model
 * @author  Iowa Interactive, LLC.
 */
class DwellingType extends AbatementsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'DwellingType';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Returns whether or not a dwelling type is a rental.
     *
     * @param int $id Dwelling type ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isRental($id)
    {
        $dwellingType = $this->details($id);
        return preg_match('/rental/i', $dwellingType['DwellingType']['label']);
    }

    /**
     * Returns whether or not a dwelling type is owner-occupied.
     *
     * @param int $id Dwelling type ID
     *
     * @return boolean True or false
     * @access public
     */
    public function isOwnerOccupied($id)
    {
        $dwellingType = $this->details($id);
        return preg_match('/owner/i', $dwellingType['DwellingType']['label']);
    }

    /**
     * getDwellingTypeList method
     *
     * @return array returns a list of dwelling types
     */
    public function getDwellingTypeList ()
    {
        return $this->find('list');
    }
}