<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';

class Test_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTestSuite('Test_OneTest');
        $suite->addTest(Test_One_AllTests::suite());

        return $suite;
    }
}