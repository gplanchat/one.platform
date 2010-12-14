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
 * Administration base grid controller
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
class One_Admin_User_EntityController
    extends One_Admin_Core_ControllerAbstract
{
    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $this->_prepareGrid('user-entities', 'user/entity.collection', $this->_getParam('sort'));

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle('User List')
        ;

        $this->renderLayout();
    }

    public function editAction()
    {
        if ($this->getRequest()->isPost()){
            $this->_forward('edit-post');
            return;
        }

        $this->_buildEditForm();

        $entityModel = $this->app()
            ->getModel('user/entity')
            ->load($this->_getParam('id'))
        ;

        $formKey = uniqid();
        $this->app()
            ->getSingleton('admin.core/session')
            ->setFormKey($formKey);

        $this->_form->populate(array(
            'form_key' => $formKey,
            'general' => array(
                'username' => $entityModel->getUsername(),
                'websites'  => $entityModel->getWebsite(),
                )
            ));

        $websites = $this->_form->getTab('general')
            ->getElement('websites')
            ->setMultiOptions($this->app()->getModel('core/website.collection')->load()->toHash('label'))
        ;

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
            ->setTitle('User Account')
            ->setEntityLabel(sprintf('Edit User "%s"', $entityModel->getUsername()))
            ->headTitle(sprintf('Edit USer "%s"', $entityModel->getUsername()))
        ;

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'edit-post'
                ));

        $this->_form->setAction($url);

        $this->renderLayout();
    }

    public function editPostAction()
    {
    }

    public function newAction()
    {
    }

    public function newPostAction()
    {
    }

    public function deleteAction()
    {
    }

    protected function _buildEditForm()
    {
        $this->_prepareForm();

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'edit-post'
                ));
        $this->_form->setAction($url);

        $this->addTab('user-entity-general', 'general', 'General');
    }
}