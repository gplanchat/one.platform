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
    protected $_userEntity = NULL;

    public function _construct($options)
    {
        $options = parent::_construct($options);

        $this->_init('user');

        return $options;
    }

    public function getUserEntity()
    {
        if (is_null($this->_userEntity)) {
            $this->_userEntity = $this->app()
                ->getSingleton('user/entity')
            ;

            $storage = $this->app()->getModel('user/entity.authentication.storage', $this);

            if (!$this->_userEntity->isLoaded() && !$storage->isEmpty()) {
                $this->_userEntity->load($storage->read(), 'username');
            }
        }
        return $this->_userEntity;
    }

    public function setUserModel(One_User_Model_Entity $user)
    {
        $this->_user = $user;

        return $this;
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
        return (bool) !($this->_getData('identity') === null);
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

        return $this->_getData('identity');
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
        $this->_setData('identity', $contents);
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
        $this->_unsetData('identity');
    }
}

