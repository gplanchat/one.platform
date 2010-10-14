<?php

class One_Core_Model_Router_Route
    extends Zend_Controller_Router_Route
    implements One_Core_Model_Router_RouteInterface
{
    protected $_app = null;

    public function __construct($routeConfig, $moduleName, One_Core_Model_Application $app)
    {
        $this->_app = $app;

        if ($routeConfig instanceof Zend_Config) {
            $routeConfig = $routeConfig->toArray();
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