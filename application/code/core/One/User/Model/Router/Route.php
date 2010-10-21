<?php

class One_User_Model_Router_Route
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

        if (!isset($routeConfig['path'])) {
            $routeConfig['path'] = 'user';
        }

        parent::__construct("/{$routeConfig['path']}/:username/:controller/:action/*", array(
            'module'     => $moduleName,
            'controller' => 'account',
            'action'     => 'index',
            'username'   => null,
//            'layout'     => 'user.account'
            ));
    }

    public function app()
    {
        return $this->_app;
    }
}