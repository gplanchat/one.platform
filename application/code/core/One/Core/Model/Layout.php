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
    extends Zend_Layout
{
    const NS_LAYOUT = 'http://xml.xnova-ng.org/layout/1.0/';
    const NS_XINCLUDE = 'http://www.w3.org/2001/XInclude';

    protected $_layoutName = NULL;
    protected $_childNodes = array();
    protected $_rootNodes = array();
    protected $_basePath = NULL;

    /**
     * Création d'une instance de layout.
     *
     * @param $layoutName
     * @return unknown_type
     */
    public static function factory($layoutName)
    {
        $config = Nova::getConfig('frontend.design');
        $layoutPath = ROOT_PATH . "/design/{$config['name']}/layout/{$config['layout']}";

        return new self($layoutPath, $layoutName);
    }

    /**
     *
     * @param string $filename
     * @param string $layoutName
     * @return void
     */
    public function __construct($filename, $layoutName)
    {
        $config = Nova::getConfig('frontend.design');
        $this->_basePath = ROOT_PATH . "/design/{$config['name']}/{$config['template']}";

        $this->_layoutName = $layoutName;
        $layoutDefinitions = simplexml_load_file($filename);
        $layoutDefinitions->registerXPathNamespace('l', self::NS_LAYOUT);
        $layoutDefinitions->registerXPathNamespace('xi', self::NS_XINCLUDE);

        $layoutHandle = $this->_loadLayoutNode($layoutDefinitions, $layoutName);

        if (!isset($layoutHandle)) {
            throw new UnexpectedValueException("No such layout `{$layoutName}`");
        }

        $layoutHandles = array($layoutHandle);
        while ($layoutHandle = $this->_loadLayoutNode($layoutDefinitions, $layoutHandle['extends'])) {
            array_unshift($layoutHandles, $layoutHandle);
        }

        foreach ($layoutHandles as $layoutHandle) {
            $this->_applyExtends($layoutHandle);
        }
    }

    /**
     * Génération du layout en fonction de son identifiant
     *
     * @param $type
     * @return unknown_type
     */
    public function createBlock($type)
    {
        if (Nova::loadClass($className = 'One_Template_' . str_replace('/', '_', $type))) {
            $instance = new $className($this);
        } else if (Nova::loadClass($className = strval($type))) {
            $instance = new $className($this);
        } else {
            return NULL;
        }
        return $instance;
    }

    /**
     * Génération du rendu du layout
     *
     * @return unknown_type
     */
    public function render()
    {
        ob_start();
        foreach ($this->_rootNodes as $node) {
            echo $node->render();
        }
        $page = ob_get_contents();
        ob_end_clean();
        return $page;
    }

    /**
     *
     * @return string
     */
    public function getLayoutName()
    {
        return (string) $this->_layoutName;
    }

    /**
     *
     * @return string
     */
    public function getBasePath()
    {
        return (string) $this->_basePath;
    }

    /**
     *
     * @param string $basePath
     * @return One_Layout
     */
    public function setBasePath($basePath)
    {
        $this->_basePath = $basePath;

        return $this;
    }

    /**
     *
     * @param string $path
     * @return string
     */
    public function loadTemplatePath($path)
    {
        $config = Nova::getConfig('frontend.design');

        return ROOT_PATH . "/design/{$config->name}/{$config->template}/{$path}";
    }

    protected function _loadLayoutNode($layoutDefinitions, $layoutName)
    {
        $nodes = $layoutDefinitions->xpath("/l:layouts/l:layout[@name=\"{$layoutName}\"]");
        if (count($nodes)) {
            return $nodes[0];
        }
        return NULL;
    }

    protected function _applyExtends(SimpleXMLElement $layoutHandle)
    {
        foreach ($layoutHandle->children() as $blockHandle) {
            $block = $this->_getBlockInstance($blockHandle);
            if (is_null($block)) {
                continue;
            }

            $this->_updateBlock($block, $blockHandle);

            if (!isset($this->_childNodes[$blockHandle->getName()])) {
                $this->_childNodes[$block->getName()] = $block;
                $this->_rootNodes[$block->getName()] = $block;
            }
        }

        foreach ($layoutHandle->reference as $blockHandle) {
            if (!isset($this->_childNodes[strval($blockHandle['name'])])) {
                continue;
            }
            $this->_updateBlock($this->_childNodes[strval($blockHandle['name'])], $blockHandle);
        }
    }

    protected function _updateBlock(One_Template_Interface $block, SimpleXMLElement $blockHandle)
    {
        foreach ($blockHandle->children() as $childType => $childHandle) {
            if ($childType == 'action') {
                $this->_callAction($block, $childHandle);
            } else if ($childType == 'block') {
                $childBlock = $this->_getBlockInstance($childHandle);
                if (is_null($childBlock) || isset($this->_childNodes[strval($childHandle['name'])])) {
                    continue;
                }
                $this->_childNodes[strval($childHandle['name'])] = $childBlock;
                $block->addChild($childBlock);

                $this->_updateBlock($childBlock, $childHandle);
            }
        }
    }

    protected function _getBlockName(SimpleXMLElement $blockHandle)
    {
        if ($name = $blockHandle->attributes()->name) {
            return strval($name);
        }
        return NULL;
    }

    protected function _getBlockInstance(SimpleXMLElement $blockHandle)
    {
        $instance = NULL;
        if ($type = (string) $blockHandle->attributes()->type) {
            if (!($instance = $this->createBlock($type))) {
                return NULL;
            }
            $instance
                ->setName($this->_getBlockName($blockHandle))
                ->setTemplate($blockHandle->attributes()->template)
            ;
        }
        return $instance;
    }

    protected function _callAction(One_Template_Interface $block, SimpleXMLElement $blockHandle)
    {
        try {
            $reflectionMethod = new ReflectionMethod($block, $blockHandle['method']);
            $reflectionParameters = $reflectionMethod->getParameters();

            $parameters = array();
            foreach ($reflectionParameters as $parameter) {
                if (isset($blockHandle->{$parameter->name})) {
                    if ($blockHandle->{$parameter->name}['locale'] &&
                        ($locale = Nova::loadLocale($blockHandle->{$parameter->name}['locale'])) !== false) {
                        $parameters[] = $locale->_((string) $blockHandle->{$parameter->name});
                    } else {
                        $parameters[] = (string) $blockHandle->{$parameter->name};
                    }
                } else {
                    $parameters[] = $parameter->getDefaultValue();
                }
            }
            $reflectionMethod->invokeArgs($block, $parameters);
        } catch (ReflectionException $e) {
            //throw $e;
        }

        return $this;
    }

    public function getChild($name)
    {
        if (isset($this->_childNodes[(string) $name])) {
            return $this->_childNodes[(string) $name];
        }
        return NULL;
    }
}
