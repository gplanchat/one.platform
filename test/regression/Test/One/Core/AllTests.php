<?php

class Test_One_Core_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTestSuite('Test_One_Core_ObjectTest');
        $suite->addTest(Test_One_Core_Model_AllTests::suite());

        return $suite;
    }
}