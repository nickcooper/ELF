<?php

App::uses('Address', 'AddressBook.Model');

/**
 * Address Test Case
 *
 */
class AddressTest extends CakeTestCase
{
/**
 * setUp method
 *
 * @return void
 */	
    public function setUp()
    {
    	parent::setUp();
    }

/**
 * testAdd method
 *
 * @return void
 */
    public function testAdd($id = null)
    {
       
       //generic validation for building the unit testing framework for each plugin
       $this->assertEquals(1, 1);
       
    }
}
?>