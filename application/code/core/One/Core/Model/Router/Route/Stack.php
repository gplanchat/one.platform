<?php


class One_Core_Model_Router_Route_Stack
//    implements Iterator
{
    protected $_routes = array();
    protected $_index = array();

    public function push(Zend_Controller_Router_Route_Abstract $route, $name)
    {
        if (isset($this->_routes[$name])) {
            return $this;
        }
        $this->_routes[$name] = $route;
        $this->_index[] = $name;

        return $this;
    }

    public function pushBefore(Zend_Controller_Router_Route_Abstract $route, $name, $before)
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

    public function pushAfter(Zend_Controller_Router_Route_Abstract $route, $name, $after)
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

    public function registerRoutes(Zend_Controller_Router_Abstract $router)
    {
        foreach ($this->_index as $routeName) {
            $router->addRoute($routeName, $this->_routes[$routeName]);
        }
    }
}