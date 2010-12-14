<?php

class Test_One_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTest(Test_One_Core_AllTests::suite());

        return $suite;
    }
}