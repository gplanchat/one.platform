<?php

class Test_One_Core_Model_AllTests
{
    public static function suite()
    {
        One::app();

        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTestSuite('Test_One_Core_Model_ApplicationTest');

        return $suite;
    }
}