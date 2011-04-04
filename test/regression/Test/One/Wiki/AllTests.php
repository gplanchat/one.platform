<?php

class Test_One_Wiki_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTest(Test_One_Wiki_Model_AllTests::suite());

        return $suite;
    }
}