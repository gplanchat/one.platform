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
 * Base model object management class
 *
 * @uses        One_Core_Object
 * @uses        One_Core_Model_EntityInterface
 *
 * @access      public
 * @author      gplanchat
 * @category    Dal
 * @package     One
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
    public function load($identifiers)
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
        $this->getDao()->loadCollection($this, $this->getDataMapper(), $identifiers);

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

    public function toArray()
    {
        $returnArray = array();
        foreach ($this as $item) {
            $returnArray[] = $item->getData();
        }
        return $returnArray;
    }
}