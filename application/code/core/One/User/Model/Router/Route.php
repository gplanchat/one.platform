<?php

class One_User_Model_Router_Route
    extends Zend_Controller_Router_Route
    implements One_Core_Model_Router_RouteInterface
{
    public function __construct($routeConfig, $moduleName)
    {
        if (!isset($routeConfig['path'])) {
            $routeConfig['path'] = 'user';
        }

        parent::__construct("/{$routeConfig['path']}/:username/:controller/:action/*", array(
            'module'     => $moduleName,
            'controller' => 'account',
            'action'     => 'index',
            'username'   => null
            ));
    }
}