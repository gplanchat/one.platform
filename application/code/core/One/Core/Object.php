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
 * @category    Core
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Object
    implements One_Core_ObjectInterface, ArrayAccess, Countable, IteratorAggregate
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
     * Internal inflector
     *
     * @var Zend_Filter_Inflector
     */
    private static $_inflector = null;

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
     * TODO: PHPDoc
     *
     * @since 0.1.0
     *
     * @var string
     */
    private $_moduleName = null;

    /**
     * TODO: PHPUnit
     * TODO: PHPDoc
     *
     * @var One_Core_Model_Application
     */
    private $_app = null;

    /**
     * Internal constructor, should not be overloaded.
     *
     * @since 0.1.0
     *
     * @param string $moduleName
     * @param Zend_Config|array $data
     * @return void
     */
    public function __construct($moduleName = 'core', $data = array(), One_Core_Model_application $application = null)
    {
        if (!is_string($moduleName)) {
            One::throwException('core/invalid-method-call', 'Parameter 1 sould be a string.');
        }
        $this->_moduleName = $moduleName;
        $this->_app = $application;

        if ($data instanceof Zend_Config) {
            $data = $data->toArray();
        } else if (!is_array($data) && $data !== null) {
            One::throwException('core/invalid-method-call', 'Parameter 2 should be either an array or a Zend_Config instance.');
        }

        $this->_data = $this->_construct($data);
        $this->_originalData = $this->_data;
    }

    /**
     * User-defined constructor, should build/call the initialization routines
     *
     * @since 0.1.0
     *
     * @return
     */
    protected function _construct($data)
    {
        return $data;
    }

    /**
     * Attach a specified variable to the $_data array
     *
     * @since 0.1.0
     *
     * @param array $variable
     * @return One_Core_Object
     */
    protected function _attachData(array &$variable)
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
    private static function _underscore($key)
    {
        static $underscoreCache = array();

        if (isset($underscoreCache[(string) $key])) {
            return $underscoreCache[(string) $key];
        }

        //Nova::profilerStart(__METHOD__);
//        $underscoreCache[(string) $key] = strtolower(preg_replace('#([A-Z])#', '_$1', lcfirst($key)));

        $underscoreCache[(string) $key] = self::getInflector()->filter(array(
            'index' => $key
            ));
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

        $this->_setData($key, $value);

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

        $this->_addData($key, $value);

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
        return $this->_getData((string) $key);
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
        return $this->_hasData((string) $key);
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
        return $this->_unsetData($key);
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
        return $this->getData($offset);
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
        return $this->setData($offset, $data);
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
        return $this->unsetData($offset);
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
        return $this->hasData($offset);
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
        return $this->setData(self::_underscore($offset));
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
        return $this->getData(self::_underscore($offset));
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
        return $this->hasData(self::_underscore($offset));
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
        return $this->unsetData(self::_underscore($offset));
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
        $dataIndex = self::_underscore(substr($method, 3));
        switch (substr($method, 0, 3)) {
        case 'get':
            return $this->getData($dataIndex);
            break;

        case 'set':
            return $this->setData($dataIndex, $params[0]);
            break;

        case 'has':
            return $this->hasData($dataIndex);
            break;

        case 'uns':
            return $this->unsetData($dataIndex);
            break;
        }

        $this->_call($method, $params);

        //Nova::profilerStop(__METHOD__);

        One::throwException('core/invalidMethodCall',
            'One_Core_ObjectAbstract_Exception_InvalidMethod', $method);
    }

    protected function _call($method, $params)
    {
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

    public static function getInflector()
    {
        if (self::$_inflector === null) {
            self::$_inflector = new Zend_Filter_Inflector(':index');

            self::$_inflector->setRules(array(
                ':index' => array('Word_CamelCaseToUnderscore', 'StringToLower')
                ));
        }
        return self::$_inflector;
    }

    public static function setInflector(Zend_Filter_Inflector $inflector)
    {
        $this->_inflector = $inflector;
    }

    public function app()
    {
        return $this->_app;
    }
}