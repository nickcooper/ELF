<?php
/**
 * Abatement model
 *
 * @package Abatements.Model
 * @author  Iowa Interactive, LLC.
 */
class AbatementStatus extends AbatementsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'AbatementStatus';

    /**
     * Display field
     *
     * @var string
     * @access  public
     */
    public $displayField = 'label';

    /**
     * Retrieves status ID from label.
     *
     * @param string $label Label
     *
     * @return string Status ID
     * @access public
     */
    public function getStatusId($label)
    {
        $conditions = array('AbatementStatus.label' => $label);
        if (($status = $this->find('first', compact('conditions'))) !== false)
        {
            return $status['AbatementStatus']['id'];
        }

        return false;
    }
}