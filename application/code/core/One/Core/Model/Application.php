<?php

class One_Core_Model_Application
    extends Zend_Application
{
    protected $_websiteId = null;

    protected $_activeModules = array();

    protected $_dependencies = array();

    protected $_classInflector = null;

    protected $_modelSingletons = array();

    protected $_resourceSingletons = array();

    protected $_blockSingletons = array();

    protected $_helperSingletons = array();

    protected $_event = null;

    const DS = DIRECTORY_SEPARATOR;
    const PS = PATH_SEPARATOR;

    const DEFAULT_CONFIG_SECTION = 'default';

    public function __construct($websiteId, $environment, Array $moreSections = array())
    {
        $this->_websiteId = $websiteId;
        $this->_event = new One_Core_Model_Event_Dispatcher();

        $config = $this->_initConfig($environment, $moreSections);

        $config->setReadOnly();

        parent::__construct($environment, $config);

        $routeStack = One_Core_Model_Router_Route_Stack::getInstance(new Zend_Config(array()));
        $pathPattern = implode(self::DS, array(APPLICATION_PATH, 'code', '%s', '%s', 'controllers'));

        $this->_frontController = $this->getBootstrap()
            ->getPluginResource('frontController')
            ->getFrontController()
        ;
        $router = $this->_frontController->getRouter();
        $router->addRoute('modules-stack', $routeStack);

        foreach ($config->modules as $moduleName => $moduleConfig) {
            if (!in_array($moduleName, $this->_activeModules)) {
                continue;
            }

            $modulePath = sprintf($pathPattern, $moduleConfig->codePool, str_replace('_', self::DS, $moduleName));
            $this->_frontController->addControllerDirectory($modulePath, $moduleName);

            $routeClassName = 'core/router.route';
            if (isset($moduleConfig->route) && !empty($moduleConfig->route)) {
                $routeClassName = $moduleConfig->route->get('type', $routeClassName);
            }
            $moduleRoute = $this->getModel($routeClassName, $moduleConfig->route, $moduleName, $this);

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

        if ($config->system->routes instanceof Zend_Config) {
            foreach ($config->system->routes as $routeName => $routeConfig) {
                if (isset($routeConfig->type)) {
                    $reflectionClass = new ReflectionClass($routeConfig->type);
                    if (!$reflectionClass->isSubclassOf('Zend_Controller_Router_Route_Interface')) {
                        continue;
                    }
                    $reflectionMethod = $reflectionClass->getMethod('getInstance');

                    $route = $reflectionMethod->invoke(null, $routeConfig);
                } else {
                    $route = Zend_Controller_Router_Route::getInstance($routeConfig);
                }
                $router->addRoute($routeName, $route);
            }
        }
        $defaultModule = $config->system->get('default-module', 'One_Core');
        $this->_frontController->setDefaultModule($defaultModule);
        $dispatcher = $this->_frontController->getDispatcher();
        $dispatcher->setParam('prefixDefaultModule', true);

        $dispatcher->setParam('applicationInstance', $this);
        $dispatcher->setParam('websiteId', $this->getWebsiteId());

        $this->_frontController->setParam('noViewRenderer', true);
    }

    protected function _initConfig($environment, Array $moreSections)
    {
        array_unshift($moreSections, $environment);

        $configFile = implode(self::DS, array(APPLICATION_PATH, 'configs', 'application.xml'));
        require_once 'Zend/Config/Xml.php';
        $config = new Zend_Config_Xml($configFile, self::DEFAULT_CONFIG_SECTION, true);
        foreach ($moreSections as $section) {
            try {
                $config->merge(new Zend_Config_Xml($configFile, $section, true));
            } catch (Zend_Config_Exception $e) {
            }
        }

        $configFile = implode(self::DS, array(APPLICATION_PATH, 'configs', 'local.xml'));
        $config->merge(new Zend_Config_Xml($configFile, self::DEFAULT_CONFIG_SECTION, true));
        foreach ($moreSections as $section) {
            try {
                $config->merge(new Zend_Config_Xml($configFile, $section, true));
            } catch (Zend_Config_Exception $e) {
            }
        }

        $configFilePattern = implode(self::DS, array(APPLICATION_PATH, 'configs', 'modules', '*.xml'));
        foreach (glob($configFilePattern) as $configFile) {
            $config->merge(new Zend_Config_Xml($configFile, self::DEFAULT_CONFIG_SECTION, true));
            foreach ($moreSections as $section) {
                try {
                    $config->merge(new Zend_Config_Xml($configFile, $section, true));
                } catch (Zend_Config_Exception $e) {
                }
            }
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

    public function getModel($identifier, $options = array())
    {
        $classData = $this->_inflectClassName($identifier, 'model');

        Zend_Loader::loadClass($classData['name']);

        try {
            $reflectionClass = new ReflectionClass($classData['name']);
            if ($reflectionClass->isSubclassOf('One_Core_Object')) {
                $object = $reflectionClass->newInstance($classData['module'], $options, $this);
            } else {
                $params = func_get_args();
                array_shift($params);
                $object = $reflectionClass->newInstanceArgs($params);
            }
        } catch (ReflectionException $e) {
            $this->throwException('core/implementation-error', $e->getMessage());
        }

        return $object;
    }

    public function getSingleton($identifier, $options = array())
    {
        if (!isset($this->_modelSingletons[$identifier])) {
            $this->_modelSingletons[$identifier] = $this->getModel($identifier, $options);
        }
        return $this->_modelSingletons[$identifier];
    }

    public function getResource($identifier, $type, $options = array())
    {
        $classData = $this->_inflectClassName($identifier, $type);

        Zend_Loader::loadClass($classData['name']);

        try {
            $reflectionClass = new ReflectionClass($classData['name']);
            if ($reflectionClass->isSubclassOf('One_Core_Object')) {
                $object = $reflectionClass->newInstance($classData['module'], $options, $this);
            } else {
                $params = func_get_args();
                array_shift($params);
                array_shift($params);
                $object = $reflectionClass->newInstanceArgs($params);
            }
        } catch (ReflectionException $e) {
            $this->throwException('core/implementation-error', $e->getMessage());
        }

        return $object;
    }

    public function getResourceSingleton($identifier, $type, $options = array())
    {
        if (!isset($this->_resourceSingletons[$identifier])) {
            $this->_resourceSingletons[$identifier] = $this->getResource($identifier, $type, $options);
        }
        return $this->_resourceSingletons[$identifier];
    }

    public function getBlock($identifier, $options = array(), One_Core_Model_Layout $layout = null)
    {
        $classData = $this->_inflectClassName($identifier, 'block');

//        Zend_Loader::loadClass($classData['name']);

        try {
            $reflectionClass = new ReflectionClass($classData['name']);
            $object = $reflectionClass->newInstance($classData['module'], $options, $this, $layout);
        } catch (ReflectionException $e) {
            $this->throwException('core/implementation-error', $e->getMessage());
        }
        $object->app($this);

        return $object;
    }

    public function getBlockSingleton($identifier, $options = array(), One_Core_Model_Layout $layout = null)
    {
        if (!isset($this->_blockSingletons[$identifier])) {
            $this->_blockSingletons[$identifier] = $this->getBlock($identifier, $options, $layout);
        }
        return $this->_blockSingletons[$identifier];
    }

    public function _getHelper($identifier, $options = array(), One_Core_Model_Layout $layout = null, One_Core_BlockAbstract $block = null)
    {
        $classData = $this->_inflectClassName($identifier, 'helper');

        Zend_Loader::loadClass($classData['name']);

        try {
            $reflectionClass = new ReflectionClass($classData['name']);
            $object = $reflectionClass->newInstance($classData['module'], $options, $this, $layout, $block);
        } catch (ReflectionException $e) {
            $this->throwException('core/implementation-error', $e->getMessage());
        }

        return $object;
    }

    public function getHelper($identifier, $options = array(), One_Core_Model_Layout $layout = null, One_Core_BlockAbstract $block = null)
    {
        if (!isset($this->_helperSingletons[$identifier])) {
            $this->_helperSingletons[$identifier] = $this->_getHelper($identifier, $options, $layout, $block);
        }
        return $this->_helperSingletons[$identifier];
    }

    public function throwException($identifier, $message = null, $_ = null)
    {
        $classData = $this->_inflectClassName($identifier, 'exception');

//        Zend_Loader::loadClass($classData['name']);

        $reflectionClass = new ReflectionClass($classData['name']);

        $numArgs = func_num_args();
        if ($numArgs <= 2) {
            $previous = null;
            $code = 0;
            $options = null;
        } else if ($numArgs > 2) {
            $offset = 1;
            $code = null;
            $previous = null;
            if (is_int($message)) {
                $code = $message;
                $message = func_get_arg(++$offset);
            }
            if ($message instanceof Exception) {
                $previous = $message;
                $message = func_get_arg(++$offset);
            }
//            if ($message instanceof One_Core_Object) {
//                $options = $message;
//                $message = func_get_arg(++$offset);
//            }
            $args = func_get_args();
            $message = vsprintf($message, array_slice($args, ++$offset));
        }
        $object = $reflectionClass->newInstance($message, $code, $previous);

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

    public function app(One_Core_Model_Application $applicaiton = null)
    {
        return $this;
    }

    public function getConfig($path = null)
    {
        $pathExploded = explode('.', $path);
        $firstChunk = array_shift($pathExploded);

        $config = $this->getOption($firstChunk);
        if (count($pathExploded) === 0) {
            return $config;
        }

        foreach ($pathExploded as $chunk) {
            if (!is_array($config) || !isset($config[$chunk])) {
                return null;
            }
            $config = $config[$chunk];
        }

        return $config;
    }

    /**
     * TODO: PHPDoc
     * TODO: PHPUnit
     *
     * @return Zend_Controller_Front
     */
    public function getFrontController()
    {
        return $this->getBootstrap()
            ->getPluginResource('frontController')
            ->getFrontController();
    }

    /**
     * TODO: PHPDoc
     * TODO: PHPUnit
     *
     * @return Zend_Controller_Router_Abstract
     */
    public function getRouter()
    {
        return $this->getFrontController()
            ->getRouter();
    }
}