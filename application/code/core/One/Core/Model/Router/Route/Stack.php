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
 * Module route stack
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_core
 */
class One_Core_Model_Router_Route_Stack
    extends Zend_Controller_Router_Route_Abstract
{
    protected $_routes = array();
    protected $_index = array();

    protected $_urlDelimiter = '/';

    private $_app = null;

    public function push($name, Zend_Controller_Router_Route_Abstract $route)
    {
        if (isset($this->_routes[$name])) {
            return $this;
        }
        $this->_routes[$name] = $route;
        $this->_index[] = $name;

        return $this;
    }

    public function pushBefore($name, Zend_Controller_Router_Route_Abstract $route, $before)
    {
        if (isset($this->_routes[$name])) {
            return $this;
        }
        $this->_routes[$name] = $route;

        $index = array_search($before, $this->_index);
        if ($index === false) {
            return $this->push($route, $name);
        }
        array_splice($this->_index, $index, 0, array($name));

        return $this;
    }

    public function pushAfter($name, Zend_Controller_Router_Route_Abstract $route, $after)
    {
        if (isset($this->_routes[$name])) {
            return $this;
        }
        $this->_routes[$name] = $route;

        $index = array_search($after, $this->_index);
        if ($index === false) {
            return $this->push($route, $name);
        }
        array_splice($this->_index, $index + 1, 0, array($name));

        return $this;
    }

    public function match($path)
    {
        foreach ($this->_index as $routeName) {
            if ($this->_routes[$routeName]->getVersion() === 1) {
                $match = $this->_routes[$routeName]->match($path->getPathInfo());
            } else {
                $match = $this->_routes[$routeName]->match($path);
            }
            if ($match !== false) {
                return $match;
            }
        }
        return false;
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        $path = 'core';
        if (isset($data['path']) && !empty($data['path'])) {
            $path = (string) $data['path'];
            unset($data['path']);
        }

        if (isset($this->_routes[$path])) {
            return $this->_routes[$path]->assemble($data, $reset, $encode);
        }
        return false;
    }

    public static function getInstance(Zend_Config $config)
    {
        return new self();
    }

    public function app(One_Core_Model_Application $app = null)
    {
        if ($app !== null) {
            $this->_app = $app;
        }
        return $this->_app;
    }
}