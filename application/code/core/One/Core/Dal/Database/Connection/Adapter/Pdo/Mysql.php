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
 * Database management class for pdo_mysql driver
 *
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
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