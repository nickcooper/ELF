<?php
/**
 * NavLink model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class NavLink extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'NavLink';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * Get link records for group
     *
     * @param int $group_id Group ID
     *
     * @return array
     * @access public
     */
    public function getLinksForGroup($group_id = null)
    {
        if (!$group_id)
        {
            throw new Exception('Group ID required.');
        }
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'NavLink.group_id' => $group_id,
                    'NavLink.enabled' => 1,
                    'OR' => array(
                        array('NavLink.plugin' => null),
                        array('NavLink.plugin' => CakePlugin::loaded())
                    )
                ),
                'order' => 'label ASC'
            )
        );
    }
}