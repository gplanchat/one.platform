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
class Legacies_Admin_Core_MoonController
    extends One_Admin_Core_Controller_FormGridAbstract
{
    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $this->_prepareGrid('legacies-moon', 'legacies/planet.collection', $this->_getParam('sort'));

        $this->_collectionModel
            ->addAttributeFilter('planet_type', Legacies_Model_Planet::TYPE_MOON)
        ;

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle('Planet management')
        ;

        $this->renderLayout();
    }

    public function gridAjaxAction()
    {
        $collection = $this->app()
            ->getModel('legacies/planet.collection')
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

        $entityModel = $this->app()
            ->getModel('legacies/planet')
            ->load($this->_getParam('id'))
        ;

        $formKey = uniqid();
        $this->app()
            ->getSingleton('admin.core/session')
            ->setFormKey($formKey);

        $this->_form->populate(array(
            'form_key' => $formKey,
//            'config' => array(
//                'key'   => $entityModel->getConfigName(),
//                'value' => $entityModel->getConfigValue()
//                )
            ));

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
            ->setTitle('Planet management')
            ->setEntityLabel(sprintf('Edit Planet "%s"', $entityModel->getUsername()))
            ->headTitle(sprintf('Edit Planet "%s"', $entityModel->getUsername()))
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
        $entityModel = $this->app()
            ->getModel('cms/page')
            ->load($this->_getParam('id'))
        ;

        $optionGroups = array(
            'config' => array(
                'key'    => array($entityModel, 'setConfigName'),
                'value'  => array($entityModel, 'setConfigValue')
                )
            );

        $session = $this->app()
            ->getSingleton('admin.core/session');

        $request = $this->getRequest();

        if ($request->getPost('form_key') === $session->getFormKey()) {
            $session->addError('Invalid form data.');

            $this->_helper->redirector->gotoRoute(array(
                    'path'       => $this->_getParam('path'),
                    'controller' => $this->_getParam('controller'),
                    'action'     => 'index'
                    ), null, true);
            return;
        }

        foreach ($optionGroups as $groupName => $groupElements) {
            $groupData = $request->getPost($groupName);
            if (!is_array($groupElements) || empty($groupName) || !is_array($groupData)) {
                continue;
            }

            foreach ($groupElements as $element => $callback) {
                if (!isset($groupData[$element])) {
                    continue;
                }

                call_user_func($callback, $groupData[$element]);
            }
        }
        try {
            $entityModel->save();
            $session->addError('Configuration successfully updated.');
        } catch (One_Core_Exception $e) {
            $session->addError('Could not save configuration updates.');
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
            ->setTitle('Planet management')
            ->setEntityLabel('Add a new Planet')
            ->headTitle('Add a new Planet')
        ;

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'new-post'
                ));

        $this->_form->setAction($url);

        $this->renderLayout();
    }

    public function newPostAction()
    {
        $entityModel = $this->app()
            ->getModel('legacies/config')
        ;

        $optionGroups = array(
            'config' => array(
//                'key'    => array($entityModel, 'setConfigName'),
//                'value'  => array($entityModel, 'setConfigValue')
                )
            );

        $session = $this->app()
            ->getSingleton('admin.core/session');

        $request = $this->getRequest();

        if ($request->getPost('form_key') === $session->getFormKey()) {
            $session->addError('Invalid form data.');

            $this->_helper->redirector->gotoRoute(array(
                    'path'       => $this->_getParam('path'),
                    'controller' => $this->_getParam('controller'),
                    'action'     => 'index'
                    ), null, true);
            return;
        }

        foreach ($optionGroups as $groupName => $groupElements) {
            $groupData = $request->getPost($groupName);
            if (!is_array($groupElements) || empty($groupName) || !is_array($groupData)) {
                continue;
            }

            foreach ($groupElements as $element => $callback) {
                if (!isset($groupData[$element])) {
                    continue;
                }

                call_user_func($callback, $groupData[$element]);
            }
        }

        try {
            $entityModel->save();
            $session->addError('Configuration successfully updated.');
        } catch (One_Core_Exception $e) {
            $session->addError('Could not save configuration updates.');
        }

        $this->_helper->redirector->gotoRoute(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ), null, true);
    }

    public function deleteAction()
    {
        try {
            $entityModel = $this->app()
                ->getModel('legacies/config')
                ->load($this->_getParam('id'))
                ->delete()
            ;

            $this->app()
                ->getModel('admin.core/session')
                ->addInfo('The Option has been successfully deleted.')
            ;
        } catch (One_Core_Exception $e) {
            $this->app()
                ->getModel('admin.core/session')
                ->addError('An error occured while deleting the Option.')
            ;
        }

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
        $this->_prepareForm();

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'edit-post'
                ));
        $this->_form->setAction($url);

        $this->addTab('legacies-planet-general', 'general', 'General');
        $this->addTab('legacies-planet-production', 'production', 'Production');
        $this->addTab('legacies-planet-buildings-resources', 'resource-buildings', 'Resources Buildings');
        $this->addTab('legacies-planet-buildings-military', 'military-buildings', 'Military Buildings');
        $this->addTab('legacies-planet-buildings-special', 'special-buildings', 'Special Buildings');
        $this->addTab('legacies-planet-defenses', 'defenses', 'Defenses');
        $this->addTab('legacies-planet-ballistic', 'ballistic', 'Ballistics');
    }
}