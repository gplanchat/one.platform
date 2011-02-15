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
 * XNova:Legacies moon administration controller
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
class Legacies_Admin_Core_MoonController
    extends One_Admin_Core_Controller_FormGridAbstract
{
    protected function _getFormOptionGroupMapping()
    {
        return array(
            'general' => array(
                'name'     => 'name',
                'position' => 'position',
                'owner'    => 'owner'
                ),
            'production' => array(
                'metal_mine_porcent'           => 'metal_mine_porcent',
                'crystal_mine_porcent'         => 'crystal_mine_porcent',
                'deuterium_sintetizer_porcent' => 'deuterium_sintetizer_porcent',
                'solar_plant_porcent'          => 'solar_plant_porcent',
                'fusion_plant_porcent'         => 'fusion_plant_porcent',
                'solar_satelit_porcent'        => 'solar_satelit_porcent'
                ),
            'resource' => array(
                'metal_mine'           => 'metal_mine',
                'crystal_mine'         => 'crystal_mine',
                'deuterium_sintetizer' => 'deuterium_sintetizer',
                'solar_plant'          => 'solar_plant',
                'fusion_plant'         => 'fusion_plant',
                'metal_store'          => 'metal_store',
                'crystal_store'        => 'crystal_store',
                'deuterium_store'      => 'deuterium_store'
                ),
            'military' => array(
                'hangar'       => 'hangar',
                'ally_deposit' => 'ally_deposit',
                'silo'         => 'silo'
                ),
            'special' => array(
                'robot_factory' => 'robot_factory',
                'nano_factory'  => 'nano_factory',
                'laboratory'    => 'laboratory',
                'terraformer'   => 'terraformer'
                ),
            'defenses' => array(
                'misil_launcher'          => 'misil_launcher',
                'small_laser'             => 'small_laser',
                'big_laser'               => 'big_laser',
                'gauss_canyon'            => 'gauss_canyon',
                'ionic_canyon'            => 'ionic_canyon',
                'buster_canyon'           => 'buster_canyon',
                'small_protection_shield' => 'small_protection_shield',
                'big_protection_shield'   => 'big_protection_shield'
                ),
            'ballistic' => array(
                'interceptor_misil'    => 'interceptor_misil',
                'interplanetary_misil' => 'interplanetary_misil'
                )
            );
    }

    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $this->_prepareGrid('legacies-planets', 'legacies/planet.moon.collection', $this->_getParam('sort'));

        $this->_collectionModel
            ->addAttributeFilter('planet_type', Legacies_Model_Planet::TYPE_MOON)
        ;

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle($this->app()->_('Moon management'))
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
            ->getModel('legacies/planet')
            ->load($this->_getParam('id'))
        ;

        $this->_form
            ->getTab('general')
            ->getElement('id_owner')
            ->setMultiOptions($this->app()->getModel('legacies/user.collection')->load()->toHash('username'))
        ;

        $this->_populateForm($entityModel);

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
            ->setTitle($this->app()->_('Moon management'))
            ->setEntityLabel($this->app()->_('Edit Moon "%s"', $entityModel->getUsername()))
            ->headTitle($this->app()->_('Edit Moon "%s"', $entityModel->getUsername()))
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
            ->getModel('legacies/planet')
        ;

        if (($id = $this->_getParam('id')) !== null) {
            $entityModel->load($id);
        }

        $this->_populateEntity($entityModel);

        try {
            $entityModel->save();
            $session->addInfo($this->app()->_('Moon successfully updated.'));
        } catch (One_Core_Exception_DaoError $e) {
            $session->addError($this->app()->_('Could not save moon updates.'));
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
            ->setTitle($this->app()->_('Moon management'))
            ->setEntityLabel($this->app()->_('Add a new moon'))
            ->headTitle($this->app()->_('Add a new moon'))
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
                ->getModel('legacies/planet')
                ->load($this->_getParam('id'))
                ->delete()
            ;

            $this->app()
                ->getModel('admin.core/session')
                ->addInfo($this->app()->_('The moon has been successfully deleted.'))
            ;
        } catch (One_Core_Exception $e) {
            $this->app()
                ->getModel('admin.core/session')
                ->addError($this->app()->_('An error occured while deleting the moon.'))
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

        $this->addTab('legacies-planet-general', 'general', $this->app()->_('General'));
        $this->addTab('legacies-planet-production', 'production', $this->app()->_('Production'));
        $this->addTab('legacies-planet-buildings-military', 'military', $this->app()->_('Military Buildings'));
        $this->addTab('legacies-planet-buildings-special', 'special', $this->app()->_('Special Buildings'));
        $this->addTab('legacies-planet-defenses', 'defenses', $this->app()->_('Defenses'));
        $this->addTab('legacies-planet-ballistic', 'ballistic', $this->app()->_('Ballistics'));
    }
}