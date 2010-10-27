<?php

class One_Cms_Model_Router_Route
    extends Zend_Controller_Router_Route_Abstract
    implements One_Core_Model_Router_RouteInterface
{
    protected $_app = null;
    protected $_bo = null;

    protected $_urlVariable = ':';
    protected $_urlDelimiter = '/';
    protected $_regexDelimiter = '#';
    protected $_defaultRegex = null;

    public function __construct($moduleName, Array $routeConfig = array(), One_Core_Model_Application $app = null)
    {
        $this->_app = $app;
        $this->_bo = $this->app()->getSingleton('cms/page');
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        if (isset($data['page-id'])) {
        }
        $this->app()->throwException('core/unimplemented');
    }

    public function match($path, $partial = false)
    {
        $pathInfo = $path->getPathInfo();
        if (!$partial) {
            $pathInfo = trim($pathInfo, $this->_urlDelimiter);
        }

        $this->_bo->load($pathInfo, 'path');
        if (!$this->_bo->getId()) {
            return false;
        }

        $this->setMatchedPath($path->getPathInfo());

        return array(
            'module'     => 'One_Cms',
            'controller' => 'display',
            'action'     => 'page',
            'page-id'    => $this->_bo->getId(),
            'layout'     => 'cms.page'
            );
    }

    /**
     * Instantiates route based on passed Zend_Config structure
     *
     * @param Zend_Config $config Configuration object
     */
    public static function getInstance(Zend_Config $config)
    {
        if (isset($config->app)) {
            $app = $config->app;
        } else {
            $app = One::app();
        }
        return new self($config, null, $app);
    }

    public function app()
    {
        return $this->_app;
    }
}