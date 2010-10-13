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
 * @uses        Whitewashing_Db_Adapter_Mysql
 * @uses        One_Core_Model_Database_Connection_AdapterInterface
 *
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Database
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Dal_Database_Connection_Adapter_Mysql
    extends Whitewashing_Db_Adapter_Mysql
    implements One_Core_Dal_Database_Connection_AdapterInterface
{
    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @var unknown_type
     */
    protected $_tablePrefix = NULL;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @var unknown_type
     */
    static $_entitiesConfig = array();

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
            //FIXME: find a way to get the config from the object itself
//            $config = Nova::getSingleton('core/database.connection.pool')
//                ->getConfig($this->getConnectionName());

            $this->_tablePrefix = Nova::app()
                ->getConfig("global.resources.db.table_prefix");
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
    public function getEntitiesConfig()
    {
        //FIXME
        if (is_null(self::$_entitiesConfig)) {
            self::$_entitiesConfig = $this->getResourcePool()
                ->getEntitiesConfig();
        }
        return self::$_entitiesConfig;
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
        $config = $this->getConfig();
        $entities = $this->getEntitiesConfig();

        $module = substr($table, 0, $pos = strpos($table, '/'));
        $entity = substr($table, $pos + 1);

        if (!isset($entities[$module]) || !isset($entities[$module][$entity])) {
            return $this->getTablePrefix() . $module . '__' . $entity;
        }

        return $this->getTablePrefix() . $entities[$module][$entity];
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
        $config = $this->getConfig();

        return $this->getTablePrefix() . $table;
    }
}