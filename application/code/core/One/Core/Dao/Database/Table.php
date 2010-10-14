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
 * Base model entity object management interface
 *
 * @uses        Zend_Db_Table
 *
 * @access      public
 * @author      gplanchat
 * @category    Dal
 * @package     One
 * @subpackage  One_Core
 */
abstract class One_Core_Dao_Database_Table
    extends One_Core_ResourceAbstract
    implements One_Core_Dao_ResourceInterface
{
    const DEFAULT_ID_FIELD_NAME = 'entity_id';

    /**
     * @var One_Core_Model_Database_Connection_AdapterInterface
     */
    private $_readConnection  = NULL;

    /**
     * @var One_Core_Model_Database_Connection_AdapterInterface
     */
    private $_writeConnection = NULL;

    /**
     * @var One_Core_Model_Database_Connection_AdapterInterface
     */
    private $_setupConnection = NULL;

    /**
     * Zend_Db_Table instance
     *
     * @var array
     */
    protected $_tableDefinition = NULL;

    /**
     * Zend_Db_Table instance
     *
     * @var Zend_Db_Select
     */
    protected $_select = NULL;

    /**
     * @var string
     */
    private $_entityTable = NULL;

    /**
     * @var string
     */
    private $_idFieldName = null;

    /**
     * _init method, should be called by the user-defined constructor
     *
     * @return One_Core_Dao_Database_Table
     */
    protected function _init($modelClass, $entityTable, $idFieldName = self::DEFAULT_ID_FIELD_NAME)
    {
        $this->setModelClass($modelClass);
        $this->setEntityTable($entityTable);
        $this->setIdFieldName($idFieldName);

        return $this;
    }

    /**
     * @return One_Core_Model_Database_Connection_AdapterInterface
     */
    public function getReadConnection()
    {
        if (is_null($this->_readConnection)) {
            $this->_readConnection = $this->app()->getSingleton('core/database.connection.pool')
                ->getConnection($this->getConfig("resource.dal.database.{$this->getModuleName()}.connection.read"));
        }
        return $this->_readConnection;
    }

    /**
     * @return One_Core_Model_Database_Connection_AdapterInterface
     */
    public function getWriteConnection()
    {
        if (is_null($this->_writeConnection)) {
            $this->_writeConnection = $this->app()->getSingleton('core/database.connection.pool')
                ->getConnection($this->getConfig("resource.dal.database.{$this->getModuleName()}.connection.write"));
        }
        return $this->_writeConnection;
    }

    /**
     * @return One_Core_Model_Database_Connection_AdapterInterface
     */
    public function getSetupConnection()
    {
        if (is_null($this->_setupConnection)) {
            $this->_setupConnection = $this->app()->getSingleton('core/database.connection.pool')
                ->getConnection($this->getConfig("resource.dal.database.{$this->getModuleName()}.connection.setup"));
        }
        return $this->_setupConnection;
    }

    /**
     * (non-PHPdoc)
     * @see application/code/core/Nova/Core/One_Core_ResourceInterface#getConfig($path)
     *
     * FIXME
     */
    public function getConfig($path = NULL)
    {
        return $this->app()->getConfig($path);
    }

    /**
     *
     * FIXME
     *
     * @param string $entityIdentifier
     * @return string
     */
    public function getTableName($entityIdentifier)
    {
        return $this->getReadConnection()->getTable($entityIdentifier);
    }

    /**
     *
     * @return string
     */
    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    /**
     *
     * @var string $idFieldName
     * @return One_Core_Model_Database_ResourceAbstract
     */
    public function setIdFieldName($idFieldName)
    {
        $this->_idFieldName = $idFieldName;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getEntityTable()
    {
        return $this->_entityTable;
    }

    /**
     *
     * @var string $entityTable
     * @return One_Core_Model_Database_ResourceAbstract
     */
    public function setEntityTable($entityTable)
    {
        $this->_entityTable = $entityTable;

        return $this;
    }

    /**
     *
     * @return Zend_Db_Select
     */
    public function getSelect()
    {
        if (is_null($this->_select)) {
            $this->_select = $this->getReadConnection()->select();

            $this->_prepareSelect($this->_select);
        }
        return $this->_select;
    }

    /**
     * @param Zend_Db_Select $select
     * @return One_Core_Model_Database_ResourceAbstract
     */
    protected function _prepareSelect($select)
    {
        $select->from(array('entity' => $this->getTableName($this->getEntityTable())), '*')
            ->limit(1);

        return $this;
    }

    public function load(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper, Array $attributes)
    {
        if (is_int(key($attributes))) {
            $newAttributes = array();
            $i = 0;
            foreach ($this->getIdFieldName() as $fieldNames) {
                if (!isset($attributes[$i])) {
                    continue;
                }
                $newAttributes[$fieldNames] = $attributes[$i++];
            }
            $attributes = $newAttributes;
            unset($newAttributes);
        }

        foreach ($attributes as $field => $identity){
            $this->getSelect()
                ->where("{$this->getReadConnection()->quoteIdentifier($field)} = ?", $identity)
            ;
        }
        $statement = $this->getSelect()->query(Zend_Db::FETCH_ASSOC);

        if (($tableData = $statement->fetch()) !== false) {
            $mapper->load($model, $tableData);
        }

        return $this;
    }

    public function save(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper)
    {
        $entityData = $mapper->save($model);

        if (!$model->isLoaded()) {
            $entityData[$model->getIdFieldName()] = NULL;

            $id = $this->getWriteConnection()
                ->insert($this->getTableName($this->getEntityTable()), $entityData);

            $model->setId($id);
        } else {
            $whereCondition = $this->getWriteConnection()->quoteInto(
                "{$this->getWriteConnection()->quoteIdentifier($model->getIdFieldName())}=?",
                $model->getId()
                );

            $this->getWriteConnection()
                ->update($this->getTableName($this->getEntityTable()), $entityData);
        }
        return $this;
    }

    public function delete(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper)
    {
        return $this;
    }
}