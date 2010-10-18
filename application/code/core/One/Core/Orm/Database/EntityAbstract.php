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
 * Base resource object management interface
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One
 * @subpackage  One_Core
 */
abstract class One_Core_Orm_Database_EntityAbstract
    extends One_Core_Orm_EntityAbstract
{
    /**
     * FIXME: PHPDoc
     *
     * @var string
     */
    private $_entityTable = NULL;

    /**
     * FIXME: PHPDoc
     *
     * @var string
     */
    private $_primaryKey = array();

    /**
     * FIXME: PHPDoc
     *
     * @return string
     */
    public function getEntityTable()
    {
        return $this->_entityTable;
    }

    /**
     * FIXME: PHPDoc
     *
     * @param string
     * @return One_Core_Orm_EntityAbstract
     */
    public function setEntityTable($entityTable)
    {
        $this->_entityTable = $entityTable;

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @param string
     * @return One_Core_Orm_EntityAbstract
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * FIXME: PHPDoc
     *
     * @param string|One_Core_Orm_DataMapper
     * @return One_Core_Orm_EntityAbstract
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->_primaryKey = $primaryKey;

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @param One_Core_Model_EntityAbstract $entity
     * @param unknown_type $identifier
     * @param unknown_type $field
     * @return One_Core_Orm_EntityAbstract
     */
    final protected function _load($entity, $identifier, $field)
    {
    }

    /**
     * FIXME: PHPDoc
     *
     * @return Zend_Db_Select
     */
    public function getSelect()
    {
        $this->getReadAdapter()->select();
    }
}