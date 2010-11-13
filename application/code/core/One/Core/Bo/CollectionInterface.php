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
 * @uses        One_Core_ObjectInterface
 *
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Dal
 * @package     One
 * @subpackage  One_Core
 */
interface One_Core_Bo_CollectionInterface
{
    /**
     * Get the entity identifier
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getAllIds();

    /**
     * Set the primary field name
     *
     * @since 0.1.0
     *
     * @param string $fieldName
     * @return One_Core_Model_EntityAbstract
     */
    public function setIdFieldName($fieldName);

    /**
     * Get the primary field name
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getIdFieldName();

    /**
     * Load an entity, based on its identifier
     *
     * @since 0.1.0
     *
     * @param mixed $identifier
     * @return One_Core_Model_EntityAbstract
     */
    public function load($identifiers);

    /**
     * Save the model into its static representation
     *
     * @param mixed $identifier
     * @return One_Core_Model_EntityAbstract
     */
    public function save();

    /**
     * Delete the entity
     *
     * @since 0.1.0
     *
     * @param mixed $identifier
     * @return One_Core_Model_EntityAbstract
     */
    public function delete();

    /**
     * Get the loading status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isLoaded($flag = NULL);

    /**
     * Get the saving status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isSaved($flag = NULL);

    /**
     * Get the deletion status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isDeleted($flag = NULL);

    /**
     * Create a new collection item instance and add it to the collection
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function newItem(Array $data);

    /**
     * Add an item instance to the collection
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function addItem($item);


    /**
     * Set the page limits
     *
     * @since 0.1.4
     *
     * @param int $page
     * @param int $pageCount
     * @return One_Core_Bo_CollectionInterface
     */
    public function setPage($page, $pageCount);

    /**
     * Set the page limits
     *
     * @since 0.1.4
     *
     * @param array $fields
     * @return One_Core_Bo_CollectionInterface
     */
    public function sort($fields);

    /**
     * Return a key=>value data set
     *
     * @since 0.1.4
     *
     * @param array $field
     * @return array
     */
    public function toHash($field);
}
