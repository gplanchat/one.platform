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
    public function __construct($moduleName = 'core', Array $data = array(), One_Core_Model_application $application = null)
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