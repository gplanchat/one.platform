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
            'type'     => 'frontoffice',
            'name'     => 'page',
            'design'   => 'default',
            'template' => 'default'
            ), (array) $data);

        $basePath = implode(One::DS, array(APPLICATION_PATH, 'design', $data['type'],
            'default', 'base', 'layout'));

        $templatePath = implode(One::DS, array(APPLICATION_PATH, 'design', $data['type'],
            $data['design'], $data['template'], 'layout'));

        $this->_layoutConfiguration = new Zend_Config(array(), true);
        $files = $this->app()->getConfig('general.layout');
        foreach ($files as $module => $filename) {
            if (file_exists($basePath . One::DS . $filename)) {
                $this->_layoutConfiguration->merge(new Zend_Config_Xml($basePath . One::DS . $filename, null, true));
            }
            if (file_exists($templatePath . One::DS . $filename)) {
                $this->_layoutConfiguration->merge(new Zend_Config_Xml($templatePath . One::DS . $filename, null, true));
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

        $view = One::app()->getBlock($type, $layoutConfig['block'], $this);

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
                    $block = One::app()->getBlock($type, $childBlock, $this);

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

    public function init()
    {
        $request = $this->_actionController->getRequest();

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

    /**
     * Génération du layout en fonction de son identifiant
     *
     * @param $type
     * @return unknown_type
     */
//    public function createBlock($type)
//    {
//        if (Nova::loadClass($className = 'One_Template_' . str_replace('/', '_', $type))) {
//            $instance = new $className($this);
//        } else if (Nova::loadClass($className = strval($type))) {
//            $instance = new $className($this);
//        } else {
//            return NULL;
//        }
//        return $instance;
//    }

    /**
     * Génération du rendu du layout
     *
     * @return unknown_type
     */
//    public function render()
//    {
//        ob_start();
//        foreach ($this->_rootNodes as $node) {
//            echo $node->render();
//        }
//        $page = ob_get_contents();
//        ob_end_clean();
//        return $page;
//    }

    /**
     *
     * @return string
     */
//    public function getLayoutName()
//    {
//        return (string) $this->_layoutName;
//    }

    /**
     *
     * @return string
     */
//    public function getBasePath()
//    {
//        return (string) $this->_basePath;
//    }

    /**
     *
     * @param string $basePath
     * @return One_Layout
     */
//    public function setBasePath($basePath)
//    {
//        $this->_basePath = $basePath;
//
//        return $this;
//    }

    /**
     *
     * @param string $path
     * @return string
     */
//    public function loadTemplatePath($path)
//    {
//        $config = Nova::getConfig('frontend.design');
//
//        return ROOT_PATH . "/design/{$config->name}/{$config->template}/{$path}";
//    }

//    protected function _loadLayoutNode($layoutDefinitions, $layoutName)
//    {
//        $nodes = $layoutDefinitions->xpath("/l:layouts/l:layout[@name=\"{$layoutName}\"]");
//        if (count($nodes)) {
//            return $nodes[0];
//        }
//        return NULL;
//    }

//    protected function _applyExtends(SimpleXMLElement $layoutHandle)
//    {
//        foreach ($layoutHandle->children() as $blockHandle) {
//            $block = $this->_getBlockInstance($blockHandle);
//            if (is_null($block)) {
//                continue;
//            }
//
//            $this->_updateBlock($block, $blockHandle);
//
//            if (!isset($this->_childNodes[$blockHandle->getName()])) {
//                $this->_childNodes[$block->getName()] = $block;
//                $this->_rootNodes[$block->getName()] = $block;
//            }
//        }
//
//        foreach ($layoutHandle->reference as $blockHandle) {
//            if (!isset($this->_childNodes[strval($blockHandle['name'])])) {
//                continue;
//            }
//            $this->_updateBlock($this->_childNodes[strval($blockHandle['name'])], $blockHandle);
//        }
//    }

//    protected function _updateBlock(One_Template_Interface $block, SimpleXMLElement $blockHandle)
//    {
//        foreach ($blockHandle->children() as $childType => $childHandle) {
//            if ($childType == 'action') {
//                $this->_callAction($block, $childHandle);
//            } else if ($childType == 'block') {
//                $childBlock = $this->_getBlockInstance($childHandle);
//                if (is_null($childBlock) || isset($this->_childNodes[strval($childHandle['name'])])) {
//                    continue;
//                }
//                $this->_childNodes[strval($childHandle['name'])] = $childBlock;
//                $block->addChild($childBlock);
//
//                $this->_updateBlock($childBlock, $childHandle);
//            }
//        }
//    }
//
//    protected function _getBlockName(SimpleXMLElement $blockHandle)
//    {
//        if ($name = $blockHandle->attributes()->name) {
//            return strval($name);
//        }
//        return NULL;
//    }
//
//    protected function _getBlockInstance(SimpleXMLElement $blockHandle)
//    {
//        $instance = NULL;
//        if ($type = (string) $blockHandle->attributes()->type) {
//            if (!($instance = $this->createBlock($type))) {
//                return NULL;
//            }
//            $instance
//                ->setName($this->_getBlockName($blockHandle))
//                ->setTemplate($blockHandle->attributes()->template)
//            ;
//        }
//        return $instance;
//    }
//
//    protected function _callAction(One_Template_Interface $block, SimpleXMLElement $blockHandle)
//    {
//        try {
//            $reflectionMethod = new ReflectionMethod($block, $blockHandle['method']);
//            $reflectionParameters = $reflectionMethod->getParameters();
//
//            $parameters = array();
//            foreach ($reflectionParameters as $parameter) {
//                if (isset($blockHandle->{$parameter->name})) {
//                    if ($blockHandle->{$parameter->name}['locale'] &&
//                        ($locale = Nova::loadLocale($blockHandle->{$parameter->name}['locale'])) !== false) {
//                        $parameters[] = $locale->_((string) $blockHandle->{$parameter->name});
//                    } else {
//                        $parameters[] = (string) $blockHandle->{$parameter->name};
//                    }
//                } else {
//                    $parameters[] = $parameter->getDefaultValue();
//                }
//            }
//            $reflectionMethod->invokeArgs($block, $parameters);
//        } catch (ReflectionException $e) {
//            //throw $e;
//        }
//
//        return $this;
//    }
//
//    public function getChild($name)
//    {
//        if (isset($this->_childNodes[(string) $name])) {
//            return $this->_childNodes[(string) $name];
//        }
//        return NULL;
//    }
}
