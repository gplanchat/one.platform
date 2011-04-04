<?php

class Test_One_Core_Setup_Model_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTest(Test_One_Core_Setup_Model_Updater_AllTests::suite());

        return $suite;
    }
}