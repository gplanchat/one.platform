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
class One_Core_Dal_Database_Connection_Adapter_Pdo_Mysql
    extends Zend_Db_Adapter_Pdo_Mysql
    implements One_Core_Dal_Database_Connection_AdapterInterface
{
    /**
     * TODO: PHPDoc
     *
     * @since 0.1.0
     *
     * @var unknown_type
     */
    protected $_tablePrefix = '';

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.0
     *
     * @var unknown_type
     */
    protected $_entitiesConfig = array();

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.0
     *
     * @var string
     */
    protected $_connectionName = null;

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.0
     *
     * @var One_Core_Model_Application
     */
    protected $_app = null;

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.0
     *
     * @param array|Zend_Config $config
     * @param One_Core_Model_Application $app
     * @return void
     */
    public function __construct($config, One_Core_Model_Application $app)
    {
        parent::__construct($config);

        $this->_app = $app;

        if (isset($config['table-prefix'])) {
            $this->_tablePrefix = $config['table-prefix'];
        }
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.0
     *
     * @return One_Core_Model_Application
     */
    public function app()
    {
        return $this->_app;
    }

    /**
     * Sets up the connection's name
     *
     * @since 0.1.0
     *
     * @param string $connectionName
     * @return One_Database_Model_Connection
     */
    public function setConnectionName($connectionName)
    {
        $this->_connectionName = $connectionName;
        return $this;
    }

    /**
     * Get the connection name
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getConnectionName()
    {
        return $this->_connectionName;
    }

    /**
     * Loads the connection configuration
     *
     * @since 0.1.0
     *
     * @return array|Zend_Config
     */
    public function getTablePrefix()
    {
        if (is_null($this->_tablePrefix)) {
            $this->_tablePrefix = $this->_config['table-prefix'];
        }
        return $this->_tablePrefix;
    }

    /**
     * Loads the entities configuration
     *
     * @since 0.1.0
     *
     * @return array|Zend_Config
     */
    public function getEntitiesConfig($module = null)
    {
        return $this->app()
            ->getSingleton('core/database.connection.pool')
            ->getEntitiesConfig($module)
        ;
    }

    /**
     * Translates the table name from its identifier.
     * Format:
     *     module/entity
     *
     * @since 0.1.0
     *
     * @param string $table
     * @return string
     */
    public function getTable($table)
    {
        $module = substr($table, 0, $offset = strpos($table, '/'));
        $entity = substr($table, $offset + 1);

        $entityList = $this->getEntitiesConfig($module);

        if (!isset($entityList[$entity]) || !isset($entityList[$entity]['table'])) {
            return $this->getTablePrefix() . str_replace('.' , '_', $module) . '_' . str_replace('.' , '_', $entity);
        }

        return $this->getTablePrefix() . $entityList[$entity]['table'];
    }

    /**
     * Translates the deprecated table name from its base name.
     *
     * @since 0.1.0
     *
     * @param string $table
     * @return string
     */
    public function getDeprecatedTable($table)
    {
        return $this->getTablePrefix() . $table;
    }
}