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
     * @var string
     */
    private static $_basePath = '/';

    /**
     * @var string
     */
    private static $_domain = null;

    /**
     * @var int
     */
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