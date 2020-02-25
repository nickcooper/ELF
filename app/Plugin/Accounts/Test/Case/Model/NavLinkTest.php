<?php
App::uses('NavLink', 'Accounts.Model');

class NavLinkTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.Accounts.nav_link',
    );

    public function setUp()
    {
        $this->NavLink = ClassRegistry::init('Accounts.NavLink');
    }

    /**
     * getLinksForGroup method
     *
     * @return void
     */
    public function testGetLinksForGroup()
    {
        $nav_links = $this->NavLink->getLinksForGroup(2);
        $passed = false;
        if (isset($nav_links[0]['NavLink']['label']) && $nav_links[0]['NavLink']['label'] == 'Accounts')
        {
            $passed = true;
        }
        $this->assertTrue($passed);

        try
        {
            $nav_links = $this->NavLink->getLinksForGroup(null);
        }
        catch (Exception $e)
        {
            $this->setExpectedException('Exception');
            throw $e;
        }
    }
}