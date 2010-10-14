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
 * Database management class
 *
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Database
 * @package     One
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
    protected $_config = NULL;

    /**
     * @var array
     *
     * @since 0.1.0
     */
    protected $_entitiesConfig = NULL;

    /**
     * @var string
     *
     * @since 0.1.0
     */
    private $_moduleName = NULL;

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
            $this->_config = $this->app()->getConfig('general.database.connection');
        }
        var_dump($this->_config);
        die();
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
    public function getEntitiesConfig()
    {/*
        if (is_null($this->_entitiesConfig)) {
            $this->getConfig();
        }*/
        return $this->_entitiesConfig;
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
                ->getConfig("general.database.connection.{$connectionName}");
            if ($connectionConfig === null) {
                $this->app()->throwException('core/configuration-error', 'No such connection "%s"', $connectionName);
            }

            if (isset($connectionConfig['use'])) {
                $this->_connectionList[$connectionName] = $this->getConnection($connectionConfig['use']);
            } else {
                $this->_connectionList[$connectionName] = $this->app()
                    ->getResource($connectionConfig['engine'], 'dal.database', $connectionConfig['params'], $this->app());
            }
        }
        return $this->_connectionList[$connectionName];
    }
}
