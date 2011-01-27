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

        if (!$model->isLoaded() || $model->getId() === null) {
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
    public function loadCollection(One_Core_Bo_CollectionInterface $collection, One_Core_Orm_DataMapperAbstract $mapper, Array $ids)
    {
        $select = $this->getSelect();

        if (!empty($ids)) {
            $selectString = "{$this->getReadConnection()->quoteIdentifier($this->getIdFieldName())} IN(?)";
            $select->where($selectString, array_values($ids));
        }

        $filters = $collection->getFilters();
        if (!empty($filters)) {
            $this->_buildFilter($select, $filters);
        }

        if ($this->_limit !== null) {
            $select->limit($this->_limit, $this->_offset);
        }
        $statement = $select->query(Zend_Db::FETCH_ASSOC);

        foreach ($statement->fetchAll() as $row) {
            $item = $collection->newItem(array());
            $mapper->load($item, $row);
        }

        return $this;
    }

    /**
     *
     * @param Zend_Db_Select $select
     * @param array $filters
     */
    private function _buildFilter(Zend_Db_Select $select, Array $filters)
    {
        foreach ($filters as $attribute => $expression) {
            if ($attribute === One_Core_Bo_CollectionInterface::FILTER_OR) {
                foreach ($expression as $clause) {
                    $subSelect = $this->_buildFilter($this->getReadConnection()->select(), $clause);
                    $select->orWhere(current($subSelect->getPart(Zend_Db_Select::WHERE)));
                }
            } else if ($attribute === One_Core_Bo_CollectionInterface::FILTER_AND) {
                foreach ($expression as $clause) {
                    $subSelect = $this->_buildFilter($this->getReadConnection()->select(), $clause);
                    $select->where(current($subSelect->getPart(Zend_Db_Select::WHERE)));
                }
            } else {
                $keyword = $attribute;
                if (is_array($expression)) {
                    $attribute = key($expression);
                    $expression = current($expression);

                    switch ($keyword) {
                    case One_Core_Bo_CollectionInterface::FILTER_NOT_LIKE:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} NOT LIKE ?", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_LIKE:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} LIKE ?", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_IN:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} IN(?)", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_NOT_IN:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} NOT IN(?)", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_GREATER_THAN:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} > ?", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_GREATER_THAN_OR_EQUAL:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} >= ?", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_LOWER_THAN:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} < ?", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_LOWER_THAN_OR_EQUAL:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} <= ?", $expression);
                        break;

                    case One_Core_Bo_CollectionInterface::FILTER_NOT:
                        $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} != ?", $expression);
                        break;
                    }
                } else if ($expression instanceof Zend_Db_Expr) {
                    $select->where($expression);
                } else {
                    $select->where("{$this->getReadConnection()->quoteIdentifier($attribute)} = ?", $expression);
                }
            }
        }
        return $select;
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

        $filters = $collection->getFilters();
        if (!empty($filters)) {
            $this->_buildFilter($select, $filters);
        }

        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);
        $groupParts = $select->getPart(Zend_Db_Select::GROUP);
        if (!empty($groupParts)) {
            //FIXME: avoid grouping by entity_id when it is counted
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