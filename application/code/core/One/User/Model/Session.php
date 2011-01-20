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
 * documentation for further information about customizing One.Platform.
 *
 */

/**
 * Session management class
 *
 * @access      public
 * @author      gplanchat
 * @category    Nova
 * @package     Bootstrap
 * @subpackage  Bootstrap
 */
class One_User_Model_Session
    extends One_Core_Model_SessionAbstract
    implements One_User_Model_Entity_Authentication_StorageInterface
{
    const STORAGE_KEY = 'auth_storage';
    const TIME_KEY = 'auth_time';

    protected $_userEntity = null;

    protected $_userModel = 'user/entity';

    protected function _construct($options)
    {
        $options = parent::_construct($options);

        $this->_init('user');

        return $options;
    }

    public function getUserEntity()
    {
        if (is_null($this->_userEntity)) {
            $this->_userEntity = $this->app()
                ->getModel($this->_userModel)
            ;
        }

        if ($this->_userEntity->getId() === null && !$this->isEmpty()) {
            $this->_userEntity->load($this->read(), 'username');
        }

        return $this->_userEntity;
    }

    /**
     *
     * @param $user
     */
    public function setUserModel(One_User_Model_Entity $user)
    {
        $this->_user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogonTime()
    {
        return $this->_getData(self::TIME_KEY);
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @see Zend_Auth_Storage
     * @throws Zend_Auth_Storage_Exception If it is impossible to determine whether storage is empty
     * @return boolean
     */
    public function isEmpty()
    {
        return (bool) ($this->_getData(self::STORAGE_KEY) === null);
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @see Zend_Auth_Storage
     * @throws Zend_Auth_Storage_Exception If reading contents from storage is impossible
     * @return mixed
     */
    public function read()
    {
        if ($this->isEmpty()) {
            // TODO: I18n
            $this->app()->throwException('user/authStorageAccessError',
                'User account storage is empty');
        }

        return $this->_getData(self::STORAGE_KEY);
    }

    /**
     * Writes $contents to storage
     *
     * @see Zend_Auth_Storage
     * @param  mixed $contents
     * @throws Zend_Auth_Storage_Exception If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $this->_setData(self::STORAGE_KEY, $contents);
        $this->_setData(self::TIME_KEY, time());

        return $this;
    }

    /**
     * Clears contents from storage
     *
     * @see Zend_Auth_Storage
     * @throws Zend_Auth_Storage_Exception If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        $this->_unsetData(self::STORAGE_KEY);
        $this->_unsetData(self::TIME_KEY);

        return $this;
    }
}

