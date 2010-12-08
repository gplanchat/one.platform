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
 * DAO driver using qn uniaue table for a BO model
 *
 * @uses        One_Core_ResourceAbstract
 * @uses        One_Core_Dao_ResourceInterface
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
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
     * @var int
     */
    protected $_limit = 1;

    /**
     * @var int
     */
    protected $_offset = 0;

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
        $select->from(array('entity' => $this->getTableName($this->getEntityTable())), '*');

        return $this;
    }

    public function loadEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper, Array $attributes)
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
        $statement = $this->getSelect()
            ->limit(1)
            ->query(Zend_Db::FETCH_ASSOC)
        ;

        if (($tableData = $statement->fetch()) !== false) {
            $mapper->load($model, $tableData);
        }

        return $this;
    }

    public function saveEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper)
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
                ->update($this->getTableName($this->getEntityTable()), $entityData, $whereCondition);
        }
        return $this;
    }

    public function deleteEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper)
    {
        if ($model->isLoaded()) {
            $whereCondition = $this->getWriteConnection()->quoteInto(
                "{$this->getWriteConnection()->quoteIdentifier($model->getIdFieldName())}=?",
                $model->getId()
                );

            $this->getWriteConnection()
                ->delete($this->getTableName($this->getEntityTable()), $whereCondition);
        }
        return $this;
    }

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_CollectionInterface $collection
     * @param One_Core_Orm_DataMapper $mapper
     * @param unknown_type $attributes
     * @return One_Core_Dao_Database_Table
     */
    public function loadCollection(One_Core_Bo_CollectionInterface $collection, One_Core_Orm_DataMapperAbstract $mapper, Array $attributes)
    {
        $select = $this->getSelect();

        if (!empty($attributes)) {
            $selectString = "{$this->getReadConnection()->quoteIdentifier($this->getIdFieldName())} IN(?)";
            $select->where($selectString, array_values($attributes));
        }

        $select->limit($this->_limit, $this->_offset);
        $statement = $select->query(Zend_Db::FETCH_ASSOC);

        foreach ($statement->fetchAll() as $row) {
            $item = $collection->newItem(array());
            $mapper->load($item, $row);
        }

        return $this;
    }

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_CollectionInterface $collection
     * @param One_Core_Orm_DataMapper $mapper
     * @param unknown_type $attributes
     * @return One_Core_Dao_Database_Table
     */
    public function countItems(One_Core_Bo_CollectionInterface $collection)
    {
        $select = clone $this->getSelect();
        $adapter = $select->getAdapter();

        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);
        $groupParts = $select->getPart(Zend_Db_Select::GROUP);
        if (!empty($groupParts)) {
            //FIXME: avoid grouping by entity_id while it is counted
        }

        $select->columns(new Zend_Db_Expr($adapter->quoteInto('COUNT(?)', $this->getIdFieldName())));

        return $select->query()->fetchColumn(0);
    }

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_CollectionInterface $collection
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Dao_Database_Table
     */
    public function saveCollection(One_Core_Bo_CollectionInterface $collection, One_Core_Orm_DataMapperAbstract $mapper)
    {
        foreach ($collection as $item) {
            $this->saveEntity($item, $mapper);
        }

        return $this;
    }

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_CollectionInterface $collection
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Dao_Database_Table
     */
    public function deleteCollection(One_Core_Bo_CollectionInterface $collection, One_Core_Orm_DataMapperAbstract $mapper)
    {
        foreach ($collection as $item) {
            $this->deleteEntity($item, $mapper);
        }

        return $this;
    }

    /**
     * FIXME PHPDoc
     *
     * @param int $limit
     * @return One_Core_Dao_Database_Table
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;

        return $this;
    }

    /**
     * FIXME PHPDoc
     *
     * @param int $limit
     * @return One_Core_Dao_Database_Table
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;

        return $this;
    }
}