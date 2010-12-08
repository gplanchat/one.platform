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
 * Base block class
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_core
 */
abstract class One_Core_BlockAbstract
    extends One_Core_Object
    implements Zend_View_Interface
{
    protected $_name = '';

    protected $_childNodes = array();

    protected $_childIndex = array();

    protected $_basePath = '';

    protected $_scriptPath = '';

    protected $_layout = null;

    protected $_renderingClass = null;

    private $_loaders = array();
    private $_helper = array();
    private $_filter = array();
    private $_path = array();

    protected $_escape = 'htmlspecialchars';
    protected $_encoding = 'UTF-8';

    protected $_loaderTypes = array('helper', 'filter');

    public function __construct($module, Array $options = array(), One_Core_Model_Application $application = null, One_Core_Model_Layout $layout = null)
    {
        if ($layout !== null) {
            $this->_layout = $layout;
            $this->_renderingClass = $layout->getRenderingClass();
        }

        parent::__construct($module, $options, $application);
    }

    protected function _construct($options)
    {
        $config = $this->app()->getOption($this->_renderingClass);
        $basePath = implode(One::DS, array(APPLICATION_PATH, 'design', $this->_renderingClass,
            $config['layout']['design'], $config['layout']['template']));

        $this->setBasePath($basePath)
            ->setScriptPath($basePath . One::DS . 'template')
        ;

        if (isset($options['name'])) {
            $this->setName($options['name']);
            unset($options['name']);
        } else {
            $this->setName(uniqid('block_'));
        }

        if (isset($options['block'])) {
            if (!is_array($options['block']) || !is_int(key($options['block']))) {
                $options['block'] = array($options['block']);
            }
            foreach ($options['block'] as $block) {
                $this->_buildChildNode($block);
            }
            unset($options['block']);
        }

        if (isset($options['action'])) {
            if (!is_array($options['action']) || !is_int(key($options['action']))) {
                $options['action'] = array($options['action']);
            }
            foreach ($options['action'] as $action) {
                $this->_executeAction($action);
            }
            unset($options['action']);
        }

        return parent::_construct($options);
    }

    protected function _buildChildNode($node)
    {
        if (!isset($node['type'])) {
            return $this;
        }

        $childBlock = $this->app()
            ->getBlock($node['type'], $node, $this->_layout)
            ->setBasePath($this->getBasePath())
            ->setScriptPath($this->getScriptPath())
        ;

        if (isset($node['name'])) {
            $name = $node['name'];
        } else {
            $name = uniqid('block_');
        }
        $this->appendChild($name, $childBlock);

        $this->_layout->registerBlock($name, $childBlock);

        return $this;
    }

    public function _executeAction($action)
    {
        $reflectionObject = new ReflectionObject($this);
        if ($reflectionObject->hasMethod($action['method'])) {
            $reflectionMethod = $reflectionObject->getMethod($action['method']);

            $callParams = array_pad(array(), $reflectionMethod->getNumberOfParameters(), null);
            $parameters = array();
            foreach ($reflectionMethod->getParameters() as $reflectionParam) {
                $parameters[$reflectionParam->getName()] = $reflectionParam;
                if ($reflectionParam->isOptional()) {
                    $callParams[$reflectionParam->getPosition()] = $reflectionParam->getDefaultValue();
                }
            }
            if (isset($action['params'])) {
                foreach ($action['params'] as $paramName => $paramValue) {
                    if (!isset($parameters[$paramName])) {
                        continue;
                    }

                    if (isset($paramValue)) {
                        $callParams[$parameters[$paramName]->getPosition()] = $paramValue;
                    } else {
                        $callParams[$parameters[$paramName]->getPosition()] = null;
                    }
                }
            }

            $reflectionMethod->invokeArgs($this, $callParams);
        } else {
            $this->__call($action['method'], array_values($action['params']));
        }

        return $this;
    }

    public function _call($method, $params)
    {
        try {
//            var_dump(array($method, $params));
            return $this->helper('core/standard')
                ->__call($method, $params)
            ;
//        } catch (Zend_Loader_Exception $e) {
        } catch (Zend $e) {
            return parent::_call($method, $params);
        }
    }

    public function helper($identifier)
    {
        return $this->app()
            ->getHelper($identifier, array(), $this->getLayout(), $this)
        ;
    }

    /**
     * @return One_Core_Model_Layout
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->getLayout()->getRequest();
    }

    public function setLayout(One_Core_Model_Layout $layout)
    {
        $this->_layout = $layout;

        return $this;
    }

    public function getChildNode($childName)
    {
        if (isset($this->_childNodes[$childName])) {
            return $this->_childNodes[$childName];
        }
        return null;
    }

    public function getAllChildNodes()
    {
        return $this->_childNodes;
    }

    public function renderChild($childName, $templateName = null)
    {
        $child = $this->getChildNode($childName);

        if ($child !== null) {
            return $child->render($templateName);
        }
        return '';
    }

    public function appendChild($childName, One_Core_BlockAbstract $childNode)
    {
        $this->_childNodes[(string) $childName] = $childNode;
        $this->_childIndex[] = $childName;

        return $this;
    }

    public function prependChild($childName, One_Core_BlockAbstract $childNode)
    {
        $this->_childNodes[(string) $childName] = $childNode;
        array_unshift($this->_childIndex, $childName);

        return $this;
    }

    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function escape($string)
    {
        if (in_array($this->_escape, array('htmlspecialchars', 'htmlentities'))) {
            return call_user_func($this->_escape, $string, ENT_COMPAT, $this->_encoding);
        }

        return call_user_func($this->_escape, $string);
    }

    public function render($name)
    {
        $obLevel = ob_get_level();

        $this->_beforeRender($name);
        $content = $this->_render($name);
        $this->_afterRender($name, $content);

        while (ob_get_level() < $obLevel) {
            ob_end_clean();
        }

        return $content;
    }

    protected function _beforeRender()
    {
        return $this;
    }

    abstract protected function _render();

    protected function _afterRender()
    {
        return $this;
    }

    public function getEngine()
    {
    }

    public function setScriptPath($path)
    {
        $this->_scriptPath = $path;

        return $this;
    }

    public function getScriptPath()
    {
        return $this->_scriptPath;
    }

    public function getScriptPaths()
    {
        return $this->_scriptPath;
    }

    public function setBasePath($path, $classPrefix = 'Zend_View')
    {
        $this->_basePath = $path;

        return $this;
    }

    public function getBasePath()
    {
        return $this->_basePath;
    }

    public function addBasePath($path, $classPrefix = 'Zend_View')
    {
        $this->setBasePath($path, $classPrefix);

        return $this;
    }

    public function assign($spec, $value = null)
    {
        return $this->setData($spec, $value);
    }

    public function clearVars()
    {
        return $this->unsetData();
    }

    public function setEncoding($encoding)
    {
        $this->_encoding = $encoding;

        return $this;
    }

    public function getEncoding()
    {
        return $this->_encoding;
    }

    public function getRenderingClass()
    {
        return $this->_renderingClass;
    }

    public function setRenderingClass($renderingClass)
    {
        $this->_renderingClass = $renderingClass;

        return $this;
    }
}