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

        return $this;
    }

    public function loadLayout($layoutName = null)
    {
        if ($this->_layout === null) {
            $this->_layout = $this->app()
                ->getSingleton('core/layout')
                ->setActionController($this)
            ;
        }

        $this->_layout
            ->reset()
            ->init($layoutName)
        ;

        return $this->_layout;
    }

    public function renderLayout()
    {
        $this->getResponse()
            ->setBody($this->view->render(null))
        ;

        return $this;
    }

    public function preDispatch()
    {
        $request = $this->getRequest();
        $this->app()
            ->dispatchEvent('controller.dispatch.before', array(
                'request' => $this->getRequest(),
                'action'  => $this
                ));
    }

    public function postDispatch()
    {
        $this->app()
            ->dispatchEvent('controller.dispatch.after', array(
                'request' => $this->getRequest(),
                'action'  => $this
            ));
    }

    public function getWebsiteId()
    {
        return $this->getInvokeArg('websiteId');
    }

    /**
     * TODO: PHPDoc
     *
     * @return One_Core_Model_Application
     */
    public function app()
    {
        if ($this->_app === null) {
            $this->_app = $this->getInvokeArg('applicationInstance');
        }
        return $this->_app;
    }

    /**
     * TODO: PHPDoc
     *
     * @return void
     */
    protected function _redirectSuccess($defaultRedirect = '/')
    {
        if (($redirect = $this->getRequest()->getParam('success_url', null)) === null) {
            $redirect = $defaultRedirect;
        }
        $this->_redirect($redirect);
    }

    /**
     * TODO: PHPDoc
     *
     * @return void
     */
    protected function _rediectError($defaultRedirect = '/')
    {
        if (($redirect = $this->getRequest()->getParam('error_url', null)) === null) {
            $redirect = $defaultRedirect;
        }
        $this->_redirect($redirect);
    }

    /**
     * TODO: PHPDoc
     *
     * @param string $fieldset
     * @return bool
     */
    protected function _validateFieldset($fieldset)
    {
        /** @var Zend_Form_SubForm $fieldset */
        $fieldset = $this->app()
            ->getSingleton('core/config')
            ->getFieldset($fieldset)
        ;

        if ($fieldset === null) {
            return false;
        }

        var_dump($this->getRequest()->getPost());
        return $fieldset->isValid($this->getRequest()->getPost());
    }
}