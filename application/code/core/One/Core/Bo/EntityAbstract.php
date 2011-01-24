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
 * @since 0.1.0
 */
abstract class One_Core_Bo_EntityAbstract
    extends One_Core_Object
    implements One_Core_Bo_EntityInterface
{
    /**
     * Default primary key field name.
     *
     * @since 0.1.0
     *
     * @var string
     */
    const DEFAULT_ENTITY_ID_FIELD_NAME = 'entity_id';

    /**
     * Default datamapper
     *
     * @since 0.1.0
     *
     * @var string
     */
    const DEFAULT_DATAMAPPER = 'core/data-mapper.standard';

    /**
     * Loading status flag
     *
     * @since 0.1.0
     *
     * @var bool
     */
    private $_isLoaded = false;

    /**
     * Saving status flag
     *
     * @since 0.1.0
     *
     * @var bool
     */
    private $_isSaved = false;

    /**
     * Deleting status flag
     *
     * @since 0.1.0
     *
     * @var bool
     */
    private $_isDeleted = false;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @var string
     */
    private $_moduleName = NULL;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @var One_Core_Dao_TableAbstract
     */
    private $_dao = NULL;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @var One_Core_Orm_DataMapper
     */
    private $_orm = NULL;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @param string $daoClass
     * @param string $ormClass
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _init($daoHandlerClass, $ormDataMapperClass = NULL)
    {
        if (is_null($ormDataMapperClass)) {
            $ormDataMapperClass = self::DEFAULT_DATAMAPPER;
        }

        $this->setDao($this->app()->getResource($daoHandlerClass, 'resource/dao'));
        $this->setDataMapper($this->app()->getResourceSingleton($ormDataMapperClass, 'resource/orm'));

        return $this;
    }

    /**
     * Set the entity identifier
     *
     * @since 0.1.0
     *
     * @param string $identifier
     * @return One_Core_Bo_EntityAbstract
     */
    public function setId($id)
    {
        return $this->setData($this->getIdFieldName(), $id);
    }

    /**
     * Get the entity identifier
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getId()
    {
        return $this->getData($this->getIdFieldName());
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
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
     * @since 0.1.0
     *
     * @param One_Core_Orm_DataMapper $mapper
     * @return One_Core_Bo_EntityAbstract
     */
    public function setDataMapper(One_Core_Orm_DataMapperAbstract $mapper)
    {
        $this->_orm = $mapper;

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
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
     * @since 0.1.0
     *
     * @param One_Core_Dao_ResourceInterface $daoHandler
     * @return One_Core_Bo_EntityAbstract
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
     * @since 0.1.0
     *
     * @param string $identifier
     * @return One_Core_Bo_EntityAbstract
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
     * @since 0.1.0
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->_moduleName;
    }

    /**
     * Set the entity identifier
     *
     * @since 0.1.0
     *
     * @param string $identifier
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _setId($id)
    {
        return $this->_setData($this->getIdFieldName(), $id);
    }

    /**
     * Get the entity identifier
     *
     * @since 0.1.0
     *
     * @return string
     */
    protected function _getId()
    {
        return $this->_getData($this->getIdFieldName());
    }

    /**
     * Set the primary field name
     *
     * @since 0.1.0
     *
     * @param string $fieldName
     * @return One_Core_Bo_EntityAbstract
     */
    public function setIdFieldName($idFieldName)
    {
        $this->getDao()->setIdFieldName($idFieldName);

        return $this;
    }

    /**
     * Get the primary field name
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getIdFieldName()
    {
        return $this->getDao()->getIdFieldName();
    }

    /**
     * Data set handler
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _setData($key, $value = NULL)
    {
        $this->_isSaved = false;

        return parent::_setData($key, $value);
    }

    /**
     * Data add handler
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _addData($key, $value = NULL)
    {
        $this->_isSaved = false;

        return parent::_addData($key, $value);
    }

    /**
     * Data unset handler
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _unsetData($key = NULL)
    {
        $this->_isSaved = false;

        return parent::_unsetData($key);
    }

    /**
     * Load an entity, based on its identifier
     *
     * @since 0.1.0
     *
     * @final
     * @param mixed $identifier
     * @return One_Core_Bo_EntityAbstract
     */
    public function load($identifier, $field = null)
    {
        if (!is_array($identifier)) {
            if ($field !== null) {
                $identifier = array($field => $identifier);
            } else {
                $identifier = array($this->getIdFieldName() => $identifier);
            }
        }

        $this->_beforeLoad($identifier);
        $this->_load($identifier);

        $this->isLoaded(true);
        $this->isSaved(false);
        $this->isDeleted(false);

        $this->_afterLoad($identifier);

        return $this;
    }

    /**
     * User-defined loading function
     *
     * @since 0.1.0
     *
     * @param string|array $identifier
     * @param mixed|null $attribute
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _load($identifier)
    {
        $this->getDao()->loadEntity($this, $this->getDataMapper(), $identifier);

        return $this;
    }

    /**
     * Pre-loading trigger
     *
     * @since 0.1.0
     *
     * @param $identifier
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _beforeLoad($identifier)
    {
        return $this;
    }

    /**
     * Post-loading trigger
     *
     * @since 0.1.0
     *
     * @param $identifier
     * @param $attribute
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _afterLoad($identifier)
    {
        return $this;
    }

    /**
     * Save the model into its static representation
     *
     * @since 0.1.0
     *
     * @return One_Core_Bo_EntityAbstract
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
     * @since 0.1.0
     *
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _save()
    {
        $this->getDao()->saveEntity($this, $this->getDataMapper());

        return $this;
    }

    /**
     * Pre-saving trigger
     *
     * @since 0.1.0
     *
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _beforeSave()
    {
        return $this;
    }

    /**
     * Post-saving trigger
     *
     * @since 0.1.0
     *
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _afterSave()
    {
        return $this;
    }

    /**
     * Delete the entity
     *
     * @since 0.1.0
     *
     * @param mixed $identifier
     * @return One_Core_Bo_EntityAbstract
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
     * @since 0.1.0
     *
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _delete()
    {
        $this->getDao()->deleteEntity($this, $this->getDataMapper());

        return $this;
    }

    /**
     * Pre-delteing trigger
     *
     * @since 0.1.0
     *
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _beforeDelete()
    {
        return $this;
    }

    /**
     * Post-deleting trigger
     *
     * @since 0.1.0
     *
     * @return One_Core_Bo_EntityAbstract
     */
    protected function _afterDelete()
    {
        return $this;
    }

    /**
     * Get the loading status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isLoaded($flag = NULL)
    {
        if (!is_null($flag)) {
            $this->_isLoaded = $flag;
        }
        return $this->_isLoaded;
    }

    /**
     * Get the saving status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isSaved($flag = NULL)
    {
        if (!is_null($flag)) {
            $this->_isSaved = $flag;
        }
        return $this->_isSaved;
    }

    /**
     * Get the deletion status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isDeleted($flag = NULL)
    {
        if (!is_null($flag)) {
            $this->_isDeleted = $flag;
        }
        return $this->_isDeleted;
    }
}