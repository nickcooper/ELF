<?php
class AllModelTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All model tests');
        
        $suite->addTestDirectory(APP . DS . 'Plugin' . DS . 'Abatements' . DS . 'Test' . DS . 'Case' . DS . 'Model');

        return $suite;
    }
}