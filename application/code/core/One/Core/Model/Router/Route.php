<?php

class One_Core_Model_Router_Route
    extends Zend_Controller_Router_Route
{
    public function __construct($routeConfig, $moduleName)
    {
        if (isset($routeConfig['path'])) {
            $path = "/{$routeConfig['path']}/:controller/:action/*";
        } else {
            $path = '/:controller/:action/*';
        }

        parent::__construct($path, array(
                'module'     => $moduleName,
                'controller' => 'index',
                'action'     => 'index'
                ));
    }
}