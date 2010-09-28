<?php

require_once 'One/Core/ObjectTest.php';

class One_Core_AllTests
{
    public static function suite()
    {
        One::app();

        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTestSuite('One_Core_ObjectTest');
        $suite->addTest(One_Core_Model_AllTests::suite());

        return $suite;
    }
}