<?php
class AllModelTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All model tests');
        $suite->addTestDirectory(APP . '/Plugin/Configuration/Test/Case/Model');
        return $suite;
    }
}
?>