<?php

defined('APPLICATION_PATH') ||
    define('APPLICATION_PATH', realpath(dirname(__FILE__)));

final class One
{
    const ENV_PRODUCTION = 'production';
    const ENV_PREPROD    = 'preprod';
    const ENV_TESTING    = 'testing';
    const ENV_DEBUG      = 'debug';

    const DS = DIRECTORY_SEPARATOR;
    const PS = PATH_SEPARATOR;

    /**
     * @var Zend_Application
     */
    private static $_app = null;

    /**
     * @var string
     */
    private static $_env = null;

    /**
     * @var Zend_Config
     */
    private static $_config = null;

    /**
     * @var Zend_Controller_Front
     */
    private static $_frontController = null;

    /**
     * @var Zend_Controller_Router_Abstract
     */
    private static $_router = null;

    /**
     * @var One_Core_Model_Router_Route_Stack
     */
    private static $_routeStack = null;

    /**
     * @var Zend_Controller_Action_Helper_ViewRenderer
     */
    private static $_viewRenderer = null;

    /**
     * @var string
     */
    private static $_basePath = '/';

    /**
     * @var string
     */
    private static $_domain = null;

    private static $_modelSingletons = array();

    /**
     * Set environment name
     *
     * @param string $env
     */
    public static function setEnv($env = self::ENV_PRODUCTION)
    {
        $oldEnv = self::$_env;
        self::$_env = $env;

        return $oldEnv;
    }

    /**
     * Get the currently set environment name
     *
     * @return string
     */
    public static function getEnv()
    {
        if (self::$_env === null) {
            self::$_env = self::ENV_PRODUCTION;
        }

        return self::$_env;
    }

    private static function _loadConfig()
    {
        $configFile = implode(self::DS, array(APPLICATION_PATH,
            'configs', 'application.xml'));

        require_once 'Zend/Config/Xml.php';
        self::$_config = new Zend_Config_Xml($configFile, self::getEnv(), true);

        $pathPattern = implode(self::DS, array(APPLICATION_PATH,
            'code', '%s', '%s', 'configs', 'module.xml'));
        $modules = self::$_config->get('modules');
        foreach ($modules as $moduleName => $moduleConfig) {
            if (!in_array(strtolower($moduleConfig->get('active')), array(1, true, '1', 'true', 'on'))) {
                continue;
            }

            if (($codePool = $moduleConfig->get('codePool')) === null) {
                $codePool = 'local';
                $moduleConfig->codePool = $codePool;
            }

            $path = sprintf($pathPattern, $codePool, str_replace('_', DS, $moduleName));
            if (!file_exists($path)) {
                self::$_config->get('modules')->get($moduleName)->active = false;
                continue;
            }

            $moduleConfig = new Zend_Config_Xml($path);
            if (($config = $moduleConfig->get(self::getEnv())) !== null) {
                self::$_config->merge($config);
            } else if (($config = $moduleConfig->get('default')) !== null) {
                self::$_config->merge($config);
            } else {
                self::$_config->merge($moduleConfig);
            }
        }

        self::$_config->setReadOnly();

        return self::$_config;
    }

    public static function getConfig($path = null)
    {
        if (self::$_config === null) {
            self::_loadConfig();
        }

        if ($path === null) {
            return self::$_config;
        }

        $config = self::$_config;
        foreach (explode('/', $path) as $pathChunk) {
            $config = $config->get($pathChunk);
            if ($config === null) {
                return null;
            }
        }

        if ($config instanceof Zend_Config) {
            return $config->toArray();
        }
        return $config;
    }

    /**
     * Get the current application instance
     *
     * @param unknown_type $websiteId
     * @return Zend_Application
     */
    public static function app()
    {
        if (self::$_app instanceof Zend_Application) {
            return self::$_app;
        }

        require_once 'Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();

        self::$_app = new Zend_Application(self::getEnv(), self::getConfig());

        self::$_app->setIncludePaths(array(
            realpath(APPLICATION_PATH . self::DS . 'code' . self::DS . 'core'),
            realpath(APPLICATION_PATH . self::DS . 'code' . self::DS . 'community'),
            realpath(APPLICATION_PATH . self::DS . 'code' . self::DS . 'local')
            ));

        $modules = self::$_app->getOption('modules');
        self::$_app->setAutoloaderNamespaces(array_keys($modules));

        $viewRenderer = self::getViewRenderer();

        Zend_Layout::startMvc(array(
            'inflectorTarget' => 'page/:script.:suffix')
            );

        $viewRenderer->setViewScriptPathSpec('/:controller/:action.phtml');

//        $layout = self::$_config['layout'];
//        $design = $layout['design'];
//        $template = $layout['template'];
//        $viewBasePath = APPLICATION_PATH . self::DS . 'design' . self::DS . $design . self::DS . $template;
//        $viewRenderer->setViewBasePathSpec($viewBasePath);

        $routeStack = new One_Core_Model_Router_Route_Stack();
        $pathPattern = implode(self::DS, array(APPLICATION_PATH, 'code', '%s', '%s', 'controllers'));
        foreach ($modules as $moduleName => $moduleConfig) {
            if (!isset($moduleConfig['active']) || !in_array(strtolower($moduleConfig['active']), array('1', 'true', 'on'))) {
                continue;
            }

            $modulePath = sprintf($pathPattern, $moduleConfig['codePool'], str_replace('_', DS, $moduleName));
            self::_registerModule(self::getFrontController(), self::getRouter(), $moduleName, $moduleConfig, $modulePath);
        }

        self::getFrontController()->setDefaultModule('One_Core');
        $dispatcher = self::getFrontController()->getDispatcher();
        $dispatcher->setParam('prefixDefaultModule', true);

        return self::$_app;
    }

    public static function getFrontController($cache = true)
    {
        if ($cache = true && self::$_frontController instanceof Zend_Controller_Front) {
            return self::$_frontController;
        }

        self::$_frontController = self::$_app->getBootstrap()
            ->getPluginResource('frontController')
            ->getFrontController()
        ;

        return self::$_frontController;
    }

    public static function getRouter($cache = true)
    {
        if ($cache = true && self::$_router instanceof Zend_Controller_Router_Abstract) {
            return self::$_router;
        }

        if (self::$_frontController instanceof Zend_Controller_Front) {
            self::$_router = self::$_frontController->getRouter();
        } else if (($frontController = self::getFrontController()) !== null) {
            self::$_router = $frontController->getRouter();
        }
        return self::$_router;
    }

    public static function getViewRenderer()
    {
        if (self::$_viewRenderer === null) {
            self::$_viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
            Zend_Controller_Action_HelperBroker::addHelper(self::$_viewRenderer);
        }
        return self::$_viewRenderer;
    }

    public static function setBasePath($basePath)
    {
        self::$_basePath = $basePath;
    }

    public static function getBasePath()
    {
        return self::$_basePath;
    }

    public static function setDomain($domain)
    {
        self::$_domain = $domain;
    }

    public static function getDomain()
    {
        return self::$_domain;
    }

    private static function _registerModule($frontController, $router, $moduleName, $moduleConfig, $modulePath)
    {
        $frontController->addControllerDirectory($modulePath, $moduleName);

        $routeClassName = 'core/router.route';
        if (isset($moduleConfig['route']) && isset($moduleConfig['route']['type'])) {
            $routeClassName = self::loadClass($moduleConfig['route']['type']);
        }
        $moduleRoute = new $routeClassName($moduleConfig['route'], $moduleName);

        if (isset($moduleConfig['route']['name'])) {
            $router->addRoute($moduleConfig['route']['name'], $moduleRoute);
        } else {
            $router->addRoute('module.' . strtolower($moduleName), $moduleRoute);
        }
    }

    private static function _inflectClassName($className, $domain)
    {
        static $inflector = null;

        if ($inflector === null) {
            $inflector = new One_Core_Model_Inflector();
        }

        $offset = strpos($className, '/');
        $module = substr($className, 0, $offset);
        $class = substr($className, $offset + 1);

//        self::getConfig("{$domain}/{$module}/rewrite")

        $xmlDomain = str_replace('.', '/', $domain);
        if (($namespace = self::getConfig("{$xmlDomain}/{$module}/namespace")) !== null) {
            return $namespace . '_' . $inflector->filter($class);
        }

        return $inflector->filter('one.' . $module) . '_' . $inflector->filter($domain)
                . '_' . $inflector->filter($class);
    }

    public static function loadClass($classIdentifier, $domain = 'model')
    {
        $className = self::_inflectClassName($classIdentifier, $domain);
        Zend_Loader::loadClass($className);
        return $className;
    }

    public function getModel($identifier, $params)
    {
        $className = self::loadClass($identifier, 'model');

        $reflectionClass = new ReflectionClass($className);
        if ($reflectionClass->isSubclassOf('One_Core_Object')) {
            $object = $reflectionClass->newInstance($moduleName, $params);
        } else {
            $object = $reflectionClass->newInstanceArgs($params);
        }
        return $object;
    }

    public function getSingleton($identifier)
    {
        if (isset(self::$_modelSingletons[$identifier])) {
            self::$_modelSingletons[$identifier] = self::getModel($identifier);
        }
        return self::$_modelSingletons[$identifier];
    }
}