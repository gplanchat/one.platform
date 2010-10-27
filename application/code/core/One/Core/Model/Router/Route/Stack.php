<?php


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
        $prefix = 'core';
        if (isset($data['prefix']) && !empty($data['prefix'])) {
            $prefix = (string) $data['prefix'];
        }

        if (isset($this->_routes[$prefix])) {
            return $this->_routes[$prefix]->assemble($data, $reset, $encode);
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