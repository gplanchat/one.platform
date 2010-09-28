<?php

require_once 'One/Core/Model/ApplicationTest.php';

class One_Core_Model_AllTests
{
    public static function suite()
    {
        One::app();

        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTestSuite('One_Core_Model_ApplicationTest');

        return $suite;
    }
}