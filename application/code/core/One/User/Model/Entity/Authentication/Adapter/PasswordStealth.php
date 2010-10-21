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

class One_User_Model_Entity_Authentication_Adapter_PasswordStealth
    extends One_User_Model_Entity_Authentication_AdapterAbstract
{
    const HASH_ALGO = 'sha256';

    public function authenticate()
    {
        $this->getAuthenticationModel()->loadByIdentity($this->getIdentity());
        if (!$this->getAuthenticationModel()->getId()) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->getIdentity());
        }

        $hash = $this->hash(
            $this->getAuthenticationModel()->getServerHash(true),
            base64_decode($this->getSalt())
            );

        if (base64_decode($this->getCredential()) === $hash) {
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->getIdentity());
        }

        return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->getIdentity());
    }

    public function hash($message, $salt = '')
    {
        return hash(self::HASH_ALGO, ($message . $salt), true);
    }
}