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
 * FIXME PHPDoc
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
interface One_Core_Dao_ResourceInterface
{
    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_EntityInterface $model
     * @param One_Core_Orm_DataMapper $mapper
     * @param unknown_type $attributes
     * @return One_Core_Dao_ResourceInterface
     */
    public function loadEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper, Array $attributes);

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_EntityInterface $model
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Dao_ResourceInterface
     */
    public function saveEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper);

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_EntityInterface $model
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Dao_ResourceInterface
     */
    public function deleteEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper);

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_CollectionInterface $collection
     * @param One_Core_Orm_DataMapper $mapper
     * @param unknown_type $attributes
     * @return One_Core_Dao_ResourceInterface
     */
    public function loadCollection(One_Core_Bo_CollectionInterface $collection, One_Core_Orm_DataMapperAbstract $mapper, Array $ids);

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_CollectionInterface $collection
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Dao_ResourceInterface
     */
    public function saveCollection(One_Core_Bo_CollectionInterface $collection, One_Core_Orm_DataMapperAbstract $mapper);

    /**
     * FIXME PHPDoc
     *
     * @param One_Core_Bo_CollectionInterface $collection
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Dao_ResourceInterface
     */
    public function deleteCollection(One_Core_Bo_CollectionInterface $collection, One_Core_Orm_DataMapperAbstract $mapper);

    /**
     * FIXME PHPDoc
     *
     * @param int $limit
     * @return One_Core_Dao_ResourceInterface
     */
    public function setLimit($limit);

    /**
     * FIXME PHPDoc
     *
     * @param int $limit
     * @return One_Core_Dao_ResourceInterface
     */
    public function setOffset($offset);
}