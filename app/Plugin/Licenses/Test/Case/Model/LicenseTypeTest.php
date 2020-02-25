<?php
App::uses('License', 'Licenses.Model');
App::uses('LicenseType', 'Licenses.Model');
App::uses('Element', 'Licenses.Model');

class LicenseTypeTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.Licenses.license',
        'plugin.Licenses.license_type',
        'plugin.Licenses.element',
        'plugin.Licenses.element_license_type',
    );

    public function setUp()
    {
        parent::setUp();

        // initialize needed models
        $this->LicenseType = ClassRegistry::init('Licenses.LicenseType');
    }

    /**
     * Test LicenseType::getLicenseTypeList()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeList()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;

            // run test case
            $types = $this->LicenseType->getLicenseTypeList();

            // set test result flag and message
            if ($types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getLicenseTypeList() did not return any records.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getLicenseTypeBySlugPass()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeBySlugPass()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;

            // run test case
            $types = $this->LicenseType->getLicenseTypeBySlug('license_type_1');

            // set test result flag and message
            if ($types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getLicenseTypeBySlugPass() did not return any records.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getLicenseTypeBySlugFail()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeBySlugFail()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;

            // run test case
            $types = $this->LicenseType->getLicenseTypeBySlug('license_type_999');

            // set test result flag and message
            if (!$types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getLicenseTypeBySlugFail() returned records unexpectedly.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getLicenseTypeByIdPass()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeByIdPass()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;

            // run test case
            $types = $this->LicenseType->getLicenseTypeById(1);

            // set test result flag and message
            if ($types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getLicenseTypeByIdPass() did not return any records.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getLicenseTypeByIdFail()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeByIdFail()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;

            // run test case
            $types = $this->LicenseType->getLicenseTypeById(999);

            // set test result flag and message
            if (!$types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getLicenseTypeByIdFail() returned records unexpectedly.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getLicenseTypeByAbbrPass()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeByAbbrPass()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;

            // run test case
            $types = $this->LicenseType->getLicenseTypeByAbbr('LT3');

            // set test result flag and message
            if ($types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getLicenseTypeByAbbrPass() did not return any records.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getLicenseTypeByAbbrFail()
     *
     * @return void
     * @access public
     */
    public function testGetLicenseTypeByAbbrFail()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;

            // run test case
            $types = $this->LicenseType->getLicenseTypeByAbbr('ZZZ');

            // set test result flag and message
            if (!$types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getLicenseTypeByAbbrFail() returned records unexpectedly.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getAppElementsByIdPass()
     *
     * @return void
     * @access public
     */
    public function testGetAppElementsByIdPass()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;
            $input = 1;  // input is passing ID

            // run test case
            $types = $this->LicenseType->getAppElements($input);

            // set test result flag and message
            if ($types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getAppElementsPass() did not return any records.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getAppElementsByIdFail()
     *
     * @return void
     * @access public
     */
    public function testGetAppElementsByIdFail()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;
            $input = '999'; // input is failing ID

            // run test case
            $types = $this->LicenseType->getAppElements($input);

            // set test result flag and message
            if (!$types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getAppElementsFail() returned records unexpectedly.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getAppElementsBySlugPass()
     *
     * @return void
     * @access public
     */
    public function testGetAppElementsBySlugPass()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;
            $input = 'license_type_1'; // input is passing slug

            // run test case
            $types = $this->LicenseType->getAppElements($input);

            // set test result flag and message
            if ($types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getAppElementsPass() did not return any records.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }


    /**
     * Test LicenseType::getAppElementsBySlugFail()
     *
     * @return void
     * @access public
     */
    public function testGetAppElementsBySlugFail()
    {
        try
        {
            // set test parameters
            $test_pass = false;
            $message = null;
            $input = 'license_type_2'; // input is failing slug

            // run test case
            $types = $this->LicenseType->getAppElements($input);

            // set test result flag and message
            if (!$types)
            {
                $test_pass = true;
            }
            else
            {
                $message = 'TEST FAILURE: getAppElementsFail() returned records unexpectedly.';
            }
        }
        catch (Exception $e)
        {
            $test_pass = false;
            $message = $e->getMessage();
        }

        // report back the test results
        $this->assertEquals(true, $test_pass, $message);
    }

    /**
     * Test LicenseType::getConversionTypes()
     *
     * @return void
     * @access public
     */
    public function testGetConversionTypes()
    {
        $results = $this->LicenseType->getConversionTypes(1);

        $expected = array(2 => 'License Type #2');

        $this->assertEquals($expected, $results);
    }
}