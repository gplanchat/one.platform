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
    protected $_sessionModel = 'user/session';

    public function _construct($options)
    {
        parent::_construct($options);

        $this->_init('user/entity', 'user/entity.data-mapper');

        return $this;
    }

    public function login($loginFieldsetDatas)
    {
        if ($this->isLoaded()) {
            return false;
        }

        $session = $this->app()->getSingleton($this->_sessionModel);

        if (!isset($loginFieldsetDatas['identity']) || !is_string($loginFieldsetDatas['identity'])) {
            // TODO: I18n
            $session->addError('Precondition failed: identity data invalid or empty.');
            return false;
        }
        $identity = $loginFieldsetDatas['identity'];

        if (!isset($loginFieldsetDatas['password']) || !is_string($loginFieldsetDatas['password'])) {
            // TODO: I18n
            $session->addError('Precondition failed: credential data invalid or empty.');
            return false;
        }
        $credential = $loginFieldsetDatas['password'];
        $salt = $session->getTransferSalt();

        $this->app()->dispatchEvent('user.login.before', array(
            'identity'   => &$identity,
            'credential' => &$credential,
            'hash'       => &$salt
            ));

        $adapter = $this->app()
            ->getModel('user/entity.authentication.adapter.password-stealth')
            ->setIdentity($identity)
            ->setCredential($credential)
            ->setSalt($salt);

        $result = $adapter->authenticate($adapter);

        $this->app()->dispatchEvent('user.login.after', array(
            'identity'   => $identity,
            'credential' => $credential,
            'hash'       => $salt,
            'result'     => $result,
            'adapter'    => $adapter
            ));

        switch($result->getCode()) {
        case Zend_Auth_Result::SUCCESS:
            $this->load($result->getIdentity(), 'username');

            if ($this->getId()) {
                $session->write($result->getIdentity());
            }

            $this->app()->dispatchEvent('user.login.success', array(
                'entity' => $this
                ));
            return true;

        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
            // TODO: I18n
            $session->addNotice('Wrong password.');
            break;

        case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
            // TODO: I18n
            $session->addNotice('User does not exist.');
            break;

        case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
        default:
            // TODO: I18n
            $session->addError('Could not connect, an error occured. Please contact admin.');
            break;
        }

        foreach ($result->getMessages() as $message) {
            $session->addNotice($message);
        }

        $this->app()->dispatchEvent('user.login.failure', array(
            'entity' => $this,
            'result' => $result
            ));

        return false;
    }

    public function register($userdata)
    {
        return false;
    }
}

