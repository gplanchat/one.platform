<?php

require_once 'OneTest.php';
require_once 'One/AllTests.php';

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTestSuite('OneTest');
        $suite->addTest(One_AllTests::suite());

        return $suite;
    }
}