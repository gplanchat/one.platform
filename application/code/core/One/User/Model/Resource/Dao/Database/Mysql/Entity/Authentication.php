<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
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
class One_User_Model_Resource_Dao_Database_Mysql_Entity_Authentication
    extends One_Core_Dao_Database_Table
{
    public function _construct()
    {
        $this->_init('user/entity.authentication', 'user/authentication', 'user_entity_id');
    }

    public function _prepareSelect($select)
    {
        parent::_prepareSelect($select);

        $select->joinLeft(
            array('user_entity' => $this->getTableName('user/entity')),
            "entity.{$this->getReadConnection()->quoteIdentifier($this->getIdFieldName())} = user_entity.entity_id",
            array(
                'identity' => 'username',
                'user_id' => 'entity_id'
                ));

        return $this;
    }
}