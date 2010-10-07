<?php

class One_Core_Model_Router_Route
    extends Zend_Controller_Router_Route
    implements One_Core_Model_Router_RouteInterface
{
    public function __construct($routeConfig, $moduleName)
    {
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
}