<?php

class One_Core_ControllerAbstract
    extends Zend_Controller_Action
{
    /**
     *
     * @var One_Core_Model_Layout
     */
    protected $_layout = null;

    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * Initialize Layout object
     *
     * @return One_Core_Model_Layout
     * @throws Zend_Controller_Exception if base layout directory does not exist
     */
    public function init()
    {
        $this->_initLayout('core/layout');
    }

    protected function _initLayout($class)
    {
        $this->_layout = $this->getApplicationInstance()
            ->getSingleton($class)
        ;
    }

    public function preDispatch()
    {
    }

    public function postDispatch()
    {
//        var_dump(get_class($this->getApplicationInstance()->getModel('core/testing.rewrite')));

        $request = $this->getRequest();
        $layoutName = implode('.', array(
            $request->getParam('path'),
            $request->getParam('controller'),
            $request->getParam('action')
            ));

        $this->getLayout()
            ->render($layoutName)
        ;
    }

    public function getApplicationInstance()
    {
        return $this->getInvokeArg('applicationInstance');
    }

    public function getWebsiteId()
    {
        return $this->getInvokeArg('websiteId');
    }
}