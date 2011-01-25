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
 * Session management class
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
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

    public function getAllErrors($empty = true)
    {
        if (!isset($this->_messages[Zend_Log::ERR])) {
            return array();
        }
        $messages = $this->_messages[Zend_Log::ERR];

        if ($empty !== false) {
            $this->_messages[Zend_Log::ERR] = array();
        }

        return $messages;
    }

    public function getAllWarnings($empty = true)
    {
        if (!isset($this->_messages[Zend_Log::WARN])) {
            return array();
        }
        $messages = $this->_messages[Zend_Log::WARN];

        if ($empty !== false) {
            $this->_messages[Zend_Log::WARN] = array();
        }

        return $messages;
    }

    public function getAllNotices($empty = true)
    {
        if (!isset($this->_messages[Zend_Log::NOTICE])) {
            return array();
        }
        $messages = $this->_messages[Zend_Log::NOTICE];

        if ($empty !== false) {
            $this->_messages[Zend_Log::NOTICE] = array();
        }

        return $messages;
    }

    public function getAllInfos($empty = true)
    {
        if (!isset($this->_messages[Zend_Log::INFO])) {
            return array();
        }
        $messages = $this->_messages[Zend_Log::INFO];

        if ($empty !== false) {
            $this->_messages[Zend_Log::INFO] = array();
        }

        return $messages;
    }
}

