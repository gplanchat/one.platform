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
     * @var int
     */
    private static $_defaultWebsiteId = null;

    /**
     * @var Zend_Config
     */
    private static $_websitesConfig = null;

    /**
     * @return array
     */
    protected static function _loadWebsitesConfig()
    {
        if (self::$_websitesConfig === null) {
            $config = new Zend_Config_Xml(APPLICATION_PATH . self::DS
                . 'configs' . self::DS . 'websites.xml');

            self::$_websitesConfig = $config->toArray();
        }

        return self::$_websitesConfig;
    }

    /**
     *
     * @param mixed $websiteId
     * @return array
     */
    public static function getWebstiteConfig($websiteId = null)
    {
        $config = self::_loadWebsitesConfig();

        if (isset($config[$websiteId])) {
            return $config[$websiteId];
        } else {
            return current($config);
        }
    }

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
     * @param mixed $websiteId
     * @return string
     */
    public static function getEnv($websiteId = null)
    {
        if ($websiteId !== null) {
            $config = self::_loadWebsitesConfig();

            if (isset($config[$websiteId]) && isset($config[$websiteId]['env'])) {
                return $config[$websiteId]['env'];
            }
        }

        if (self::$_env === null) {
            self::$_env = self::ENV_PRODUCTION;
        }

        return self::$_env;
    }

    /**
     *
     * @param mixed $websiteId
     * @return void
     */
    public static function setDefaultWebsiteId($websiteId)
    {
        self::$_defaultWebsiteId = $websiteId;
    }

    /**
     * @return mixed
     */
    public static function getDefaultWebsiteId()
    {
        if (self::$_defaultWebsiteId === null) {
            $config = self::_loadWebsitesConfig();

            self::$_defaultWebsiteId = key($config);
        }

        return self::$_defaultWebsiteId;
    }

    /**
     * Get the current application instance
     *
     * @param mixed $websiteId
     * @return Zend_Application
     */
    public static function app($websiteId = null, $environment = null, $moreSections = array())
    {
        if ($websiteId === null) {
            $websiteId = self::getDefaultWebsiteId();
        }

        if (isset(self::$_app[$websiteId]) && self::$_app[$websiteId] instanceof Zend_Application) {
            return self::$_app[$websiteId];
        }

        if ($environment === null) {
            $environment = self::getEnv($websiteId);
        }

        require_once 'One/Core/Model/Application.php';
        self::$_app[$websiteId] = new One_Core_Model_Application($websiteId,
            $environment, (array) $moreSections, self::getWebstiteConfig($websiteId));

        $modules = self::$_app[$websiteId]->getOption('modules');
        self::$_app[$websiteId]->setAutoloaderNamespaces(array_keys($modules));

        return self::$_app[$websiteId];
    }

    public static function throwException($identifier, $message = null, $_ = null)
    {
        $app = self::app();
        $reflectionObject = new ReflectionObject($app);
        $reflectionMethod = $reflectionObject->getMethod('throwException');

        $args = func_get_args();
        $reflectionMethod->invokeArgs($app, $args);
    }
}