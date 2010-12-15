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
 * Page layout loader and management class.
 *
 * @since       0.1.1
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class One_Core_Model_Layout
    extends One_Core_Object
{
    protected $_layoutConfiguration = null;

    protected $_actionController = null;

    protected $_blocks = array();

    protected $_baseScriptPath = null;
    protected $_defaultScriptPath = null;
    protected $_templateScriptPath = null;

    protected $_renderingClass = null;

    /**
     *
     * @param string $filename
     * @param string $layoutName
     * @return void
     */
    public function _construct($data)
    {
        if ($data instanceof Zend_Config) {
            $data = $data->toArray();
        }

        $data = array_merge(array(
            'name'     => 'page',
            'class'    => 'frontoffice',
            'design'   => 'default',
            'template' => 'default'
            ), (array) $this->app()->getConfig('system.layout'), (array) $data);

        $this->_renderingClass = $data['class'];

        $this->_baseScriptPath = realpath(implode(One::DS, array(APPLICATION_PATH, 'design', $this->_renderingClass,
            'default', 'base', 'template')));
        $this->_defaultScriptPath = realpath(implode(One::DS, array(APPLICATION_PATH, 'design', $this->_renderingClass,
            $data['design'], 'default', 'template')));
        $this->_templateScriptPath = realpath(implode(One::DS, array(APPLICATION_PATH, 'design', $this->_renderingClass,
            $data['design'], $data['template'], 'template')));

        $baseLayoutPath = realpath(dirname($this->_baseScriptPath) . One::DS . 'layout');
        $defaultLayoutPath = realpath(dirname($this->_defaultScriptPath) . One::DS . 'layout');
        $templateLayoutPath = realpath(dirname($this->_templateScriptPath) . One::DS . 'layout');

        $this->_layoutConfiguration = new Zend_Config(array(), true);
        $files = $this->app()->getConfig('general.layout');
        foreach ($files as $module => $filename) {
            if (file_exists($baseLayoutPath . One::DS . $filename)) {
                $this->_layoutConfiguration->merge(new Zend_Config_Xml($baseLayoutPath . One::DS . $filename, null, true));
            }
            if (file_exists($defaultLayoutPath . One::DS . $filename)) {
                $this->_layoutConfiguration->merge(new Zend_Config_Xml($defaultLayoutPath . One::DS . $filename, null, true));
            }
            if (file_exists($templateLayoutPath . One::DS . $filename)) {
                $this->_layoutConfiguration->merge(new Zend_Config_Xml($templateLayoutPath . One::DS . $filename, null, true));
            }
        }

        $this->_layoutConfiguration->setReadOnly();

        return parent::_construct(array());
    }

    public function buildView($layoutName)
    {
        $layoutConfig = new Zend_Config(array(), true);

        if (($defaultConfig = $this->_layoutConfiguration->default) === null) {
            return null;
        }
        $layoutConfig->merge($defaultConfig);

        if (is_array($layoutName)) {
            $layoutList = $layoutName;
        } else {
            $layoutList = func_get_args();
        }
        foreach ($layoutList as $layoutName) {
            if (($namedConfig = $this->_layoutConfiguration->{$layoutName}) === null) {
                return null;
            }
            if ($namedConfig instanceof Zend_Config) {
                $layoutConfig->merge($namedConfig);
            }
        }

        $layoutConfig = $layoutConfig->toArray();

        $type = $layoutConfig['block']['type'];
        unset($layoutConfig['block']['type']);

        $view = $this->app()->getBlock($type, $layoutConfig['block'], $this);

        if (isset($layoutConfig['reference'])) {
            if (!is_int(key($layoutConfig['reference']))) {
                $layoutConfig['reference'] = array($layoutConfig['reference']);
            }
            foreach ($layoutConfig['reference'] as $reference) {
                if (!isset($this->_blocks[$reference['name']])) {
                    continue;
                }

                if (isset($reference['block'])) {
                    if (!is_int(key($reference['block']))) {
                        $reference['block'] = array($reference['block']);
                    }

                    foreach ($reference['block'] as $childBlock) {
                        $type = $childBlock['type'];
                        unset($childBlock['type']);
                        $block = $this->app()->getBlock($type, $childBlock, $this);

                        $this->_blocks[$reference['name']]->appendChild($block->getName(), $block);
                        $this->registerBlock($block->getName(), $block);
                    }
                }

                if (isset($reference['action'])) {
                    if (!is_int(key($reference['action']))) {
                        $reference['action'] = array($reference['action']);
                    }

                    foreach ($reference['action'] as $action) {
                        // FIXME: Bad implementation
                        $this->_blocks[$reference['name']]->_executeAction($action);
                    }
                }
            }
        }

        return $view;
    }

    public function setActionController(Zend_Controller_Action $actionController)
    {
        $this->_actionController = $actionController;

        return $this;
    }

    public function init($layoutName = null)
    {
        $request = $this->_actionController->getRequest();

        if ($layoutName === null && ($layoutName = $request->getParam('layout')) === null) {
            $path = $request->getParam('path');
            if (empty($path)) {
                $layoutName = implode('.', array(
                    $request->getParam('controller', 'default'),
                    $request->getParam('action', 'default')
                    ));
            } else {
                $layoutName = implode('.', array(
                    $path,
                    $request->getParam('controller'),
                    $request->getParam('action')
                    ));
            }
        }

        $view = $this->buildView($layoutName);
        if ($view === null) {
            $this->app()->throwException('core/configuration-error', "Layout '{$layoutName}' not declared.");
        }

        $this->_actionController->view = $view;

        return $this;
    }

    public function reset()
    {
        $this->_actionController->view = null;
        $this->_blocks = array();

        return $this;
    }

    public function registerBlock($name, One_Core_BlockAbstract $block)
    {
        if (isset($this->_blocks[(string) $name])) {
            $this->app()->throwException('core/invalid-method-call',
                "Block '$name' is already defined in the layout");
        }
        $this->_blocks[(string) $name] = $block;

        return $this;
    }

    public function getBlock($name)
    {
        if (isset($this->_blocks[(string) $name])) {
            return $this->_blocks[(string) $name];
        }
        return null;
    }

    public function getAllBlocks()
    {
        return $this->_blocks;
    }

    public function getRequest()
    {
        return $this->_actionController->getRequest();
    }

    public function getBaseScriptPath()
    {
        return $this->_baseScriptPath;
    }

    public function getDefaultScriptPath()
    {
        return $this->_defaultScriptPath;
    }

    public function getTemplateScriptPath()
    {
        return $this->_templateScriptPath;
    }

    public function getRenderingClass()
    {
        return $this->_renderingClass;
    }
}
