<?php

class Test_One_Core_Model_ApplicationTest extends PHPUnit_Framework_TestCase
{
    private $_applicationHandler = null;

    private $_websiteId = 0;

    private $_environment = '';

    private $_config = null;

    private $_defaultConfg = array(
        'default' => array(
            'modules' => array(
                'One_Core' => array(
                    'active'   => true,
                    'codePool' => 'local',
                    'route'    => array(
                        'type' => 'core/router.route',
                        'path' => 'core',
                        'name' => 'default'
                        )
                    ),
                'One_Foo' => array(
                    'active'   => false,
                    'codePool' => 'local',
                    'requires' => array(
                        'One_Core'
                        ),
                    'route'    => array(
                        'type' => 'core/router.route',
                        'path' => 'foo',
                        'name' => 'module.foo'
                        )
                    ),
                'One_Bar' => array(
                    'active'   => true,
                    'codePool' => 'local',
                    'requires' => array(
                        'One_Core',
                        'One_Foo',
                        'One_Baz'
                        ),
                    'route'    => array(
                        'type' => 'bar/router.route',
                        'path' => 'bar',
                        'name' => 'module.bar'
                        )
                    ),
                'One_Baz' => array(
                    'active'   => false,
                    'codePool' => 'local',
                    'requires' => array(
                        'One_Core',
                        'One_Foo'
                        ),
                    'route'    => array(
                        'type' => 'baz/router.route',
                        'path' => 'baz',
                        'name' => 'module.baz'
                        )
                    ),
                'One_Dummy' => array(
                    'active'   => false,
                    'codePool' => 'local',
                    'requires' => array(
                        'One_Dumb',
                        ),
                    'route'    => array(
                        'type' => 'dummy/router.route',
                        'path' => 'dummy',
                        'name' => 'module.dummy'
                        )
                    ),
                'One_Dumb' => array(
                    'active'   => false,
                    'codePool' => 'local',
                    'requires' => array(
                        'One_Dummy',
                        ),
                    'route'    => array(
                        'type' => 'dumb/router.route',
                        'path' => 'dumb',
                        'name' => 'module.dumb'
                        )
                    )
                )
            ),
        'production' => array(
            'extends' => 'default'
            ),
        'test_acyclic_dependencies' => array(
            'extends' => 'production',
            'modules' => array(
                'One_Foo' => array(
                    'active' => true
                    ),
                'One_Baz' => array(
                    'active' => true
                    )
                ),
            ),
        'test_cyclic_dependencies' => array(
            'extends' => 'production',
            'modules' => array(
                'One_Dummy' => array(
                    'active' => true
                    ),
                'One_Dumb' => array(
                    'active' => true
                    )
                )
            )
        );

    public function __construct()
    {
    }

    public function tearDown()
    {
        $this->_applicationHandler = null;
        $this->_environment = null;
        $this->_config = null;
    }

    public function setUp()
    {
        $this->_environment = 'production';
        $this->_config = $this->_defaultConfg;
        $this->_websiteId = 1;
    }

    public function initApplication()
    {
        $this->_applicationHandler = new One_Core_Model_Application(
            $this->_websiteId, $this->_environment, array(), $this->_config);
    }

    public function testWebsiteId()
    {
        $this->initApplication();

        $this->assertAttributeType('int', '_websiteId', $this->_applicationHandler,
            'Website ID was not properly set in the application');

        $this->assertAttributeEquals($this->_websiteId, '_websiteId', $this->_applicationHandler,
            'Website ID was not properly set in the application');
    }

    public function testEnvironment()
    {
        $this->initApplication();

        $this->assertAttributeType('string', '_environment', $this->_applicationHandler,
            'Environment was not properly set in the application');

        $this->assertAttributeEquals($this->_environment, '_environment', $this->_applicationHandler,
            'Environment was not properly set in the application');
    }

    public function testModuleList()
    {
        $this->_environment = 'test_acyclic_dependencies';
        $this->initApplication();

        $activeModules = array(
            'One_Core',
            'One_Foo',
            'One_Baz',
            'One_Bar'
            );

        $this->assertAttributeEquals($activeModules, '_activeModules',
            $this->_applicationHandler);

        $this->_environment = 'test_cyclic_dependencies';
        $this->_applicationHandler = null;
        $this->initApplication();

        $activeModules = array(
            'One_Core'
            );

        $this->assertAttributeEquals($activeModules, '_activeModules',
            $this->_applicationHandler);
    }

    public function testModuleDependencies()
    {
        $this->_environment = 'test_acyclic_dependencies';
        $this->initApplication();

        $this->assertAttributeType('array', '_dependencies', $this->_applicationHandler,
            'There is an error in the module dependency management');

        $dependencies = array(
            'One_Core' => array(),
            'One_Foo' => array('One_Core'),
            'One_Baz' => array('One_Core', 'One_Foo'),
            'One_Bar' => array('One_Core', 'One_Foo', 'One_Baz'),
            );

        $this->assertAttributeEquals($dependencies, '_dependencies', $this->_applicationHandler,
            'There is an error in the module dependency management');
    }
}