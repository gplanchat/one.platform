<?php

class One_Core_Model_Application
    extends Zend_Application
{
    protected $_websiteId = null;

    protected $_activeModules = array();

    protected $_dependencies = array();

    protected $_classInflector = null;

    protected $_modelSingletons = array();

    protected $_event = null;

    /**
     * @var Zend_Controller_Front
     */
    protected $_frontController = null;

    const DS = DIRECTORY_SEPARATOR;
    const PS = PATH_SEPARATOR;

    const DEFAULT_CONFIG_SECTION = 'default';

    public function __construct($websiteId, $environment)
    {
        $this->_websiteId = $websiteId;
        $this->_event = new One_Core_Model_Event_Dispatcher();

        $config = $this->_initConfig($environment);

        $config->setReadOnly();

        parent::__construct($environment, $config);

        $routeStack = One_Core_Model_Router_Route_Stack::getInstance(new Zend_Config((array) $config->routes));
        $pathPattern = implode(self::DS, array(APPLICATION_PATH, 'code', '%s', '%s', 'controllers'));

        $this->_frontController = $this->getBootstrap()
            ->getPluginResource('frontController')
            ->getFrontController()
        ;
        $router = $this->_frontController->getRouter();
        $router->addRoute('default', $routeStack);

        foreach ($config->modules as $moduleName => $moduleConfig) {
            if (!in_array($moduleName, $this->_activeModules)) {
                continue;
            }

            $modulePath = sprintf($pathPattern, $moduleConfig->codePool, str_replace('_', self::DS, $moduleName));
            $this->_frontController->addControllerDirectory($modulePath, $moduleName);

            $routeClassName = 'core/router.route';
            if (isset($moduleConfig->route)) {
                $routeClassName = $moduleConfig->route->get('type', $routeClassName);
            }
            $moduleRoute = $this->getModel($routeClassName, $moduleConfig->route, $moduleName);

            if (isset($moduleConfig->route->name)) {
                $routeName = $moduleConfig->route->name;
            } else {
                $routeName = 'module.' . strtolower($moduleName);
            }

            if (isset($moduleConfig->route->before)) {
                $routeStack->pushBefore($routeName, $moduleRoute, $moduleConfig->route->before);
            } else if (isset($moduleConfig->route->after)) {
                $routeStack->pushAfter($routeName, $moduleRoute, $moduleConfig->route->after);
            } else {
                $routeStack->push($routeName, $moduleRoute);
            }
        }

        $this->_frontController->setDefaultModule('One_Core');
        $dispatcher = $this->_frontController->getDispatcher();
        $dispatcher->setParam('prefixDefaultModule', true);

        $dispatcher->setParam('applicationInstance', $this);
        $dispatcher->setParam('websiteId', $this->getWebsiteId());

        $this->_frontController->setParam('noViewRenderer', true);
    }

    protected function _initConfig($environment)
    {
        $configFile = implode(self::DS, array(APPLICATION_PATH, 'configs', 'application.xml'));
        require_once 'Zend/Config/Xml.php';
        $config = new Zend_Config_Xml($configFile, self::DEFAULT_CONFIG_SECTION, true);
        try {
            $config->merge(new Zend_Config_Xml($configFile, $environment, true));
        } catch (Zend_Config_Exception $e) {
        }

        $configFile = implode(self::DS, array(APPLICATION_PATH, 'configs', 'local.xml'));
        require_once 'Zend/Config/Xml.php';
        $config->merge(new Zend_Config_Xml($configFile, self::DEFAULT_CONFIG_SECTION, true));
        try {
            $config->merge(new Zend_Config_Xml($configFile, $environment, true));
        } catch (Zend_Config_Exception $e) {
        }

        $pathPattern = implode(self::DS, array(APPLICATION_PATH, 'code', '%s', '%s', 'configs', 'module.xml'));
        if (($modules = $config->get('modules')) === null) {
            require_once 'One/core/Exception/ConfigurationError.php';
            throw new One_Core_Exception_ConfigurationError(
                "No modules found. A core module should at least be declared.");
        }
        foreach ($modules as $moduleName => $moduleConfig) {
            if (!in_array(strtolower($moduleConfig->get('active')), array(1, true, '1', 'true', 'on'))) {
                continue;
            }

            if (($codePool = $moduleConfig->get('codePool')) === null) {
                $codePool = 'local';
                $moduleConfig->codePool = $codePool;
            }

            $path = sprintf($pathPattern, $codePool, str_replace('_', self::DS, $moduleName));
            if (!file_exists($path)) {
                $modules->get($moduleName)->active = false;
                continue;
            }

            $moduleConfig = new Zend_Config_Xml($path, null, true);
            $config->merge($moduleConfig->default);
            if (isset($moduleConfig->$environment)) {
                $config->merge($moduleConfig->$environment);
            }

            $this->_addModule($moduleName, $moduleConfig);
        }

        $this->_validateModulesActivation($modules);

        return $config;
    }

    protected function _validateModulesActivation($modulesConfig)
    {
        $moduleDependencies = $this->_dependencies;
        $sortedModules = array();
        while (!empty($moduleDependencies)) {
            $modulesCount = count($moduleDependencies);
            foreach ($moduleDependencies as $slaveModule => $dependencies) {
                if (!empty($dependencies)) {
                    continue;
                }
                unset($moduleDependencies[$slaveModule]);
                $sortedModules[$slaveModule] = null;

                foreach ($moduleDependencies as $module => $dependencies) {
                    foreach ($dependencies as $masterModule) {
                        if (($offset = array_search($slaveModule, $moduleDependencies[$module])) !== false) {
                            unset($moduleDependencies[$module][$offset]);
                        }
                    }
                }
            }
            if ($modulesCount === count($moduleDependencies)) {
//                throw new One_Core_Exception_ConfigurationError(
//                    'Module topological sort could not be proceeded, the dependency tree may be cyclic');
                break;
            }
        }

        $this->_activeModules = array_keys($sortedModules);
    }

    protected function _addModule($moduleName, $moduleConfig)
    {
        if (($dependencies = $moduleConfig->get('requires')) !== null) {
            foreach ($dependencies as $dependency) {
                if (!isset($this->_dependencies[$moduleName])) {
                    $this->_dependencies[$moduleName] = array();
                }
                $this->_dependencies[$moduleName][] = $dependency;
            }
        } else {
            $this->_dependencies[$moduleName] = array();
        }
    }

    public function getWebsiteId()
    {
        return $this->_websiteId;
    }

    public function setClassInflector($inflector)
    {
        $this->_classInflector = $inflector;
    }

    /**
     *
     * @param unknown_type $className
     * @param unknown_type $domain
     * @return StdClass
     */
    protected function _inflectClassName($className, $domain)
    {
        if ($this->_classInflector === null) {
            $this->_classInflector = new One_Core_Model_Inflector();
        }

        $offset = strpos($className, '/');
        $classData = array(
            'module' => substr($className, 0, $offset),
            'domain' => $domain,
            'alias'  => substr($className, $offset + 1),
            'name'   => '',
            );

        $domainXmlPath = explode('/', $domain);
        array_push($domainXmlPath, $classData['module']);
        $domainConfig = $this->getOption(array_shift($domainXmlPath));
        while (count($domainXmlPath)) {
            $key = array_shift($domainXmlPath);
            if (isset($domainConfig[$key])) {
                $domainConfig = $domainConfig[$key];
            }
        }

        if ((isset($domainConfig['rewrite']) &&
                $rewrite = $domainConfig['rewrite']) !== null &&
                isset($rewrite[$classData['alias']]) &&
                $rewrite[$classData['alias']] !== null) {
            $classData['name'] = $rewrite->{$classData['alias']};
        } else if (isset($domainConfig['namespace']) && ($namespace = $domainConfig['namespace']) !== null) {
            $classData['name'] = $namespace . '_' . $this->_classInflector->filter($classData['alias']);
        } else {
            $classData['name'] = $this->_classInflector->filter('one.' . $classData['module'])
                    . '_' . $this->_classInflector->filter($classData['domain'])
                    . '_' . $this->_classInflector->filter($classData['alias']);
        }

        return $classData;
    }

    public function getModel($identifier, $options = null)
    {
        $classData = $this->_inflectClassName($identifier, 'model');

        Zend_Loader::loadClass($classData['name']);

        $reflectionClass = new ReflectionClass($classData['name']);
        if ($reflectionClass->isSubclassOf('One_Core_Object')) {
            $object = $reflectionClass->newInstance($classData['module'], $options);
        } else {
            $params = func_get_args();
            array_shift($params);
            $object = $reflectionClass->newInstanceArgs($params);
        }
        return $object;
    }

    public function getSingleton($identifier, $options = null)
    {
        if (!isset($this->_modelSingletons[$identifier])) {
            $this->_modelSingletons[$identifier] = $this->getModel($identifier, $options);
        }
        return $this->_modelSingletons[$identifier];
    }

    public function getBlock($identifier, $options = null)
    {
        $classData = $this->_inflectClassName($identifier, 'block');

//        Zend_Loader::loadClass($classData['name']);

        $reflectionClass = new ReflectionClass($classData['name']);
        if ($reflectionClass->isSubclassOf('One_Core_Object')) {
            $object = $reflectionClass->newInstance($classData['module'], $options);
        } else {
            $params = func_get_args();
            array_shift($params);
            $object = $reflectionClass->newInstanceArgs($params);
        }
        return $object;
    }

    public function getBlockSingleton($identifier, $options = null)
    {
        if (!isset($this->_blockSingletons[$identifier])) {
            $this->_blockSingletons[$identifier] = $this->getBlock($identifier, $options);
        }
        return $this->_blockSingletons[$identifier];
    }

    public function throwException($identifier, $message = null, $_ = null)
    {
        $classData = $this->_inflectClassName($identifier, 'exception');

//        Zend_Loader::loadClass($classData['name']);

        $reflectionClass = new ReflectionClass($classData['name']);
        if ($reflectionClass->isSubclassOf('One_Core_Exception')) {
            $args = array_slice(func_get_args(), 1);
            $object = $reflectionClass->newInstanceArgs($args);
        } else {
            $args = array_slice(func_get_args(), 2);
            $object = $reflectionClass->newInstance(sfprintf($message, $args));
        }
        throw $object;
    }

    public function dispatchEvent($eventName, $params)
    {
        $this->_event->dispatch($eventName, $params);
    }

    public function registerEvent($eventName, $listener)
    {
        if (!$listener instanceof One_Core_Model_Event_Listener && is_callable($listener)) {
            $listener = $this->getModel('core/event.listener')
                ->setCallback($listener)
            ;
        } else {
            $this->throwException('core/invalid-method-call', 'Parameter 2 shoud'
                .' be an instance of One_Core_Model_Event_Listener or be a callable'
                .' variable. See documentation of call_user_func() function.');
        }
        $this->_event->dispatch($eventName, $listener);
    }
}