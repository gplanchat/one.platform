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
class One_User_Model_Entity
    extends One_Core_Bo_EntityAbstract
{
    public function _construct()
    {
        $this->_init('user/entity', 'user/entity.orm.data-mapper');
    }

    public function login($identity, $credential, $salt)
    {
        // TODO: dispatch event 'user.login.before'
        if ($this->isLoaded()) {
            return false;
        }

        $adapter = Nova::getModel('user/entity.authentication.adapter.password-stealth')
            ->setIdentity($identity)
            ->setCredential($credential)
            ->setSalt($salt);

        $storage = Nova::getSingleton('user/session')
            ->setUserModel($this);

        $result = Zend_Auth::getInstance()
            ->setStorage($storage)
            ->authenticate($adapter);

        var_dump($result);

        // TODO: dispatch event 'user.login.after'

        switch($result->getCode()) {
        case Zend_Auth_Result::SUCCESS:
            // TODO: dispatch event 'user.login.success'
            $this->load($result->getIdentity(), 'username');
            return true;

        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
            // TODO: I18n
            Nova::getSingleton('user/session')
                ->addNotice('Wrong password.');
            break;

        case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
            // TODO: I18n
            Nova::getSingleton('user/session')
                ->addNotice('User does not exist.');
            break;

        case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
        default:
            // TODO: I18n
            Nova::getSingleton('user/session')
                ->addError('Could not connect, an error occured. Please contact admin.');
            break;
        }

        foreach ($result->getMessages() as $message) {
            Nova::getSingleton('user/session')
                ->addNotice($message);
        }

        // TODO: dispatch event 'user.login.failure'
        return false;
    }
}

