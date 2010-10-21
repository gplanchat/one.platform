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
            $this->loadLayout();
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
        if (($redirect = $this->getRequest()->getParam('success')) === null) {
            $redirect = $defaultRedirect;
        }
        $this->_redirect($redirect);
    }

    /**
     * TODO: PHPDoc
     *
     * @return void
     */
    protected function _redirectError($defaultRedirect = '/')
    {
        if (($redirect = $this->getRequest()->getParam('error')) === null) {
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
    protected function _validateFieldset($fieldsetName)
    {
        /** @var Zend_Form_SubForm $fieldset */
        $fieldset = $this->app()
            ->getSingleton('core/config')
            ->getFieldset($fieldsetName)
        ;

        if ($fieldset === null) {
            return false;
        }

        $status = $fieldset->isValid($this->getRequest()->getPost($fieldsetName));

        if ($status !== true) {
            return $fieldset->getMessages();
        }

        return true;
    }

    /**
     * TODO: PHPDoc
     *
     * @param string $fieldset
     * @return bool
     */
    protected function _validateForm($form)
    {
        /** @var Zend_Form $form */
        $form = $this->app()
            ->getSingleton('core/config')
            ->getForm($form)
        ;

        if ($form === null) {
            return false;
        }

        $status = $form->isValid($this->getRequest()->getPost());

        if ($status !== true) {
            return $form->getMessages();
        }

        return true;
    }
}