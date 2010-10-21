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
 * Page layout loader and management class.
 *
 * @since       0.1.1
 *
 * @access      public
 * @author      gplanchat
 * @category    Layout
 * @package     One
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
            'design'   => 'default',
            'template' => 'default'
            ), (array) $data);

        $this->_renderingClass = $this->app()->getOption('system.rendering.class');
        if ($this->_renderingClass === null) {
            $this->_renderingClass = 'frontoffice';
        }

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
        if (($namedConfig = $this->_layoutConfiguration->{$layoutName}) === null) {
            return null;
        }
        $layoutConfig->merge($namedConfig);

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

                if (!is_int(key($reference['block']))) {
                    $reference['block'] = array($reference['block']);
                }

                foreach ($reference['block'] as $childBlock) {
                    $type = $childBlock['type'];
                    unset($childBlock['type']);
                    $block = $this->app()->getBlock($type, $childBlock, $this);

                    $this->_blocks[$reference['name']]->appendChild($block->getName(), $block);
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
