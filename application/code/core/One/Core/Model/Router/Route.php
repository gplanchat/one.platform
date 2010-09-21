<?php

class One_Core_Model_Router_Route
    extends Zend_Controller_Router_Route_Chain
{
    public function __construct($routeConfig, $baseRoute, $moduleName)
    {
        if (isset($routeConfig['path'])) {
            $path = $routeConfig['path'];
        } else {
            $path = '';
        }

        if ($baseRoute !== null) {
            $this->chain($baseRoute);
        }
        $this->chain(new Zend_Controller_Router_Route_Static($path))
            ->chain(new Zend_Controller_Router_Route('/:controller/:action/*', array(
                'module'     => $moduleName,
                'controller' => 'index',
                'action'     => 'index'
                )));
    }
}