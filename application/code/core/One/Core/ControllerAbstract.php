<?php

class One_Core_ControllerAbstract
    extends Zend_Controller_Action
    implements One_Core_ObjectInterface
{
    /**
     *
     * @var One_Core_Model_Layout
     */
    protected $_layout = null;

    /**
     *
     * @var One_Core_Model_Application
     */
    protected $_app = null;

    public function getLayout()
    {
        if ($this->_layout === null) {
            $this->_initLayout();
        }
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
        $this->view = null;

        $this->_initLayout();

        return $this;
    }

    protected function _initLayout($class = null)
    {
        if ($this->_layout instanceof One_Core_Model_Layout) {
            return $this->_layout;
        }

        if ($class === null) {
            if (($class = $this->getInvokeArg('layoutClass')) === null) {
                $class = 'core/layout';
            }
        }

        $this->_layout = $this->app()
            ->getSingleton($class)
//            ->setViewRendererHelper($this->_helper->getHelper('viewRenderer'))
            ->setActionController($this)
        ;

        return $this->_layout;
    }

    public function preDispatch()
    {
        $request = $this->getRequest();
        $this->app()
            ->dispatchEvent('controller.dispatch.before', array(
                'request' => $this->getRequest(),
                'action'  => $this
                ));

        $this->_layout
            ->reset()
            ->init()
        ;
    }

    public function postDispatch()
    {
        $this->app()
            ->dispatchEvent('controller.dispatch.after', array(
                'request' => $this->getRequest(),
                'action'  => $this
            ));

//        echo $this->view->render(null);
    }

    public function getWebsiteId()
    {
        return $this->getInvokeArg('websiteId');
    }

    /**
     * @return One_Core_Model_Application
     */
    public function app()
    {
        if ($this->_app === null) {
            $this->_app = $this->getInvokeArg('applicationInstance');
        }
        return $this->_app;
    }
}