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

/**
 * Database management class
 *
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class One_Core_Model_Database_Connection_Pool
    extends One_Core_Object
{
    /**
     * @var array
     *
     * @since 0.1.0
     */
    protected $_config = null;

    /**
     * @var array
     *
     * @since 0.1.0
     */
    protected $_entitiesConfig = null;

    /**
     * @var string
     *
     * @since 0.1.0
     */
    private $_moduleName = null;

    /**
     * @var array
     *
     * @since 0.1.0
     */
    protected $_connectionList = array();

    /**
     * Retrieves the database configuration
     *
     * @since 0.1.0
     *
     * @access public
     * @return array
     */
    public function getConfig($connectionName)
    {
        if ($this->_config === null) {
            $this->_config = $this->app()->getConfig('general/database/connection');
        }
        if (!isset($this->_config[$connectionName])) {
            return null;
        }
        return $this->_config[$connectionName];
    }

    /**
     * Updates the database configuration
     *
     * @since 0.1.0
     *
     * @access public
     * @return array
     * FIXME
     */
    public function setConfig($connectionName, $options)
    {/*
        if (is_null($this->_config)) {
            $this->getConfig($connectionName);
        }

        if (isset($this->_config[$connectionName])) {
            $this->_config[$connectionName] = array_merge(
                $this->_config[$connectionName], $options);
        }*/
        return $this;
    }

    /**
     * Retrieves the entities configuration
     *
     * @since 0.1.0
     *
     * @access public
     * @return array
     * FIXME
     */
    public function getEntitiesConfig($module = null)
    {
        if ($this->_entitiesConfig === null) {
            $this->_entitiesConfig = $this->app()->getConfig('resource/dal/entities');
        }
        if ($module === null) {
            return $this->_entitiesConfig;
        }
        if (!isset($this->_entitiesConfig[$module])) {
            return null;
        }
        return $this->_entitiesConfig[$module];
    }

    /**
     * Retrieves the main database instance, using Zend_Db's database adapters.
     *
     * @since 0.1.0
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getConnection($connectionName)
    {
        if (!isset($connectionName)) {
            $this->app()->throwException('core/invalid-method-call', 'Empty connection name.');
        }
        if (!isset($this->_connectionList[$connectionName])) {
            $connectionConfig = $this->app()
                ->getConfig("general/database/connection/{$connectionName}");
            if ($connectionConfig === null) {
                $this->app()->throwException('core/configuration-error', 'No such connection "%s"', $connectionName);
            }

            if (isset($connectionConfig['use'])) {
                $this->_connectionList[$connectionName] = $this->getConnection($connectionConfig['use']);
            } else {
                $connection = $this->app()
                    ->getResource($connectionConfig['engine'], 'dal/database', $connectionConfig['params'], $this->app());

                if ($connection === null) {
                    $this->app()
                        ->throwException('core/configuration-error', 'No such engine "%s"', $connectionConfig['engine'])
                    ;
                }
                $connection->query('SET NAMES "UTF8";');

                $this->_connectionList[$connectionName] = $connection;
            }
        }
        return $this->_connectionList[$connectionName];
    }

    public function getConnectionList()
    {
        return $this->_connectionList;
    }
}
