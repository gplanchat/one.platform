<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * NOTICE:
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 * Applciation manager
 *
 *
 * @uses        Zend_Config
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Config
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Model_Application
    extends One_Core_Object
{
    private $_config            = NULL;
    private $_globalConfig      = NULL;
    private $_websitesConfig    = array();
    private $_gamesConfig       = array();
    private $_gameViewsConfig   = array();

    private $_cacheHandler = NULL;

    protected function _construct()
    {
        $frontendOptions = array(
            'caching'           => !defined('DEBUG') ? true : false,
            'cache_id_prefix'   => 'Config',
            'lifetime'          => NULL,
            'logging'           => defined('DEBUG') ? true : false,
            'write_control'     => true,
            'automatic_serialization' => true
            );

        $backendOptions = array(
            'cache_dir'                 => APPLICATION_PATH . DS . 'cache',
            'file_locking'              => true,
            'read_control'              => true,
            'read_control_type'         => 'adler32',
            'hashed_directory_level'    => 0,
            'hashed_directory_umask'    => 0700,
            'file_name_prefix'          => 'NovaCache',
            'cache_file_umask'          => 0600
            );

        $this->_cacheHandler = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
    }

    /**
     * @todo
     * @return unknown_type
     */
    protected function _mergeDatabaseConfig($websiteId = 0, $gameId = 0, $gameViewId = 0)
    {
        if (!$this->_globalConfig) {
            return $this;
        }
    }

    private function _importGlobalConfig()
    {
        $config = new Zend_Config_Xml(APPLICATION_PATH . 'config' . DS . 'system.xml', NULL, true);
        $config->merge(new Zend_Config_Xml(APPLICATION_PATH . 'config' . DS . 'local.xml', NULL, true));

        $path = APPLICATION_PATH . 'config' . DS . 'modules';
        $iterator = new RegexIterator(new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS), '#\.xml$#');
        foreach ($iterator as $file) {
            $config->merge(new Zend_Config_Xml($file->getPathname(), NULL, true));
        }

        if ($modules = $config->get('modules')) {
            foreach ($modules as $moduleName => $moduleConfig) {
                $codePool = $moduleConfig->get('codePool');
                if (!$codePool) {
                    $codePool = 'local';
                }
                $configPath = APPLICATION_PATH . 'code' . DS . $codePool . DS . str_replace('_', DS, $moduleName)
                     . DS . '_config' . DS . 'module.xml';
                $config->merge(new Zend_Config_Xml($configPath, NULL, true));
            }
        }
        $config->setReadOnly();
        return $config;
    }

    /**
     * Loads the config files, for various usages
     *
     * @access private
     * @return Zend_Config
     */
    public function getConfig($path = NULL)
    {
        Nova::profilerStart('APPLICATION.CONFIG');
        if (is_null($this->_config)) {
            Nova::profilerStart('APPLICATION.CONFIG.LOAD');
            $cacheId = 'General';

            if ($rawConfig = $this->_cacheHandler->load($cacheId)) {
                Nova::profilerStart('APPLICATION.CONFIG.LOAD.CACHED');
                $this->_config = new Zend_Config($rawConfig);
                Nova::profilerStop('APPLICATION.CONFIG.LOAD.CACHED');
            } else {
                Nova::profilerStart('APPLICATION.CONFIG.LOAD.UNCACHED');
                $this->_config = $this->_importGlobalConfig();

                $this->_cacheHandler->save($this->_config->toArray(), $cacheId, array('configuration'));
                Nova::profilerStop('APPLICATION.CONFIG.LOAD.UNCACHED');
            }
            Nova::profilerStop('APPLICATION.CONFIG.LOAD');
        }
        Nova::profilerStop('APPLICATION.CONFIG');

        if (!is_null($path)) {
            $pathExploded = explode('.', $path);
            if (count($pathExploded)) {
                $config = $this->_config;
                foreach ($pathExploded as $chunk) {
                    $config = $config->get($chunk, NULL);
                    if (is_null($config)) {
                        return NULL;
                    }
                }
                if ($config instanceof Zend_Config) {
                    return $config->toArray();
                } else {
                    return $config;
                }
            }
            return NULL;
        } else {
            return $this->_config->toArray();
        }
    }

    /**
     * Returns the modules configuration
     *
     * @return Zend_Config
     */
    public function getModulesConfig()
    {
        return $this->getConfig('modules');
    }

    /**
     * Loads global configuration
     *
     * @return Zend_Config
     * @todo
     */
    public function getGlobalConfig()
    {
        if (is_null($this->_globalConfig)) {
            $cacheTag = 'CONFIGURATION_GLOBAL';

            if ($this->_cacheExists($cacheTag)) {
                Nova::profilerStart('APPLICATION.CONFIG.GLOBAL.LOAD.CACHE ' . __METHOD__);
                $this->_globalConfig = $this->_loadConfigFromCache($cacheTag);
                Nova::profilerStop('APPLICATION.CONFIG.GLOBAL.LOAD.CACHE ' . __METHOD__);
            } else {
                Nova::profilerStart('APPLICATION.CONFIG.GLOBAL.LOAD ' . __METHOD__);
                $config = $this->getConfig();
                $this->_globalConfig = new Zend_Config(array(), true);
                $this->_globalConfig->merge(new Zend_Config($config->get('default'), true));
                $this->_globalConfig->merge(new Zend_Config($config->get('global'), true));

                $stagesConfig = $config->get('global');
                if ($stagesConfig) {
                    $this->_globalConfig->merge(new Zend_Config($stagesConfig->get($this->getStage()), true));
                }

                $this->_mergeDatabaseConfig();

                Nova::profilerStart('APPLICATION.CONFIG.GLOBAL.SAVE.CACHE ' . __METHOD__);
                $this->_saveConfigToCache($this->_globalConfig, $cacheTag);
                Nova::profilerStop('APPLICATION.CONFIG.GLOBAL.SAVE.CACHE ' . __METHOD__);
                $this->_globalConfig->setReadOnly();
                Nova::profilerStop('APPLICATION.CONFIG.GLOBAL.LOAD ' . __METHOD__);
            }
        }
        return $this->_globalConfig;
    }

    /**
     * Loads website configuration
     *
     * @return Zend_Config
     * @todo
     */
    public function getWebsiteConfig($websiteId = NULL)
    {
        if (is_null($websiteId)) {
            $websiteId = $this->getWebsiteId();
        }

        if (is_null($this->_websiteConfig)) {
            $cacheTag = 'CONFIGURATION_WEBSITE_' . strval($websiteId) . '.cache';

            if ($this->_cacheExists($cacheFile)) {
                Nova::profilerStart("APPLICATION.CONFIG.WEBSITE({$websiteId}).LOAD.CACHE " . __METHOD__);
                $this->_websiteConfig = $this->_loadConfigFromCache($cacheTag);
                Nova::profilerStop("APPLICATION.CONFIG.GLOBAL.LOAD.CACHE " . __METHOD__);
            } else {
                Nova::profilerStart("APPLICATION.CONFIG.WEBSITE({$websiteId}).GLOBAL.LOAD " . __METHOD__);
                $this->_websiteConfig = $this->getConfig();

                $stageConfig = $this->_websiteConfig->get($this->getStage());
                if (!is_null($stageConfig)) {
                    $this->_websiteConfig->merge($stageConfig);
                }

                $this->_mergeDatabaseConfig();

                Nova::profilerStart("APPLICATION.CONFIG.WEBSITE({$websiteId}).GLOBAL.SAVE.CACHE " . __METHOD__);
                $this->_saveConfigToCache($this->_websiteConfig, $cacheTag);
                Nova::profilerStop("APPLICATION.CONFIG.WEBSITE({$websiteId}).GLOBAL.SAVE.CACHE " . __METHOD__);
                $this->_websiteConfig->setReadOnly();
                Nova::profilerStop("APPLICATION.CONFIG.WEBSITE({$websiteId}).GLOBAL.LOAD " . __METHOD__);
            }
        }
        return $this->_websiteConfig;
    }

    /**
     * Loads game configuration
     *
     * @return Zend_Config
     * @todo
     */
    public function getGameConfig($gameId = NULL)
    {
    }

    /**
     * Loads website view configuration
     *
     * @return Zend_Config
     * @todo
     */
    public function getGameViewConfig($gameViewId = NULL)
    {
    }

    /**
     * FIXME: PHPDoc
     */
    public function getStage()
    {
        return defined('DEBUG') ? 'debug' : 'production';
    }

    /**
     * FIXME: PHPDoc
     */
    public function bootstrap()
    {
        return $this;
    }

    /**
     * FIXME: PHPDoc
     */
    public function run()
    {
        return $this;
    }
}
