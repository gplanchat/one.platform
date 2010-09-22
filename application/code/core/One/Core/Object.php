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
 * Base data object management class
 *
 * @access      public
 * @author      gplanchat
 * @category    Dal
 * @package     One
 * @subpackage  One_Core
 */
abstract class One_Core_Object
    implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Internal data handler.
     *
     * @since 0.1.0
     *
     * @access private
     * @var array
     */
    protected $_data = array();

    /**
     * Internal original data 'memory'.
     *
     * @since 0.1.0
     *
     * @access private
     * @var array
     */
    private $_originalData = array();

    /**
     * TODO: PHPUnit
     *
     * @since 0.1.0
     *
     * @var string
     */
    private $_moduleName = NULL;

    /**
     * Internal constructor, should not be overloaded.
     *
     * @since 0.1.0
     *
     * @param string $moduleName
     * @param Zend_Config|array $data
     * @return void
     */
    public function __construct($moduleName = 'core', $data = array())
    {
        if (!is_string($moduleName)) {
            Nova::throwException('core/invalid-method-call', 'Parameter 1 sould be a string.');
        }
        $this->_moduleName = $moduleName;

        if ($data instanceof Zend_Config) {
            $data = $data->toArray();
        } else if (!is_array($data)) {
            Nova::throwException('core/invalid-method-call', 'Parameter 2 should be either an array or a Zend_Config instance.');
        }

        $this->_data = $data;
        $this->_originalData = $data;

        $this->_construct();
    }

    /**
     * User-defined constructor, should build/call the initialization routines
     *
     * @since 0.1.0
     *
     * @return One_Core_ObjectAbstract
     */
    protected function _construct()
    {
    }

    /**
     * Attach a specified variable to the $_data array
     *
     * @since 0.1.0
     *
     * @param array $variable
     * @return One_Core_Object
     */
    protected function _attachData(Array &$variable)
    {
        $this->_data = &$variable;
        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @param $tableAlias
     */
    public function getModuleName()
    {
        return $this->_moduleName;
    }

    /**
     * Build a camelized identifier from its underscored equivalent.
     *
     * @since 0.1.0
     *
     * @param string $key
     * @return string
     */
    private static function _camelize($key)
    {
        static $camelizeCache = array();

        $key = strtolower((string) $key);
        if (isset($camelizeCache[$key])) {
            return $camelizeCache[$key];
        }

        //Nova::profilerStart(__METHOD__);
        $camelizeCache[$key] = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
        //Nova::profilerStop(__METHOD__);

        return $camelizeCache[$key];
    }

    /**
     * Build an underscored identifier from its camelized equivalent.
     *
     * @since 0.1.0
     *
     * @param string $key
     * @return string
     */
    private function _underscore($key)
    {
        static $underscoreCache = array();

        if (isset($underscoreCache[(string) $key])) {
            return $underscoreCache[(string) $key];
        }

        //Nova::profilerStart(__METHOD__);
        $underscoreCache[(string) $key] = strtolower(preg_replace('#([A-Z])#', '_$1', lcfirst($key)));
        //Nova::profilerStop(__METHOD__);

        return $underscoreCache[(string) $key];
    }

    /**
     * Update the 'orignal data' handler with the contents of the 'data' handle.
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_ObjectInterface
     */
    public function updateOriginalData()
    {
        $this->_originalData = $this->_data;
    }

    /**
     * Data set handler. Sets the data as it is defined.
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_ObjectInterface
     */
    public function setData($key, $value = NULL)
    {
        if ($key instanceof Zend_Config) {
            $key = $key->toArray();
        }

        if (is_array($key)) {
            $this->_data = $key;
        } else {
            $this->_data[self::_underscore((string) $key)] = $value;
        }
        return $this;
    }

    /**
     * Data add handler. Adds data to the currently handled data.
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_ObjectInterface
     */
    public function addData($key, $value = NULL)
    {
        if ($key instanceof Zend_Config) {
            $key = $key->toArray();
        }

        if (is_array($key)) {
            foreach ($key as $index => $value) {
                $this->_data[self::_underscore($index)] = $value;
            }
        } else {
            $this->_data[self::_underscore((string) $key)] = $value;
        }
        return $this;
    }

    /**
     * Data get handler.
     *
     * @since 0.1.0
     *
     * @param string $key
     * @return mixed
     */
    public function getData($key = NULL)
    {
        if (is_null($key)) {
            return $this->_data;
        }
        return $this->_getData(self::_underscore((string) $key));
    }

    /**
     * Data isset handler
     *
     * @since 0.1.0
     *
     * @param string $key
     * @return bool
     */
    public function hasData($key = NULL)
    {
        if (is_null($key)) {
            return $this->_hasData();
        }
        return $this->_hasData(self::_underscore((string) $key));
    }

    /**
     * Data unset handler
     *
     * @since 0.1.0
     *
     * @param $key
     * @return One_Core_ObjectInterface
     */
    public function unsetData($key = NULL)
    {
        if (is_null($key)) {
            return $this->_unsetData();
        }
        $this->_isSaved = false;
        return $this->_unsetData(self::_underscore((string) $key));
    }

    /**
     * Data raw set handler
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_ObjectInterface
     */
    protected function _setData($key, $value = NULL)
    {
        if (is_array($key)) {
            foreach ($key as $index => $value) {
                $this->_data[$index] = $value;
            }
        } else {
            $this->_data[(string) $key] = $value;
        }
        return $this;
    }

    /**
     * Data raw add handler
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_ObjectInterface
     */
    protected function _addData($key, $value = NULL)
    {
        if (is_array($key)) {
            foreach ($key as $index => $value) {
                $this->_data[$index] = $value;
            }
        } else {
            $this->_data[(string) $key] = $value;
        }
        return $this;
    }

    /**
     * Data raw get handler
     *
     * @since 0.1.0
     *
     * @param string $key
     * @return mixed
     */
    protected function _getData($key = NULL)
    {
        if (is_null($key)) {
            return $this->_data;
        } else if ($this->_hasData($key)) {
            return $this->_data[$key];
        }
        return NULL;
    }

    /**
     * Data raw isset handler
     *
     * @since 0.1.0
     *
     * @param string $key
     * @return bool
     */
    protected function _hasData($key = NULL)
    {
        if (is_null($key)) {
            return !empty($this->_data);
        }

        return array_key_exists($key, $this->_data);
    }

    /**
     * Data raw unset handler
     *
     * @since 0.1.0
     *
     * @param $key
     * @return One_ModelAbstract
     */
    protected function _unsetData($key = NULL)
    {
        $this->_isSaved = false;

        if (is_null($key)) {
            $this->_data = array();
        } else if ($this->_hasData($key)) {
            unset($this->_data[$key]);
        }
        return $this;
    }

    /**
     * Array access get handler
     *
     * @since 0.1.0
     *
     * @final
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        return $this->_getData($offset);
    }

    /**
     * Array access set handler
     *
     * @since 0.1.0
     *
     * @final
     * @return One_Core_ObjectAbstract
     */
    final public function offsetSet($offset, $data)
    {
        return $this->_setData($offset, $data);
    }

    /**
     * Array access unset handler
     *
     * @since 0.1.0
     *
     * @final
     * @return One_Core_ObjectAbstract
     */
    final public function offsetUnset($offset)
    {
        return $this->_unsetData($offset);
    }

    /**
     * Array access isset handler
     *
     * @since 0.1.0
     *
     * @final
     * @return bool
     */
    final public function offsetExists($offset)
    {
        return $this->_hasData($offset);
    }

    /**
     * Magic property access get handler
     *
     * @since 0.1.0
     *
     * @final
     * @return mixed
     */
    final public function __set($offset, $data)
    {
        return $this->setData($offset);
    }

    /**
     * Magic property access get handler
     *
     * @since 0.1.0
     *
     * @final
     * @return mixed
     */
    final public function __get($offset)
    {
        return $this->getData($offset);
    }

    /**
     * Magic property access isset handler
     *
     * @since 0.1.0
     *
     * @final
     * @return bool
     */
    final public function __isSet($offset)
    {
        return $this->hasData($offset);
    }

    /**
     * Magic property access unset handler
     *
     * @since 0.1.0
     *
     * @final
     * @return One_Core_ObjectAbstract
     */
    final public function __unSet($offset)
    {
        return $this->unsetData($offset);
    }

    /**
     * Magic method access handler
     *
     * @since 0.1.0
     *
     * @final
     * @throws Nonva_Core_Exception_InvalidMethodCall
     * @return mixed
     */
    final public function __call($method, $params)
    {
        //Nova::profilerStart(__METHOD__);
        switch (substr($method, 0, 3)) {
        case 'get':
            return $this->getData(substr($method, 3));
            break;

        case 'set':
            return $this->setData(substr($method, 3), $params[0]);
            break;

        case 'has':
            return $this->hasData(substr($method, 3));
            break;

        case 'uns':
            return $this->unsetData(substr($method, 5));
            break;
        }
        //Nova::profilerStop(__METHOD__);

        Nova::throwException('core/invalidMethodCall',
            'One_Core_ObjectAbstract_Exception_InvalidMethod', $method);
    }

    /**
     * Get the data fields iterator
     *
     * @since 0.1.0
     *
     * @see IteratorAggregate
     * @return Iterator
     */
    public function getIterator()
    {
        // TODO: Implement a bundled iterator
        return new ArrayIterator($this->_data);
    }

    /**
     * Count the data fields iterator
     *
     * @since 0.1.0
     *
     * @see Countable
     * @return int
     */
    public function count()
    {
        return count($this->_data);
    }
}