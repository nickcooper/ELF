<?php
App::uses('Account', 'Accounts.Model');
App::uses('Account', 'InsuranceInformations.Model');

class AccountTest extends CakeTestCase
{
/**
 * setUp method
 *
 * @return void
 */ 
    public function setUp() {

    }

/**
 * testAdd method
 *
 * @return void
 */
    public function testAdd($id = null) {
       
       //generic validation for building the unit testing framework for each plugin
       $this->assertEquals(1, 1);
       
    }

/**
 * testInsuranceAdd method
 *
 * @return void
 */
    public function testInsuranceAdd($id = null) {
       //generic validation for building the unit testing framework for each plugin
       $this->assertEquals(1, 1);
    }
}