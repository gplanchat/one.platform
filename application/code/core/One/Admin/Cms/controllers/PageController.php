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
 * documentation for further information about customizing XNova.
 *
 */

/**
 * CMS Page administration controller
 *
 * @uses        One_Admin_Core_ControllerAbstract
 * @uses        Zend_Form
 *
 * @access      public
 * @author      gplanchat
 * @category    Cms
 * @package     One_Admin_Cms
 * @subpackage  One_Admin_Cms
 */
class One_Admin_Cms_PageController
    extends One_Admin_Core_ControllerAbstract
{
    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $collection = $this->app()
            ->getModel('cms/page.collection')
            ->setPage($this->_getParam('p'), $this->_getParam('n'));

        $grid = $this->getLayout()
            ->getBlock('grid')
            ->setCollection($collection)
            ->loadColumns('cms-page')
            ->sort($this->_getParam('sort'))
        ;
        $this->_prepareGrid('cms-page', 'cms/page.collection');

        $this->renderLayout();
    }

    public function gridAjaxAction()
    {
        $collection = $this->app()
            ->getModel('cms/page.collection')
            ->setPage($this->_getParam('p'), $this->_getParam('n'));

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ->setBody(Zend_Json::encode($collection->load()->toArray()))
        ;
    }

    public function editAction()
    {
        if ($this->getRequest()->isPost()){
            $this->_forward('edit-post');
            return;
        }

        $this->_buildEditForm();

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
        ;

        $this->renderLayout();
    }

    public function editPostAction()
    {
        $this->app()
            ->getModel('admin.core/session')
            ->addInfo('Edit action is not implemented.')
        ;// FIXME

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ));

        $this->_redirect($url);
    }

    public function newAction()
    {
        $this->_buildEditForm();

        $this->renderLayout();
    }

    public function newPostAction()
    {
        $this->app()
            ->getModel('admin.core/session')
            ->addInfo('Add action is not implemented.')
        ;// FIXME

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ));

        $this->_redirect($url);
    }

    public function deleteAction()
    {
        $this->app()
            ->getModel('admin.core/session')
            ->addInfo('Delete action is not implemented.')
        ;// FIXME

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ));

        $this->_redirect($url);
    }

    protected function _buildEditForm()
    {
        $this->prepareForm('cms/page', $this->_getParam('id'));
        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'edit-post'
                ));
        $this->_form->setAction($url);

        $this->addTab('cms-page-general', 'general', 'General');
        $this->addTab('cms-page-content', 'content', 'Content');
        $this->addTab('cms-page-meta', 'meta', 'Meta data');
        $this->addTab('cms-page-layout', 'layout', 'Layout updates');
    }
}