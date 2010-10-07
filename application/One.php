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
     * @var array
     */
    private static $_app = array();

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

    private static $_defaultWebsiteId = 0;

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

    public static function setDefaultWebsiteId($websiteId)
    {
        self::$_defaultWebsiteId = $websiteId;
    }

    public static function getDefaultWebsiteId()
    {
        return self::$_defaultWebsiteId;
    }

    private static function _loadConfig($environent = null)
    {
        $configFile = implode(self::DS, array(APPLICATION_PATH,
            'configs', 'application.xml'));

        if ($environent === null) {
            $environent = self::getEnv();
        }
        require_once 'Zend/Config/Xml.php';
        self::$_config = new Zend_Config_Xml($configFile, $environent, true);

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
     * @param mixed $websiteId
     * @return Zend_Application
     */
    public static function app($websiteId = null, $environment = null)
    {
        if ($websiteId === null) {
            $websiteId = self::getDefaultWebsiteId();
        }

        if (isset(self::$_app[$websiteId]) && self::$_app[$websiteId] instanceof Zend_Application) {
            return self::$_app[$websiteId];
        }

        if ($environment === null) {
            $environment = self::getEnv();
        }

        require_once 'One/Core/Model/Application.php';
        self::$_app[$websiteId] = new One_Core_Model_Application($websiteId, $environment);

        $modules = self::$_app[$websiteId]->getOption('modules');
        self::$_app[$websiteId]->setAutoloaderNamespaces(array_keys($modules));

        return self::$_app[$websiteId];
    }

    public static function getViewRenderer()
    {
        if (self::$_viewRenderer === null) {
            self::$_viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
            Zend_Controller_Action_HelperBroker::addHelper(self::$_viewRenderer);
        }
        return self::$_viewRenderer;
    }

    /**
     * @deprecated
     *
     * @param unknown_type $identifier
     * @param unknown_type $websiteId
     * @param unknown_type $params
     */
    public static function getModel($identifier, $websiteId = null, $params = null)
    {
        $params = array_splice(func_get_args(), 1, 1);

        return call_user_func_array(array(self::app($websiteId), 'getModel'), $params);
    }

    /**
     * @deprecated
     *
     * @param unknown_type $identifier
     * @param unknown_type $websiteId
     * @param unknown_type $params
     */
    public static function getSingleton($identifier, $websiteId = null, $params = null)
    {
        return self::app($websiteId)->getSingleton($identifier, $params);
    }

    /**
     * @deprecated
     *
     * @param unknown_type $identifier
     * @param unknown_type $websiteId
     * @param unknown_type $params
     */
    public static function getBlockSingleton($identifier, $websiteId = null, $params = null)
    {
        return self::app($websiteId)->getBlockSingleton($identifier, $params);
    }

    /**
     * @deprecated
     *
     * @param unknown_type $identifier
     * @param unknown_type $websiteId
     * @param unknown_type $message
     * @param unknown_type $_
     */
    public static function throwException($identifier, $websiteId, $message, $_ = null)
    {
        return self::app($websiteId)->throwException($identifier, vsprintf($message, array_slice(func_get_args(), 3)));
    }
}