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

    private static $_config = self::ENV_PRODUCTION;

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
        if ($path === null) {
            return self::$_config;
        }

        return $oldConfig;
    }

    public static function app($websiteId = null)
    {
        if (self::$_app instanceof Zend_Application) {
            return self::$_app;
        }

        require_once 'Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();

        self::$_app = new Zend_Application(self::$_env, self::$_config);
        self::$_config = self::$_app->getOptions();

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
        $langRoute = new Zend_Controller_Router_Route(':lang', array(
            'lang' => 'fr'
            ), array(
            'lang' => '[a-z]{2}'
            ));

        if (($domain = self::getDomain()) !== null) {
            $baseRoute = new Zend_Controller_Router_Route_Chain();
            $baseRoute
                ->chain(new Zend_Controller_Router_Route_Hostname($domain))
                ->chain(new Zend_Controller_Router_Route_Static(self::getBasePath()))
                ->chain($langRoute)
            ;
            $langRoute = $baseRoute;
        }

        $pathPattern = APPLICATION_PATH . self::DS . 'code' . self::DS . '%s'
                . self::DS . '%s' . self::DS . 'controllers';

        $frontController = $router->getFrontController();
        $modules = self::$_app->getOption('modules');
        foreach ($modules as $moduleName => $moduleConfig) {
            $codePool = isset($moduleConfig['codePool']) ? $moduleConfig['codePool'] : 'local';

            $path = sprintf($pathPattern, $codePool, str_replace('_', DS, $moduleName));

            $frontController
                ->addControllerDirectory($path, $moduleName);

            $modulePath = new Zend_Controller_Router_Route_Static($moduleName);
            $chainRoute = $langRoute->chain($modulePath);
            $chainRoute->chain(new Zend_Controller_Router_Route('/:controller/:action/*', array(
                'module'     => $moduleName,
                'controller' => 'index',
                'action'     => 'index'
                )));
            $router->addRoute('module.' . strtolower($moduleName), $chainRoute);
        }

        $cmsRoute = new One_Cms_Model_Router_Route();
        $defaultRoute = $langRoute->chain($cmsRoute);
        $router->addRoute('default', $defaultRoute);
    }
}