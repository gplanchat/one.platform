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

require_once 'Nova/Core/ResourceInterface.php';
/**
 * Debug class
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Debug
 * @package     One
 * @subpackage  One_Core
 * @todo Clean up source code
 */
class One_Core_Model_Debug
    extends One_Core_Object
{
    /**
     * FIXME: PHPDoc
     */
    const CRITICAL      = 0x80;

    /**
     * FIXME: PHPDoc
     */
    const ERROR         = 0x40;

    /**
     * FIXME: PHPDoc
     */
    const WARNING       = 0x20;

    /**
     * FIXME: PHPDoc
     */
    const INFO          = 0x10;

    /**
     * FIXME: PHPDoc
     */
    const MESSAGE       = 0x08;

    /**
     * FIXME: PHPDoc
     */
    const AUDIT_FAILED  = 0x04;

    /**
     * FIXME: PHPDoc
     */
    const AUDIT_SUCCESS = 0x02;

    /**
     * FIXME: PHPDoc
     */
    const DEBUG         = 0x01;

    /**
     * FIXME: PHPDoc
     */
    const DEFAULT_LOGFILE = 'var/log/system.log';

    /**
     * Human-readable log level names
     *
     * @var unknown_type
     */
    protected $_logLevelNames = array();

    /**
     * FIXME: PHPDoc
     */
    protected $_logLevelIdientifiers = array();

    /**
     * FIXME: PHPDoc
     */
    protected $_buffer = array();

    public function _construct()
    {
        $reflector = new ReflectionClass($this);
        $this->_logLevelNames = array_flip($reflector->getConstants());
        foreach ($this->_logLevelNames as &$level) {
            $level = ucwords(str_replace('_', ' ', $level));
        }
        unset($level);
    }

    /**
     * Clean logging method
     *
     * @param string $message
     * @param string $resource
     * @param int $level
     * @return unknown_type
     */
    public function log($message, $resource = self::DEFAULT_LOGFILE, $level = self::DEBUG, $buffered = false)
    {
        $message = sprintf('%s: %s - %s', $this->_logLevelNames[$level], date('r'), $message);
        if(!($fp = fopen($resource, 'a'))) {
            trigger_error('Unable to open logs.', E_USER_ERROR);
            trigger_error($message, E_USER_ERROR);
            return false;
        }
        fwrite($fp, $message);
        fclose($fp);
        return true;
    }

    /**
     * FIXME: PHPDoc
     */
    public static function getInstance()
    {
        static $instance = NULL;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
