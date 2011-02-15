<?php
/**
 * This file is part of XNova:Legacies
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
 * XNova:Legacies user administration controller
 *
 * @uses        One_Admin_Core_Controller_FormGridAbstract
 * @uses        Zend_Form
 *
 * @access      public
 * @author      gplanchat
 * @category    Admin
 * @package     Legacies
 * @subpackage  Legacies_Admin_Core
 */
class Legacies_Admin_Core_UserController
    extends One_Admin_Core_Controller_FormGridAbstract
{
    protected function _getFormOptionGroupMapping()
    {
        return array(
            'general' => array(
                'username' => 'username',
                'email'    => 'email',
                'lang'     => 'lang',
                'sex'      => 'sex'
                ),
            'profile' => array(
                'id_planet' => 'id_planet',
                'avatar'    => 'avatar',
                'sign'      => 'sign',
                'dpath'     => 'dpath',
                'design'    => 'design'
                ),
            'options' => array(
                'noipcheck'             => 'noipcheck',
                'planet_sort_order'     => 'planet_sort_order',
                'spio_anz'              => 'spio_anz',
                'settings_tooltiptime'  => 'settings_tooltiptime',
                'settings_fleetactions' => 'settings_fleetactions',
                'settings_allylogo'     => 'settings_allylogo',
                'settings_esp'          => 'settings_esp',
                'settings_wri'          => 'settings_wri',
                'settings_bud'          => 'settings_bud',
                'settings_mis'          => 'settings_mis',
                'settings_rep'          => 'settings_rep'
                ),
            'meta' => array(
                'urlaubs_modus'  => 'urlaubs_modus',
                'urlaubs_until'  => 'urlaubs_until',
                'onlinetime'     => 'onlinetime',
                'user_lastip'    => 'user_lastip',
                'ip_at_reg'      => 'ip_at_reg',
                'register_time'  => 'register_time',
                'user_agent'     => 'user_agent',
                'current_page'   => 'current_page',
                'current_planet' => 'current_planet'
                ),
            'researches' => array(
                'spy_tech'              => 'spy_tech',
                'computer_tech'         => 'computer_tech',
                'military_tech'         => 'military_tech',
                'shield_tech'           => 'shield_tech',
                'defence_tech'          => 'defence_tech',
                'energy_tech'           => 'energy_tech',
                'hyperspace_tech'       => 'hyperspace_tech',
                'combustion_tech'       => 'combustion_tech',
                'impulse_motor_tech'    => 'impulse_motor_tech',
                'hyperspace_motor_tech' => 'hyperspace_motor_tech',
                'laser_tech'            => 'laser_tech',
                'ionic_tech'            => 'ionic_tech',
                'buster_tech'           => 'buster_tech',
                'intergalactic_tech'    => 'intergalactic_tech',
                'expedition_tech'       => 'expedition_tech',
                'graviton_tech'         => 'graviton_tech'
                )
            );
    }

    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $this->_prepareGrid('legacies-users', 'legacies/user.collection', $this->_getParam('sort'));

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle($this->app()->_('User management'))
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
            ->getModel('legacies/user')
            ->load($this->_getParam('id'))
        ;

        $this->_populateForm($entityModel);

        $planetCollection = $this->app()
            ->getModel('legacies/planet.planet.collection')
            ->addAttributeFilter('id_owner', $entityModel->getId())
            ->load()
            ->toHash('name');

        $this->_form
            ->getTab('profile')
            ->getElement('id_planet')
            ->setMultiOptions($planetCollection)
        ;

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
            ->setTitle($this->app()->_('User management'))
            ->setEntityLabel($this->app()->_('Edit user "%1$s"', $entityModel->getUsername()))
            ->headTitle($this->app()->_('Edit user "%1$s"', $entityModel->getUsername()))
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
            ->getModel('legacies/user')
        ;

        if (($id = $this->_getParam('id')) !== null) {
            $entityModel->load($id);
        }

        $this->_populateEntity($entityModel);

        try {
            $entityModel->save();
            $session->addInfo($this->app()->_('User successfully updated.'));
        } catch (One_Core_Exception_DaoError $e) {
            $session->addError($this->app()->_('Could not save user updates.'));
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
            ->setTitle($this->app()->_('User management'))
            ->setEntityLabel($this->app()->_('Add a new user'))
            ->headTitle($this->app()->_('Add a new user'))
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
                ->getModel('legacies/user')
                ->load($this->_getParam('id'))
                ->delete()
            ;

            $this->app()
                ->getModel('admin.core/session')
                ->addInfo($this->app()->_('The user has been successfully deleted.'))
            ;
        } catch (One_Core_Exception $e) {
            $this->app()
                ->getModel('admin.core/session')
                ->addError($this->app()->_('An error occured while deleting the user.'))
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

        $this->addTab('legacies-user-general', 'general', 'General');
        $this->addTab('legacies-user-profile', 'profile', 'Profile');
        $this->addTab('legacies-user-options', 'options', 'Options');
        $this->addTab('legacies-user-meta', 'meta', 'Metadata');
        $this->addTab('legacies-user-researches', 'researches', 'Researches');
//        $this->addTab('legacies-user-officers', 'officers', 'Officers');
    }
}