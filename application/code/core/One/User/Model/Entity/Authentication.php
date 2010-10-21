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
 * @uses        One_Core_Bo_EntityAbstract
 *
 * @access      public
 * @author      gplanchat
 * @category    Nova
 * @package     Bootstrap
 * @subpackage  Bootstrap
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

