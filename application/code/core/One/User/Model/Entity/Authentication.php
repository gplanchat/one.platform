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
 * Authentication BO entity model, used to store and retrieve authentication
 * informations.
 *
 * @uses        One_Core_Bo_EntityAbstract
 *
 * @access      public
 * @author      gplanchat
 * @category    User
 * @package     One_User
 * @subpackage  One_User
 */
class One_User_Model_Entity_Authentication
    extends One_Core_Bo_EntityAbstract
{
    public function _construct($options)
    {
        $options = parent::_construct($options);

        $this->_init('user/entity.authentication');

        return $options;
    }

    public function loadByIdentity($identity, $websiteId = NULL)
    {
        $this->load($identity, 'username');

        return $this;
    }

    public function setServerHash($hash, $base64Encoded = true)
    {
        if ($base64Encoded === true) {
            return $this->_setData('server_hash', $hash);
        }
        return $this->_setData('server_hash', base64_encode($hash));
    }

    public function setServerSalt($salt, $base64Encoded = true)
    {
        if ($base64Encoded === true) {
            return $this->_setData('server_salt', $salt);
        }
        return $this->_setData('server_salt', base64_encode($salt));
    }

    public function getServerHash($base64Decoded = false)
    {
        if ($base64Decoded !== true) {
            return $this->_getData('server_hash');
        }
        return base64_decode($this->_getData('server_hash'));
    }

    public function getServerSalt($base64Decoded = false)
    {
        if ($base64Decoded !== true) {
            return $this->_getData('server_salt');
        }
        return base64_decode($this->_getData('server_salt'));
    }
}

