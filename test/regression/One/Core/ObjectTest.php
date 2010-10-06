<?php

class One_Core_ObjectTest extends PHPUnit_Framework_TestCase
{
    protected $_className = 'One_Core_Object';

    protected $_module = 'test';

    protected $_constructorParams = array();

    public function setClassName($className)
    {
        $this->_className = $className;
    }

    public function getClassName()
    {
        return $this->_className;
    }

    public function setModule($module)
    {
        $this->_module = $module;
    }

    public function getModule()
    {
        return $this->_module;
    }

    public function setConstructorParams(array $constructorParams)
    {
        $this->_constructorParams = $constructorParams;
    }

    public function getConstructorParams(array $additionnal = array())
    {
        return array_merge($this->_constructorParams, $additionnal);
    }

    public function testGetData()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('setData', 'getData'),
            array($this->getModule(), array('testing' => $random)));

        $stub->expects($this->once())
            ->method('getData')
            ->with($this->equalTo('testing'))
            ->will($this->returnValue($random))
        ;

        $stub->getData('testing');
    }

    public function testSetData()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('setData', 'getData'),
            array($this->getModule(), array()));

        $stub->expects($this->once())
            ->method('getData')
            ->with($this->equalTo('testing'))
            ->will($this->returnValue($random))
        ;

        $stub->expects($this->once())
            ->method('setData')
            ->with($this->equalTo('testing'), $this->equalTo($random))
            ->will($this->returnValue($stub))
        ;

        $stub->setData('testing', $random);
        $stub->getData('testing');
    }

    public function testGetDataMagicCall()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('getData'),
            array($this->getModule(), array('testing_datas_for_test' => $random)));

        $stub->expects($this->once())
            ->method('getData')
            ->with($this->equalTo('testing_datas_for_test'))
            ->will($this->returnValue($random))
        ;

        $stub->getTestingDatasForTest();
    }

    public function testGetDataMagicCallDataNotSet()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('getData'),
            array($this->getModule(), array('testing_datas_for_test' => $random)));

        $stub->expects($this->once())
            ->method('getData')
            ->with($this->equalTo('testing_datas_for_test_unset'))
            ->will($this->returnValue(null))
        ;

        $stub->getTestingDatasForTestUnset();
    }

    public function testSetDataMagicCall()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('setData', 'getData'),
            array($this->getModule(), array()));

        $stub->expects($this->once())
            ->method('setData')
            ->with($this->equalTo('testing_datas_for_test'), $this->equalTo($random))
            ->will($this->returnValue($stub))
        ;

        $stub->setTestingDatasForTest($random);
    }

    public function testSetDataMagicCallObjectStatus()
    {
        $random = mt_rand();
        $class = $this->getClassName();
        $object = new $class($this->getModule(), array());

        $object->setTestingDatasForTest($random);

        $this->assertEquals($random, $object->getData('testing_datas_for_test'));
    }

    public function testUnsetDataMagicCall()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('unsetData'),
            array($this->getModule(), array('testing_datas_for_test' => $random)));

        $stub->expects($this->once())
            ->method('unsetData')
            ->with($this->equalTo('testing_datas_for_test'))
            ->will($this->returnValue($stub))
        ;

        $stub->unsTestingDatasForTest();
    }

    public function testHasDataMagicCall()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('hasData'),
            array($this->getModule(), array('testing_datas_for_test' => $random)));

        $stub->expects($this->once())
            ->method('hasData')
            ->with($this->equalTo('testing_datas_for_test'))
            ->will($this->returnValue(true))
        ;

        $stub->hasTestingDatasForTest();
    }

    public function testHasDataMagicCallDataNotSet()
    {
        $random = mt_rand();
        $stub = $this->getMock($this->getClassName(), array('hasData'),
            array($this->getModule(), array()));

        $stub->expects($this->once())
            ->method('hasData')
            ->with($this->equalTo('testing_datas_for_test'))
            ->will($this->returnValue(false))
        ;

        $stub->hasTestingDatasForTest();
    }

    public function testCountableInterfaceMethodCountWithEmptyObject()
    {
        $class = $this->getClassName();
        $object = new $class($this->getModule(), array());

        $this->assertEquals(0, $object->count());
    }

    public function testCountableInterfaceMethodCountWithNotEmptyObject()
    {
        $class = $this->getClassName();
        $object = new $class($this->getModule(), array());

        $object->setData('testing-1', mt_rand());
        $object->setData('testing-2', mt_rand());
        $object->setData('testing-3', mt_rand());
        $object->setData('testing-4', mt_rand());
        $object->setData('testing-5', mt_rand());
        $object->setData('testing-6', mt_rand());
        $object->setData('testing-7', mt_rand());
        $object->setData('testing-1', mt_rand());

        $this->assertEquals(7, $object->count());
    }
}