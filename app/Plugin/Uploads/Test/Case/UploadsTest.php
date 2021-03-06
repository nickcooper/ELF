<?php
class UploadsTest extends CakeTestSuite
{
    public static function suite()
    {   
        // Here's how you would add all of the tests in the model directory
        $suite = new CakeTestSuite('All model tests for uploads');
        $suite->addTestDirectory(APP_DIR . DS . 'Plugin' . DS . 'Uploads' . DS . 'Test' . DS . 'Case' . DS . 'Model');

        // Here's how you'd add an individual test file
        // $suite->addTestFile(filename);

        // And if you want to add a directory recursively 
        // in this example it's the whole 'Case' directory so 
        // it would pick up models, controllers, views, helpers, etc
        // $suite->addTestDirectoryRecursive(TESTS . 'Case');

        return $suite;
    }   
}