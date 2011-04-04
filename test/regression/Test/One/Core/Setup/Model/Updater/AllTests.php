<?php

class Test_One_Core_Setup_Model_Updater_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('One Platform');

        $suite->addTestSuite('Test_One_Core_Setup_Model_Updater_ScriptQueueTest');

        return $suite;
    }
}