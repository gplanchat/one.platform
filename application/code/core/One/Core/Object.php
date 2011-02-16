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
 * Base data object management class
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
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
    public function __construct($moduleName, Array $data = array(), One_Core_Model_application $application = null)
    {
        if (!is_string($moduleName)) {
            $moduleName = 'core';
        }
        $this->_moduleName = $moduleName;
        $this->_app = $application;

        if ($data instanceof Zend_Config) {
            $data = $data->toArray();
        } else if (!is_array($data) && $data !== null) {
            One::throwException('core/invalid-method-call', 'Parameter 2 should be either an array or a Zend_Config instance.');
        }

        $this->_construct($data);
    }

    /**
     * User-defined constructor, should build/call the initialization routines
     *
     * @since 0.1.0
     *
     * @return One_Core_Object
     */
    protected function _construct($data)
    {
        $this->addData($data);
        $this->_originalData = $this->_data;

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

        $camelizeCache[$key] = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));

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

        $underscoreCache[(string) $key] = self::getInflector()->filter(array(
            'index' => $key
            ));

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

        return $this;
    }

    /**
     * Place the 'orignal data' into the 'data' handle.
     *
     * @since 0.1.0
     *
     * @param string|array $key
     * @param mixed $value
     * @return One_Core_ObjectInterface
     */
    public function resetData()
    {
        $this->_data = $this->_originalData;

        return $this;
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

        return $this->_call($method, $params);
    }

    protected function _call($method, $params)
    {
        $this->app()->throwException('core/invalidMethodCall',
            'Method "%s" not found.', $method);
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

    /**
     * TODO: PHPDoc
     * TODO: PHPUnit
     *
     * @return One_Core_Model_Application
     */
    public function app()
    {
        return $this->_app;
    }
}