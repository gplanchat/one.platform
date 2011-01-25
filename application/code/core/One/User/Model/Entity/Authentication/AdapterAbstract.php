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
 *     - Neither the name of Grégory PLANCHAT nor the names of its
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
 * User authentication base adapter
 *
 * @access      public
 * @author      gplanchat
 * @category    User
 * @package     One_User
 * @subpackage  One_User
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
            $this->_authenticationModel = $this->app()->getModel('user/entity.authentication');
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