<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * NOTICE:
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 * Session management class
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Nova
 * @package     Bootstrap
 * @subpackage  Bootstrap
 */
abstract class One_Core_Model_SessionAbstract
    extends One_Core_Object
{
    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    const KEY_REMOTE_ADDR           = 'REMOTE_ADDR';

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    const KEY_HTTP_USER_AGENT       = 'HTTP_USER_AGENT';

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    const KEY_HTTP_VIA              = 'HTTP_VIA';

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    const KEY_HTTP_X_FORWARDED_FOR  = 'HTTP_X_FORWARDED_FOR';

    /**
     * FIXME: PHPDoc
     *
     * @var array
     */
    protected $_messages = array();

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    protected function _init($namespace = null)
    {
        if ($namespace !== null) {
            $namespace = (string) $namespace;
        } else {
            $namespace = (string) $this->getModuleName();
        }

        $this->_start();

        if (!isset($_SESSION[$namespace])) {
            $_SESSION[$namespace] = array();
        }

        $this->_data = &$_SESSION[$namespace];

        if (!isset($_SESSION[$namespace]['__messages'])) {
            $_SESSION[$namespace]['__messages'] = array();
        }
        $this->_messages = &$_SESSION[$namespace]['__messages'];

        $this->validate();
        $this->updateCookie();
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    protected function _start()
    {
        if (isset($_SESSION)) {
            return $this;
        }

        session_start();
        //session_regenerate_id();
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    public function validate()
    {
        // TODO
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    public function updateCookie()
    {
        // TODO
    }

    protected function _addMessage($level, $message)
    {
        if (is_null($this->_messages)) {
            $this->_messages = array();

            $this->_data['__messages'] = &$this->_messages;
        }

        if (!isset($this->_messages[$level])) {
            $this->_messages = array();
        }

        $this->_messages[$level][] = $message;

        return $this;
    }

    public function getMessages($empty = true)
    {
        $messageList = $this->_messages;

        if ($empty !== false) {
            $this->_messages = array();
        }

        return $messageList;
    }

    public function addError($messagePattern, $_ = NULL)
    {
        $parameters = func_get_args();
        array_shift($parameters);
        return $this->_addMessage(Zend_Log::ERR, vsprintf($messagePattern, $parameters));
    }

    public function addWarning($messagePattern, $_ = NULL)
    {
        $parameters = func_get_args();
        array_shift($parameters);
        return $this->_addMessage(Zend_Log::WARN, vsprintf($messagePattern, $parameters));
    }

    public function addNotice($messagePattern, $_ = NULL)
    {
        $parameters = func_get_args();
        array_shift($parameters);
        return $this->_addMessage(Zend_Log::NOTICE, vsprintf($messagePattern, $parameters));
    }

    public function addInfo($messagePattern, $_ = NULL)
    {
        $parameters = func_get_args();
        array_shift($parameters);
        return $this->_addMessage(Zend_Log::INFO, vsprintf($messagePattern, $parameters));
    }
}

