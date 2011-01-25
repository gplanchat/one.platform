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
 * Base data object management class
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class One_Core_Collection
    implements One_Core_ObjectInterface, Countable, SeekableIterator
{
    /**
     * Internal items iteration index.
     *
     * @since 0.1.0
     *
     * @var int
     */
    protected $_index = 0;

    /**
     * Internal items handler.
     *
     * @since 0.1.0
     *
     * @access private
     * @var array
     */
    protected $_items = array();

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
     * Count the data fields iterator
     *
     * @since 0.1.0
     *
     * @see Countable
     * @return int
     */
    public function count()
    {
        return count($this->_items);
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

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @param mixed $item
     * @return mixed
     */
    public function addItem($item)
    {
        $this->_items[] = $item;
    }

    /**
     * TODO: PHPDoc
     *
     * @return One_Core_Bo_EntityInterface
     */
    public function current()
    {
        return current(array_slice($this->_items, $this->_index, 1));
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @return int
     */
    public function key()
    {
        return $this->_index;
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_EntityInterface
     */
    public function next()
    {
        $this->_index++;

        return $this->current();
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_EntityInterface
     */
    public function seek($position)
    {
        $this->_index = (int) $position;

        return $this->current();
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @return One_Core_Bo_EntityInterface
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * TODO: PHPDoc
     *
     * @since 0.1.3
     *
     * @return bool
     */
    public function valid()
    {
        return (bool) (($this->_index >= 0) && ($this->_index < count($this->_items)));
    }
}