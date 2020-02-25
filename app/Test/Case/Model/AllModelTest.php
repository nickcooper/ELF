<?php
class AllModelTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All model tests');
        $suite->addTestDirectory(APP . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Abatements' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Accounts' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'AddressBook' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Configuration' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'ContinuingEducation' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Firms' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Licenses' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Notes' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'OutputDocuments' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Pages' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Payments' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Reports' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Searchable' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Uploads' . DS . 'Test' . DS . 'Case' . DS . 'Model');

        return $suite;
    }
}