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
abstract class One_Core_Dao_FileSource
    extends One_Core_ResourceAbstract
    implements One_Core_Dao_ResourceInterface
{
    const DEFAULT_ID_FIELD_NAME = 'entity_id';

    protected $_localFile = null;

    protected $_data = array();

    /**
     * @var string
     */
    private $_idFieldName = null;

    /**
     * _init method, should be called by the user-defined constructor
     *
     * @return One_Core_Dao_Database_Table
     */
    protected function _init($modelClass, $localFile = null, $idFieldName = self::DEFAULT_ID_FIELD_NAME)
    {
        $this->setModelClass($modelClass);
        $this->setIdFieldName($idFieldName);
        $this->setLocalFile($localFile);

        $this->_data = include $localFile;

        return $this;
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

    public function setLocalFile($filename)
    {
        $this->_localFile = $filename;

        return $this;
    }

    public function getLocalFile()
    {
        return $this->_localFile;
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

    public function loadEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper, Array $attributes)
    {
        if (count($attributes) > 1) {
            $this->app()
                ->throwException('core/dao.read-error', 'FileSource DAO does not support multiple field ID.')
            ;
        }
        $id = current($attributes);

        var_dump($this->_data);
        return $this;
        if (!isset($this->_data[$id]) || !is_array($this->_data[$id])) {
            $this->app()
                ->throwException('core/dao.read-error', 'Could not load entity: %s', $id)
            ;
        }
        $entityData = array_merge(array($this->getIdFieldName() => $id), $this->_data[$id]);

        $mapper->load($model, $entityData);

        return $this;
    }

    public function saveEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper)
    {
        $entityData = $mapper->save($model);

        $id = $entityData[$this->getIdFieldName()];
        unset($entityData[$this->getIdFieldName()]);

        $localData = include $this->getLocalFile();
        $this->_data[$id] = $entityData;

        file_put_contents($this->getLocalFile(), '<?php return ' . var_export($this->_data, true) . ';');

        return $this;
    }

    public function deleteEntity(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper)
    {
        if (isset($this->_data[$model->getId()])) {
            unset($this->_data[$model->getId()]);
        }

        file_put_contents($this->getLocalFile(), '<?php return ' . var_export($this->_data, true) . ';');

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
        $this->app()
            ->throwException('core/unimplemented')
        ; // FIXME

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
        $this->app()
            ->throwException('core/unimplemented')
        ; // FIXME

        return false;
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
        $this->app()
            ->throwException('core/unimplemented')
        ; // FIXME

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
        $this->app()
            ->throwException('core/unimplemented')
        ; // FIXME

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