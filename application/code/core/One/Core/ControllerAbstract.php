<?php
/**
 * This file is part of One.Platform
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Grégory PLANCHAT nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */

/**
 * Base controller
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_core
 */
class One_Core_ControllerAbstract
    extends Zend_Controller_Action
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

    /**
     * @return One_Core_Model_Layout
     */
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
        if (($redirect = $this->getRequest()->getPost('success')) === null) {
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
        if (($redirect = $this->getRequest()->getPost('error')) === null) {
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