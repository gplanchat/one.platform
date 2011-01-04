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
 * Base model entity object management interface
 *
 * @uses        One_Core_ObjectInterface
 *
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
interface One_Core_Bo_CollectionInterface
{
    const FILTER_NOT      = 'NOT';
    const FILTER_AND      = Zend_Db_Select::SQL_AND;
    const FILTER_OR       = Zend_Db_Select::SQL_OR;
    const FILTER_IN       = 'IN';
    const FILTER_NOT_IN   = 'NIN';
    const FILTER_LIKE     = 'LIKE';
    const FILTER_NOT_LIKE = 'NLIKE';

    const FILTER_GREATER_THAN          = 'GTHAN';
    const FILTER_GREATER_THAN_OR_EQUAL = 'GTHANEQ';
    const FILTER_LOWER_THAN            = 'LTHAN';
    const FILTER_LOWER_THAN_OR_EQUAL   = 'LTHANEQ';

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
    public function load($identifiers = array());

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
    public function isLoaded($flag = null);

    /**
     * Get the saving status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isSaved($flag = null);

    /**
     * Get the deletion status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isDeleted($flag = null);

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
    public function setPage($page, $pageCount = null);

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
    public function toHash($field = null);

    /**
     * Adds an attribute filter
     *
     * @param string $attribute
     * @param mixed $params
     */
    public function addAttributeFilter($attribute, $params);

    /**
     * Adds multiple filters
     *
     * @see Zend_Db_Select
     * @uses Zend_Db_Select
     *
     * @param array $filter
     */
    public function addFilters(Array $filter);

    /**
     * Adds an expression filter. Beware of SQL expressions compatibility.
     *
     * @see Zend_Db_Expr
     * @uses Zend_Db_Expr
     *
     * @param Zend_Db_Expr $expression
     */
    public function addExpressionFilter(Zend_Db_Expr $expression);

    /**
     * Get all the filters
     *
     */
    public function getFilters();
}
