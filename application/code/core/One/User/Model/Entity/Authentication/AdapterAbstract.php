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
 * User authentication adapter
 *
 * @access      public
 * @author      gplanchat
 * @category    Nova
 * @package     Bootstrap
 * @subpackage  Bootstrap
 */

abstract class One_User_Model_Entity_Authentication_AdapterAbstract
    extends One_Core_Object
    implements One_User_Model_Entity_Authentication_AdapterInterface
{
    protected $_credential = null;
    protected $_identity = null;
    protected $_salt = null;

    /**
     * FIXME: PHPDoc
     *
     * @var One_Core_Bo_EntityAbstract
     */
    protected $_authenticationModel = NULL;

    protected function _construct()
    {
        $this->_bo = Nova::getModel('user/entity.authentication');
    }

    public function setCredential($credential)
    {
        $this->_credential = $credential;

        return $this;
    }

    public function getCredential()
    {
        return $this->_credential;
    }

    public function setIdentity($identity)
    {
        $this->_identity = $identity;

        return $this;
    }

    public function getIdentity()
    {
        return $this->_identity;
    }

    public function setSalt($salt)
    {
        $this->_salt = $salt;

        return $this;
    }

    public function getSalt()
    {
        return $this->_salt;
    }

    /**
     * FIXME PHPDoc
     *
     * @return One_Core_Bo_EntityAbstract
     */
    public function getAuthenticationModel()
    {
        if (is_null($this->_authenticationModel)) {
            $this->_authenticationModel = Nova::getModel('user/entity.authentication');
        }
        return $this->_authenticationModel;
    }

    /**
     * FIXME PHPDoc
     *
     * @return One_Core_Bo_EntityAbstract
     */
    public function setAuthenticationModel(One_Core_Bo_EntityAbstract $authenticationModel)
    {
        $this->_authenticationModel = $authenticationModel;

        return $this;
    }
}