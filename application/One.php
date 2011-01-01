<?php
/**
 * This file is part of One.Platform
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Zend Technologies USA, Inc. nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */
defined('APPLICATION_PATH') ||
    ($env = getenv('APPLICATION_PATH')) ? define('APPLICATION_PATH', $env) :
        define('APPLICATION_PATH', ROOT_PATH . DS. 'application');

/**
 * One.Platform Hub class
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */

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
     * @var Zend_Controller_Response_Abstract
     */
    private static $_defaultResponseObject = null;

    /**
     * @var Zend_Controller_Request_Abstract
     */
    private static $_defaultRequestObject = null;

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

    public static function getDefaultResponseObject()
    {
        if (self::$_defaultResponseObject === null) {
            self::$_defaultResponseObject = new Zend_Controller_Response_Http();
        }
        return clone self::$_defaultResponseObject;
    }

    public static function setDefaultResponseObject(Zend_Controller_Response_Abstract $response)
    {
        $oldObject = self::$_defaultResponseObject;
        self::$_defaultResponseObject = $response;

        return $oldObject;
    }

    public static function getDefaultRequestObject()
    {
        if (self::$_defaultRequestObject === null) {
            self::$_defaultRequestObject = new Zend_Controller_Request_Http();
        }
        return clone self::$_defaultRequestObject;
    }

    public static function setDefaultRequestObject(Zend_Controller_Request_Abstract $request)
    {
        $oldObject = self::$_defaultRequestObject;
        self::$_defaultRequestObject = $request;

        return $oldObject;
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

    /**
     *
     * @param mixed $websiteId
     */
    public static function terminateApp($websiteId)
    {
        if (!isset(self::$_app[$websiteId])) {
            return false;
        }
        unset(self::$_app[$websiteId]);
        return true;
    }

    /**
     *
     * @param string $identifier
     * @param string $message
     * @param mixed $...
     */
    public static function throwException($identifier, $message = null, $_ = null)
    {
        $app = self::app();
        $reflectionObject = new ReflectionObject($app);
        $reflectionMethod = $reflectionObject->getMethod('throwException');

        $args = func_get_args();
        $reflectionMethod->invokeArgs($app, $args);
    }
}