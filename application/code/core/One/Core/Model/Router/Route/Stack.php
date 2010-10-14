<?php


class One_Core_Model_Router_Route_Stack
    extends Zend_Controller_Router_Route_Abstract
{
    protected $_routes = array();
    protected $_index = array();

    protected $_urlDelimiter = '/';

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
        throw $this->app()->throwException('core/unimplemented', __METHOD__ . ' not implemented.');
    }

    public static function getInstance(Zend_Config $config)
    {
        return new self();
    }
}