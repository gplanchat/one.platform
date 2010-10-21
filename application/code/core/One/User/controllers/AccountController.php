<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 * User account controller
 *
 * @access      public
 * @author      gplanchat
 * @category    Controller
 * @package     One
 * @subpackage  One_User
 */
class One_User_AccountController
    extends One_User_Controller_AuthenticationAware
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
            'exists'       => false,
            'stealth_salt' => null
            );

        if ($user->getId() !== null) {
            $datas['exists'] = true;
            $datas['stealth_salt'] = $user->getServerSalt();
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
                ))
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
            $returnObject['messages'] = $this->app()->getSingleton('core/session')->getMessages();
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
                $returnObject['messages'] += $this->app()->getSingleton('user/session')->getMessages();

                $this->getResponse()
                    ->setBody(Zend_Json::encode($returnObject))
                    ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
                ;
                return;
            }

            $this->app()->getSingleton('user/session')
                ->addInfo('Login successful.')
            ;

            $returnObject['error'] = false;
            $returnObject['redirect'] = $this->app()->getRouter()->assemble(array(
                'controller' => 'account',
                'action'     => 'index',
                'username'   => $user->getUsername()
                ), 'user');

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