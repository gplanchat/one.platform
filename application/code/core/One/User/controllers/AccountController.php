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
 *     - Neither the name of Zend Technologies USA, Inc. nor the names of its
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
 * User account controller
 *
 * @access      public
 * @author      gplanchat
 * @category    User
 * @package     One_User
 * @subpackage  One_User
 */
class One_User_AccountController
    extends One_User_Controller_AuthenticatedAbstract
{
    /**
     * TODO: PHPDoc
     *
     */
    public function indexAction()
    {
        $this->_redirectIfNotLoggedIn();

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * TODO: PHPDoc
     *
     */
    public function loginAction()
    {
        $this->_redirectIfLoggedIn();

        $this->loadLayout();
        $this->renderLayout();
    }

    public function loginAjaxAction()
    {
        $this->_redirectIfLoggedIn();

        $identity = $this->getRequest()->getParam('identity');

        $user = $this->app()
            ->getSingleton('user/entity.authentication')
            ->loadByIdentity($identity)
        ;

        $datas = array(
            'exists' => false,
            'salt'   => null
            );

        if ($user->getId() !== null) {
            $datas['exists'] = true;
            $datas['salt'] = $user->getServerSalt();
        }

        $this->getResponse()
            ->setBody(Zend_Json::encode($datas))
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
        ;
    }

    /**
     * TODO: PHPDoc
     *
     */
    public function loginAjaxPostAction()
    {
        $this->_redirectIfLoggedIn();

        $returnObject = array(
            'error'    => true,
            'messages' => array(),
            'redirect' => $this->app()->getRouter()->assemble(array(
                'controller' => 'account',
                'action'     => 'login'
                ), 'account')
            );

        if (!$this->getRequest()->isPost()) {
            $returnObject['messages'][] = 'Request should be POSTed.';

            $this->getResponse()
                ->setBody(Zend_Json::encode($returnObject))
                ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ;
            return;
        }

        if (!$this->_validateFieldset('login')) {
            $returnObject['messages'] = $this->_getSession()->getMessages();
            $returnObject['messages'][Zend_Log::ERR][] = 'Invalid form datas.';

            $this->getResponse()
                ->setBody(Zend_Json::encode($returnObject))
                ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ;
            return;
        }

        $user = $this->app()->getSingleton('user/entity');
        try {
            if (!$user->login($this->getRequest()->getPost('login'))) {
                $returnObject['messages'] = $this->app()->getSingleton('core/session')->getMessages();
                $returnObject['messages'] += $this->_getSession()->getMessages();

                $this->getResponse()
                    ->setBody(Zend_Json::encode($returnObject))
                    ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
                ;
                return;
            }

            $this->_getSession()
                ->addInfo('Login successful.')
            ;

            $returnObject['error'] = false;
            $returnObject['redirect'] = $this->_getRedirectLoginSuccessUrl();

            $this->getResponse()
                ->setBody(Zend_Json::encode($returnObject))
                ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ;
        } catch (Exception $e) {
            $returnObject['messages']['error'][] = $e->getMessage();
            $returnObject['messages']['error'][] = $e->getTraceAsString();

            $this->getResponse()
                ->setBody(Zend_Json::encode($returnObject))
                ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ;
        }
    }

    protected function _getRedirectLoginSuccessUrl()
    {
        return $this->app()->getRouter()->assemble(array(
            'controller' => 'account',
            'action'     => 'index',
            'username'   => $user->getUsername()
            ), 'user');
    }

    /**
     * TODO: PHPDoc
     *
     */
    public function logoutAction()
    {
        $this->_redirectIfNotLoggedIn('/');

        $this->_getSession()
            ->clear()
        ;

        $this->_redirect('/');
    }

    /**
     * TODO: PHPDoc
     *
     */
    public function registerAction()
    {
        $this->_redirectIfLoggedIn();

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * TODO: PHPDoc
     *
     */
    public function registerPostAction()
    {
        $this->_redirectIfLoggedIn();

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError('user/login');
            return;
        }
        $this->_redirectSuccess('user');
    }
}