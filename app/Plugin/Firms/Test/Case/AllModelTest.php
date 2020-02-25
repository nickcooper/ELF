<?php
class AllModelTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All model tests');
        $suite->addTestDirectory(APP . '/Plugin/Firms/Test/Case/Model');
        return $suite;
    }
}
?>