<?php

class One_User_Model_Router_Route
    extends Zend_Controller_Router_Route
    implements One_Core_Model_Router_RouteInterface
{
    public function __construct($routeConfig, $baseRoute, $moduleName)
    {
        parent::__construct('user/:username/:controller/:action', array(
            'module'     => 'One_User',
            'controller' => 'account',
            'action'     => 'view'
            ));
    }
}