<?php

class One_Core_Model_Router_Route
    extends Zend_Controller_Router_Route
    implements One_Core_Model_Router_RouteInterface
{
    protected $_app = null;

    public function __construct($moduleName, Array $routeConfig = array(), One_Core_Model_Application $app = null)
    {
        $this->_app = $app;

        if (isset($routeConfig['module'])) {
            $moduleName = $routeConfig['module'];
        } else {
            $class = get_class($this);
            $moduleName = substr($class, 0, strpos($class, '_Model_'));
        }

        $modulePath = strtolower($moduleName);
        if (isset($routeConfig['path'])) {
            $modulePath = $routeConfig['path'];
        }
        $path = "/{$modulePath}/:controller/:action/*";

        parent::__construct($path, array(
                'module'     => $moduleName,
                'controller' => 'index',
                'action'     => 'index',
                'path'       => $modulePath
                ));
    }

    public function app()
    {
        return $this->_app;
    }
}