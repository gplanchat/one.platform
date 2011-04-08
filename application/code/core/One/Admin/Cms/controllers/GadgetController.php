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
 * CMS Gadget administration controller
 *
 * @uses        One_Admin_Core_Controller_FormGridAbstract
 * @uses        Zend_Form
 *
 * @access      public
 * @author      gplanchat
 * @category    Cms
 * @package     One_Admin_Cms
 * @subpackage  One_Admin_Cms
 */
class One_Admin_Cms_GadgetController
    extends One_Admin_Core_Controller_FormGridAbstract
{
    protected function _getFormOptionGroupMapping()
    {
        return array(
            'general' => array(
                'title'      => 'title',
                'identifier' => 'identifier',
                'websites'   => 'websites'
                ),
            'content' => array(
                'html' => 'content'
                ),
            'layout' => array(
                'active'  => 'active',
                'updates' => 'updates'
                )
            );
    }

    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $this->_prepareGrid('cms-gadget', 'cms/gadget.collection', $this->_getParam('sort'));

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle($this->app()->_('CMS Gadgets'))
        ;

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_buildEditForm();

        if (($id = $this->_getParam('id')) === null) {
            $this->getSingleton('admin.core/session')
                ->addError($this->app()->_('Unable to load entity: no entity id was specified.'))
            ;
            $this->_helper->redirector->gotoRoute(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ), null, true);
            return;
        }

        $entityModel = $this->app()
            ->getModel('cms/gadget')
            ->load($this->_getParam('id'))
        ;

        $this->_populateForm($entityModel);

        $websites = $this->_form->getTab('general')
            ->getElement('websites')
            ->setMultiOptions($this->app()->getModel('core/website.collection')->load()->toHash('label'))
        ;

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
            ->setTitle('CMS Gadget')
            ->setEntityLabel($this->app()->_('Edit CMS Gadget "%1$s"', $entityModel->getTitle()))
            ->headTitle($this->app()->_('Edit CMS Gadget "%1$s"', $entityModel->getTitle()))
        ;

        $this->renderLayout();
    }

    public function editPostAction()
    {
        $request = $this->getRequest();
        $session = $this->app()
            ->getSingleton('admin.core/session');

        if ($request->getPost('form_key') !== $this->_getFormKey()) {
            $session->addError($this->app()->_('Invalid form data.'));

            $this->_helper->redirector->gotoRoute(array(
                    'path'       => $this->_getParam('path'),
                    'controller' => $this->_getParam('controller'),
                    'action'     => 'index'
                    ), null, true);
            return;
        }

        $entityModel = $this->app()
            ->getModel('cms/gadget')
        ;

        if (($id = $this->_getParam('id')) !== null) {
            $entityModel->load($id);
        }

        $this->_populateEntity($entityModel);

        try {
            $entityModel->save();
            $session->addError($this->app()->_('Gadget successfully updated.'));
        } catch (One_Core_Exception $e) {
            $session->addError($this->app()->_('Could not save gadget updates.'));
        }

        $this->_helper->redirector->gotoRoute(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ), null, true);
    }

    public function newAction()
    {
        $this->_buildEditForm();

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle($this->app()->_('CMS Gadget'))
            ->setEntityLabel($this->app()->_('Add a new CMS Gadget'))
            ->headTitle($this->app()->_('Add a new CMS Gadget'))
        ;

        $websites = $this->_form->getTab('general')
            ->getElement('websites')
            ->setMultiOptions($this->app()->getModel('core/website.collection')->load()->toHash('label'))
        ;

        $this->renderLayout();
    }

    public function newPostAction()
    {
        $this->_forward('edit-post');
    }

    public function deleteAction()
    {
        try {
            $entityModel = $this->app()
                ->getModel('cms/gadget')
                ->load($this->_getParam('id'))
                ->delete()
            ;

            $this->app()
                ->getModel('admin.core/session')
                ->addInfo($this->app()->_('The gadget has been successfully deleted.'))
            ;
        } catch (One_Core_Exception $e) {
            $this->app()
                ->getModel('admin.core/session')
                ->addError($this->app()->_('An error occured while deleting the gadget.'))
            ;
        }

        $this->_helper->redirector->gotoRoute(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ));
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

        $this->addTab('cms-gadget-general', 'general', 'General');
        $this->addTab('cms-content', 'content', 'Content');
        $this->addTab('cms-layout', 'layout', 'Layout updates');
    }
}