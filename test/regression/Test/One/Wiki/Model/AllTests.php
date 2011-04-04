<?php

class Test_One_Wiki_Model_AllTests
{
    public static function suite()
    {
        One::app();

        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTest(Test_One_Wiki_Model_Syntax_AllTests::suite());

        return $suite;
    }
}