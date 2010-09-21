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

    private static $_app = null;

    private static $_env = null;

    private static $_config = null;

    private static $_frontController = null;

    private static $_router = null;

    private static $_viewRenderer = null;

    private static $_basePath = '/';

    private static $_domain = null;

    public static function setEnv($env = self::ENV_PRODUCTION)
    {
        $oldEnv = self::$_env;
        self::$_env = $env;

        return $oldEnv;
    }

    public static function getEnv()
    {
        if (self::$_env === null) {
            self::$_env = self::ENV_PRODUCTION;
        }

        return self::$_env;
    }

    public static function setConfig($config)
    {
        $oldConfig = self::$_config;

        self::$_config = $config;

        return $oldConfig;
    }

    public static function getConfig($path = null)
    {
        if (self::$_config === null) {
            $configFile = implode(self::DS, array(APPLICATION_PATH,
                'configs', 'application.xml'));

            require_once 'Zend/Config/Xml.php';
            self::$_config = new Zend_Config_Xml($configFile, self::getEnv());
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

    public static function app($websiteId = null)
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

        self::_buildRoutes(self::getRouter());

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

    private static function _buildRoutes(Zend_Controller_Router_Abstract $router)
    {
        $pathPattern = implode(self::DS, array(APPLICATION_PATH, 'code', '%s', '%s', 'controllers'));

        $frontController = $router->getFrontController();
        $modules = self::$_app->getOption('modules');
        foreach ($modules as $moduleName => $moduleConfig) {
            if (!in_array(strtolower($moduleConfig['active']), array('1', 'true', 'on'))) {
                continue;
            }
            $codePool = isset($moduleConfig['codePool']) ? $moduleConfig['codePool'] : 'local';

            $path = sprintf($pathPattern, $codePool, str_replace('_', DS, $moduleName));

            $frontController->addControllerDirectory($path, $moduleName);

            $routeClassName = 'core/router.route';
            if (isset($moduleConfig['route']) && isset($moduleConfig['route']['type'])) {
                $routeClassName = self::loadClass($moduleConfig['route']['type']);
            }
            $moduleRoute = new $routeClassName($moduleConfig['route'], null, $moduleName);

            if (isset($moduleConfig['route']['name'])) {
                $router->addRoute($moduleConfig['route']['name'], $moduleRoute);
            } else {
                $router->addRoute('module.' . strtolower($moduleName), $moduleRoute);
            }
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

        if (($namespace = self::getConfig("{$module}/{$domain}/namespace")) !== null) {
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
}