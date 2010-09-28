<?php

require_once 'One/Core/AllTests.php';

class One_AllTests
{
    public static function suite()
    {
        One::app();

        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTest(One_Core_AllTests::suite());

        return $suite;
    }
}