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
 * Base resource object management interface for database storage
 *
 * @access      public
 * @author      gplanchat
 * @category    Database
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Model_Database_ResourceAbstract
    extends One_Core_Object
    implements One_Core_ResourceInterface
{
    /**
     * FIXME: PHPDoc
     * @var string
     */
    private $_modelClass = NULL;

    /**
     *
     * @param string $modelClass
     * @return One_Core_Model_Database_ResourceAbstract
     */
    public function setModelClass($modelClass)
    {
        $this->_modelClass = $modelClass;

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->_modelClass;
    }

    /**
     * FIXME: PHPDoc
     *
     * (non-PHPdoc)
     * @see application/code/core/Nova/Core/One_Core_ResourceInterface#getConfig($path)
     */
    public function getConfig($path = NULL)
    {
        return NULL;
    }

    /**
     *
     * @param One_Core_Model_Database_EntityAbstract $entity
     * @param $identifier
     * @param $field
     * @return One_Core_Model_Database_ResourceAbstract
     */
    public function load(One_Core_Model_Database_EntityAbstract $entity, $identifier, $field = NULL)
    {
        $this->_beforeLoad();

        $fieldName = $this->getReadConnection()->quoteIdentifier($this->getIdFieldName());

        $statement = $this->getSelect()
            ->where("$fieldName=?", $identifier)
            ->limit(1)
            ->query();

        $entity->setData($statement->fetch(Zend_Db::FETCH_ASSOC));

        $this->_afterLoad();

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @return One_Core_Model_Database_EntityAbstract
     */
    public function _beforeLoad()
    {
        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @return One_Core_Model_Database_EntityAbstract
     */
    public function _afterLoad()
    {
        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @param One_Core_Model_Database_EntityAbstract $entity
     * @param $identifier
     * @param $field
     * @return One_Core_Model_Database_ResourceAbstract
     */
    public function save(One_Core_Model_Database_EntityAbstract $entity)
    {
        $this->_beforeSave();
        if (!$entity->getId()) {
            $this->getWriteConnection()
                ->insert($this->getTableName($this->getEntityTable()), $entity->getData());
        } else {
            $where = array("{$this->getIdFieldName()}=?" => $entity->getId());

            $this->getWriteConnection()
                ->update($this->getTableName($this->getEntityTable()), $entity->getData(), $where);
        }
        $this->_afterSave();

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @return One_Core_Model_Database_EntityAbstract
     */
    public function _beforeSave()
    {
        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @return One_Core_Model_Database_EntityAbstract
     */
    public function _afterSave()
    {
        return $this;
    }
}
