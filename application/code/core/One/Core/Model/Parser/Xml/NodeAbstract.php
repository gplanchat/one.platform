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
 * XML file parser node
 *
 * FIXME: PHPDoc
 *
 * @access      public
 * @author      gplanchat
 * @category    Config
 * @package     One
 * @subpackage  One_Core
 */
abstract class One_Core_Model_Parser_Xml_NodeAbstract
    extends One_Core_Object
{
    protected $_handle = NULL;

    /**
     * FIXME: PHPDoc
     *
     */
    protected function _construct()
    {
        if (!$this->_hasData('node')) {
            Nova::throwException('core/invalidConstructorParams');
        }

        $this->_init();
    }

    /**
     * Initialization method, may be reimplemented if needed.
     *
     * @return One_Core_Model_Parser_Xml_Node
     */
    protected function _init()
    {
        $this->_handle = $this->_getData('node');

        return $this;
    }

    public function toArray()
    {

    }

    /**
     * FIXME: PHPDoc
     *
     * @param SimpleXMLElement $node
     * @return One_Core_Model_Parser_Xml_Node
     */
    public function setNode(SimpleXMLElement $node)
    {
        $this->_handle = $node;

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @return SimpleXMLElement
     */
    public function setNode()
    {
        if (is_null($this->_handle)) {
            if ($this->_hasData('node')) {
                $this->_handle = $this->_getData('node');
            } else {
                Nova::throwException('core/invalidConstructorParams');
            }
        }
        return $this->_handle;
    }

    /**
     * Executes an XPath request on the current file
     *
     * @param string $xpath
     * @param array|Zend_Config $namespaces
     * @return One_Core_Model_Parser_Xml_Node
     */
    public function loadXpath($xpath, $namespaces = array())
    {
        if ($namespaces instanceof Zend_Config) {
            $namespaces = $namespaces->toArray();
        }

        foreach ($namespaces as $namespacePrefix => $namespaceUrl) {
            $this->_handle->registerXPathNamespace($namespacePrefix, $namespaceUrl);
        }
        return Nova::getModel('core/parser.xml.node', array('node' => $this->_handle->xpath($xpath)));
        //return new self(array('node' => $this->_handle->xpath($xpath)));
    }

    /**
     * Tells wether if this is a document node
     *
     * @return bool
     */
    public function isDocument()
    {
        return false;
    }

    /**
     * Count child nodes
     *
     * @return int
     */
    public function getChildCount()
    {
        return $this->getNode()->count();
    }

    /**
     * FIXME: PHPDoc
     *
     * @param string $name
     * @param string $value
     * @param string $namespace
     * @return One_Core_Model_Parser_Xml_Node
     */
    public function addChild($name, $value, $namespace = NULL)
    {
        $this->getNode()->addChild($name, $value, $namespace);

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @param string $name
     * @param string $value
     * @param string $namespace
     * @return One_Core_Model_Parser_Xml_Node
     */
    public function getChild($name, $value, $namespace = NULL)
    {
        $this->getNode()->addChild($name, $value, $namespace);

        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @param One_Core_Model_Parser_Xml_Node $mergeData
     * @param bool $overwrite
     * @return unknown_type
     */
    public function merge(One_Core_Model_Parser_Xml_Node $mergeData, $overwrite = true)
    {
        if ($overwrite !== false) {
            $rootNode = $this->getNode();
            foreach ($mergeData->getNode()->children() as $child) {
                $nodeName = $child->getName();
                if (isset($rootNode->$nodeName)) {
                    foreach ($child->children() as $grandChild) {

                    }
                }
            }
        }
    }
}