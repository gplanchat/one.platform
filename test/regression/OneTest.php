<?php

require_once 'One.php';

class OneTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        One::app();
    }

    public function testClassInflector()
    {
        $this->assertEquals('One_Core_Model_Session', One::loadClass('core/session'),
            'Class inflection fails');

        $this->assertEquals('One_Core_Model_Setup_Patch', One::loadClass('core/setup.patch', 'model'),
            'Class inflection fails');

        $this->assertEquals('One_Core_Model_Orm_DataMapper', One::loadClass('core/data-mapper', 'model.orm'),
            'Class inflection fails');
    }
}