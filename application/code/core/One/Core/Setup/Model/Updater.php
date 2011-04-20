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
 *     - Neither the name of Grégory PLANCHAT nor the names of its
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
 * Install and updates utility class
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Setup
 * @package     One_Core
 * @subpackage  One_Core_Setup
 */
class One_Core_Setup_Model_Updater
    extends One_Core_ResourceAbstract
{
    /**
     * Current database adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $_databaseAdapter = null;

    private $_currentModule = null;

    public function setup($module, $dialect = 'mysql5')
    {
        $this->_currentModule = $module;

        $moduleSetup = $this->app()
            ->getModel('setup/module')
        ;
        try {
            $moduleSetup->load($module, 'identifier');
        } catch (One_Core_Exception_Dao_ReadError $e) {
            $moduleSetup
                ->setIdentifier($module)
                ->setVersion('0.0.0')
                ->setStage(One_Core_Setup_Model_Updater_ScriptQueue::STAGE_STABLE)
            ;
        }

        $modulesConfig = $this->app()->getConfig('modules');
        if (!isset($modulesConfig[$module])) {
            return $this;
        }
        $codePool = 'local';
        if (isset($modulesConfig[$module]['codePool'])) {
            $codePool = $modulesConfig[$module]['codePool'];
        }
        $fromVersion = $moduleSetup->getData('version');
        if ($moduleSetup->hasData('stage') && $moduleSetup->getData('stage') !== One_Core_Setup_Model_Updater_ScriptQueue::STAGE_STABLE) {
            $fromVersion .= '-' . $moduleSetup->getData('stage');
        }
        $toVersion = $modulesConfig[$module]['version'];
        if (isset($modulesConfig[$module]['stage']) && $modulesConfig[$module]['stage'] !== One_Core_Setup_Model_Updater_ScriptQueue::STAGE_STABLE) {
            $toVersion .= '-' . $modulesConfig[$module]['stage'];
        }

        $path = implode(DS, array(APPLICATION_PATH, 'code', $codePool, str_replace('_', DS, $module), 'install', $dialect));

        $scriptQueue = $this->app()
            ->getModel('setup/updater.script-queue', $path, $fromVersion, $toVersion)
        ;

        $finalVersion = null;
        foreach ($scriptQueue as $installer) {
            try {
                $this->_run($installer['script']);
                $finalVersion = $installer['version'];
            } catch (Exception $e) {
                break;
            }
        }

        try {
            if ($finalVersion !== null) {
                $moduleSetup
                    ->setIdentifier($module)
                    ->setData('version', $finalVersion['version'])
                    ->setData('stage', $finalVersion['stage'] . $finalVersion['level'])
                    ->save()
                ;
            } else {
                $moduleSetup
                    ->setIdentifier($module)
                    ->setVersion('0.0.0')
                    ->setStage(One_Core_Setup_Model_Updater_ScriptQueue::STAGE_STABLE)
                    ->save()
                ;
            }
        } catch (One_Core_Exception $e) {
        }

        return $this;
    }

    protected function _run($script)
    {
        include $script;
    }

    /**
     * @return One_Core_Setup_Model_Module_Collection
     */
    public function getInstalledModuleCollection()
    {
        return $this->app()
            ->getModel('setup/module.collection')
            ->load()
        ;
    }

    /**
     * @return One_Core_Setup_Model_Module_Collection
     */
    public function getApplicationModuleCollection($applicationId)
    {
        $collection = $this->app()
            ->getModel('setup/module.collection')
        ;

        $applicationConfig = $this->app($applicationId)->getConfig('modules');

        foreach ($applicationConfig as $module => $version) {
            $collection->newItem(array(
                $collection->getIdFieldName() => null,
                'identifier' => $module,
                'version'    => isset($version['version']) ? $version['version'] : One_Core_Setup_Model_Updater_ScriptQueue::VERSION_NULL,
                'stage'      => isset($version['stage']) ? $version['version'] : One_Core_Setup_Model_Updater_ScriptQueue::STAGE_STABLE
                ));
        }

        return $collection;
    }

    /**
     * FIXME: Get the actual setup connection
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getModuleSetupConnection($module)
    {
        if ($module === null || empty($module)) {
            return null;
        }

        return $this->app()
            ->getSingleton('core/database.connection.pool')
            ->getConnection('core_setup')
        ;
    }

    /**
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getConnection($connectionName)
    {
        return $this->app()
            ->getSingleton('core/database.connection.pool')
            ->getConnection($connectionName)
        ;
    }

    /**
     * Prepares and executes an SQL statement with bound data.
     *
     * @param  mixed  $sql  The SQL statement with placeholders.
     *                      May be a string or Zend_Db_Select.
     * @param  mixed  $bind An array of data to bind to the placeholders.
     * @return Zend_Db_Statement_Interface
     */
    public function query($sql, $bind = array())
    {
        return $this->getModuleSetupConnection($this->_currentModule)->query($sql, $bind);
    }

    /**
     * Prepares and executes an SQL statement with bound data.
     *
     * @param  mixed  $sql  The SQL statement with placeholders.
     *                      May be a string or Zend_Db_Select.
     * @param  mixed  $bind An array of data to bind to the placeholders.
     * @return int
     */
    public function execute($sql)
    {
        return $this->getModuleSetupConnection($this->_currentModule)->execute($sql);
    }

    /**
     * Creates and returns a new Zend_Db_Select object for this adapter.
     *
     * @return Zend_Db_Select
     */
    public function select()
    {
        return $this->getModuleSetupConnection($this->_currentModule)->select();
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param mixed $table The table to insert data into.
     * @param array $bind Column-value pairs.
     * @return int The number of affected rows.
     */
    public function insert($table, array $bind)
    {
        return $this->getModuleSetupConnection($this->_currentModule)->insert($table, $bind);
    }

    /**
     * Gets the last insert ID.
     *
     * @param $tableName The table name
     * @param $primaryKey The primary key to use
     * @return int The last insert ID.
     */
    public function lastInsertId($tableName = null, $primaryKey = null)
    {
        return $this->getModuleSetupConnection($this->_currentModule)->lastInsertId($tableName, $primaryKey);
    }

    /**
     * Updates table rows with specified data based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  array        $bind  Column-value pairs.
     * @param  mixed        $where UPDATE WHERE clause(s).
     * @return int          The number of affected rows.
     */
    public function update($table, array $bind, $where = '')
    {
        return $this->getModuleSetupConnection($this->_currentModule)->update($table, $bind, $where);
    }

    /**
     * Deletes table rows based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  mixed        $where DELETE WHERE clause(s).
     * @return int          The number of affected rows.
     */
    public function delete($table, $where = '')
    {
        return $this->getModuleSetupConnection($this->_currentModule)->delete($table, $where);
    }

    /**
     * Get a table name from its entity identifier
     *
     * @param string $entityIdentifier
     * @return string
     */
    public function getTableName($entityIdentifier, $quoted = true)
    {
        $connection =  $this->getSetupConnection();
        if ($quoted) {
            return $connection->quoteIdentifier($connection->getTable($entityIdentifier));
        }
        return $connection->getTable($entityIdentifier);
    }

    /**
     * FIXME: Should be integrated into the adapter engine
     *
     * @param string $entityTable
     * @param string $connectionName
     * @param array $options
     */
    public function grant($entityTable, $connectionName, $options = null)
    {
        if ($options === null) {
            $optionsString = 'ALL PRIVILEGES';
        } else {
            foreach ($options as &$option) {
                $option = strtoupper($option);
            }
            unset($option);
            $optionsString = implode(', ', $options);
        }

        $connection = $this->getConnection($connectionName);
        $config = $connection->getConfig();

        $sql = "GRANT {$optionsString}" . "\n"
            . "ON {$connection->quoteIdentifier($config['dbname'])}.{$this->getTableName($entityTable)} " . "\n"
            . "TO '{$config['username']}'@'{$config['host']}'";

        if ($config['password'] !== null && !empty($config['password'])) {
            $sql .= " IDENTIFIED BY '{$config['password']}'";
        }

        $this->query($sql);

        return $this;
    }
}
