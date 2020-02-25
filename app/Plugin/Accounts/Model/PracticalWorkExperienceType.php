<?php
/**
 * PracticalWorkExperienceType model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class PracticalWorkExperienceType extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PracticalWorkExperienceType';

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
    public $order = array('PracticalWorkExperienceType.order' => 'ASC');

    /**
     * Retrieves a practical work percentage type from a specified label.
     *
     * @param string $label Label
     *
     * @return array|boolean Practical work percentage type, or false if not found.
     * @access public
     */
    public function getTypeByLabel($label)
    {
        $conditions = array('label LIKE' => sprintf('%s%%', $label));
        return $this->find('first', compact('conditions'));
    }

    /**
     * Retrieves a practical work percentage type ID from a specified label.
     *
     * @param string $label Label
     *
     * @return int|boolean Practical work percentage type ID, or false if not found.
     * @access public
     */
    public function getTypeIdFromLabel($label)
    {
        if ($type = $this->getTypeByLabel($label))
        {
            return $type[$this->name]['id'];
        }

        return false;
    }
}