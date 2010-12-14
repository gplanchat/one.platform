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
 * Base model object management class
 *
 * @uses        One_Core_Object
 * @uses        One_Core_Model_EntityInterface
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 *
 * @since 0.1.3
 */
abstract class One_Core_Bo_CollectionAbstract
    extends One_Core_Collection
    implements One_Core_Bo_CollectionInterface
{
    /**
     * Default primary key field name.
     *
     * @since 0.1.3
     *
     * @var string
     */
    const DEFAULT_ENTITY_ID_FIELD_NAME = 'entity_id';

    /**
     * Default datamapper
     *
     * @since 0.1.3
     *
     * @var string
     */
    const DEFAULT_DATAMAPPER = 'core/data-mapper.standard';

    /**
     * Loading status flag
     *
     * @since 0.1.3
     *
     * @var bool
     */
    private $_isLoaded = false;

    /**
     * Saving status flag
     *
     * @since 0.1.3
     *
     * @var bool
     */
    private $_isSaved = false;

    /**
     * Deleting status flag
     *
     * @since 0.1.3
     *
     * @var bool
     */
    private $_isDeleted = false;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @var string
     */
    private $_moduleName = null;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @var One_Core_Dao_TableAbstract
     */
    private $_dao = null;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @var One_Core_Orm_DataMapper
     */
    private $_orm = null;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @var string
     */
    protected $_boEntityClass = null;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.4
     *
     * @var int
     */
    protected $_page = 1;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.4
     *
     * @var int
     */
    protected $_pageSize = 20;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.4
     *
     * @var int
     */
    protected $_itemsCount = null;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @param string $daoClass
     * @param string $ormClass
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _init($boEntityClass, $daoHandlerClass, $ormDataMapperClass = null)
    {
        if (is_null($ormDataMapperClass)) {
            $ormDataMapperClass = self::DEFAULT_DATAMAPPER;
        }

        $this->_boEntityClass = $boEntityClass;
        $this->setDao($this->app()->getResource($daoHandlerClass, 'resource.dao'));
        $this->setDataMapper($this->app()->getResourceSingleton($ormDataMapperClass, 'resource.orm'));

        return $this;
    }

    /**
     * Get the entity identifier
     *
     * @since 0.1.3
     *
     * @return array
     */
    public function getAllIds()
    {
        $ids = array();
        foreach ($this as $item) {
            $ids[] = $item->getId();
        }
        return $ids;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @return One_Core_Orm_DataMapper
     */
    public function getDataMapper()
    {
        return $this->_orm;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Bo_CollectionAbstract
     */
    public function setDataMapper(One_Core_Orm_DataMapperAbstract $mapper)
    {
        $this->_orm = $mapper;

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @return One_Core_Dao_ObjectInterface
     */
    public function getDao()
    {
        return $this->_dao;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.3
     *
     * @param One_Core_Dao_ResourceInterface $daoHandler
     * @return One_Core_Bo_CollectionAbstract
     */
    public function setDao(One_Core_Dao_ResourceInterface $daoHandler)
    {
        $this->_dao = $daoHandler;

        return $this;
    }

    /**
     * Set the module identifier
     * /!\ Internal use only!!
     *
     * @since 0.1.3
     *
     * @param string $identifier
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _setModuleName($moduleName)
    {
        $this->_moduleName = $moduleName;

        return $this;
    }

    /**
     * Get the module identifier
     * /!\ Internal use only!!
     *
     * @since 0.1.3
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->_moduleName;
    }

    /**
     * Set the primary field name
     *
     * @since 0.1.3
     *
     * @param string $fieldName
     * @return One_Core_Bo_CollectionAbstract
     */
    public function setIdFieldName($idFieldName)
    {
        $this->getDao()->setIdFieldName($idFieldName);

        return $this;
    }

    /**
     * Get the primary field name
     *
     * @since 0.1.3
     *
     * @return string
     */
    public function getIdFieldName()
    {
        return $this->getDao()->getIdFieldName();
    }

    /**
     * Load entities, based on its identifiers
     *
     * @since 0.1.3
     *
     * @final
     * @param mixed $identifier
     * @return One_Core_Bo_CollectionAbstract
     */
    public function load($identifiers = array())
    {
        $this->_beforeLoad($identifiers);
        $this->_load($identifiers);

        $this->isLoaded(true);
        $this->isSaved(false);
        $this->isDeleted(false);

        $this->_afterLoad($identifiers);

        return $this;
    }

    /**
     * User-defined loading function
     *
     * @since 0.1.3
     *
     * @param mixed $identifier
     * @param string $attribute
     * @return One_Core_Bo_CollectionAbstract
     */
     protected function _load($identifiers)
    {
        $this->getDao()
            ->setLimit($this->_pageSize)
            ->setOffset($this->_pageSize * ($this->_page - 1))
            ->loadCollection($this, $this->getDataMapper(), $identifiers);

        return $this;
    }

    /**
     * Pre-loading trigger
     *
     * @since 0.1.3
     *
     * @param $identifier
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _beforeLoad($identifiers)
    {
        return $this;
    }

    /**
     * Post-loading trigger
     *
     * @since 0.1.3
     *
     * @param $identifier
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _afterLoad($identifiers)
    {
        $this->count();

        return $this;
    }

    /**
     * Save the model into its static representation
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_CollectionAbstract
     */
    public function save()
    {
        $this->_beforeSave();
        $this->_save();

        $this->isLoaded(true);
        $this->isSaved(true);
        $this->isDeleted(false);

        $this->_afterSave();

        return $this;
    }

    /**
     * User-defined saving function
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _save()
    {
        $this->getDao()->saveCollection($this, $this->getDataMapper());

        return $this;
    }

    /**
     * Pre-saving trigger
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _beforeSave()
    {
        return $this;
    }

    /**
     * Post-saving trigger
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _afterSave()
    {
        return $this;
    }

    /**
     * Delete the entity
     *
     * @since 0.1.3
     *
     * @param mixed $identifier
     * @return One_Core_Bo_CollectionAbstract
     */
    public function delete()
    {
        $this->_beforeDelete();
        $this->_delete();

        $this->isLoaded(false);
        $this->isSaved(false);
        $this->isDeleted(true);

        $this->_afterDelete();

        return $this;
    }

    /**
     * User-defined deleting function
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _delete()
    {
        $this->getDao()->deleteCollection($this, $this->getDataMapper());

        return $this;
    }

    /**
     * Pre-delteing trigger
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _beforeDelete()
    {
        return $this;
    }

    /**
     * Post-deleting trigger
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_CollectionAbstract
     */
    protected function _afterDelete()
    {
        return $this;
    }

    /**
     * Get the loading status flag
     *
     * @since 0.1.3
     *
     * @return bool
     */
    public function isLoaded($flag = null)
    {
        if (!is_null($flag)) {
            $this->_isLoaded = $flag;
        }
        return $this->_isLoaded;
    }

    /**
     * Get the saving status flag
     *
     * @since 0.1.3
     *
     * @return bool
     */
    public function isSaved($flag = null)
    {
        if (!is_null($flag)) {
            $this->_isSaved = $flag;
        }
        return $this->_isSaved;
    }

    /**
     * Get the deletion status flag
     *
     * @since 0.1.3
     *
     * @return bool
     */
    public function isDeleted($flag = null)
    {
        if (!is_null($flag)) {
            $this->_isDeleted = $flag;
        }
        return $this->_isDeleted;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @return string
     */
    public function getBoEntityClass()
    {
        return $this->_boEntityClass;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @param array $data
     * @return One_Core_Bo_EntityInterface
     */
    public function newItem(Array $data)
    {
        $item = $this->app()->getModel($this->getBoEntityClass(), $data);

        $item->isLoaded(true);
        $this->addItem($item);

        return $item;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @param array $data
     * @return One_Core_Bo_CollectionInterface
     */
    public function addItem($item)
    {
        if (!$item instanceof One_Core_Bo_EntityInterface) {
            $this->app()->throwException('core/invalid-method-call');
        }
        return parent::addItem($item);
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @return array
     */
    public function toArray()
    {
        $returnArray = array();
        foreach ($this as $item) {
            $returnArray[] = $item->getData();
        }
        return $returnArray;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.4
     *
     * @return One_Core_Bo_CollectionInterface
     */
    public function setPage($page, $pageSize = null)
    {
        $this->_pageSize = min(max(intval($pageSize), 5), 200);
        $this->_page = min(max(1, intval($page)), ceil($this->count() / $this->_pageSize));

        return $this;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.4
     *
     * @return int
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.4
     *
     * @return int
     */
    public function getPageCount()
    {
        return ceil($this->count() / $this->_pageSize);
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.4
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.4
     *
     * @return int
     */
    public function count()
    {
        if ($this->_itemsCount === null) {
            $this->_itemsCount = $this->getDao()->countItems($this);
        }
        return $this->_itemsCount;
    }

    public function sort($fields)
    {
        return $this;
    }

    public function toHash($field)
    {
        if (!current($this->_items)->hasData($field)) {
            return array();
        }

        if (!$this->isLoaded()) {
            $this->load();
        }

        $hash = array();
        foreach ($this->_items as $item) {
            $hash[$item->getId()] = $item->getData($field);
        }
        return $hash;
    }
}