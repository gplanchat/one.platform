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

        $this->_prepareGrid('legacies-planets', 'legacies/astronomical.moon.collection', $this->_getParam('sort'));

        $this->_collectionModel
            ->addAttributeFilter('planet_type', Legacies_Model_Planet::TYPE_MOON)
        ;

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle('Moon management')
        ;

        $this->renderLayout();
    }

    public function gridAjaxAction()
    {
        $collection = $this->app()
            ->getModel('legacies/astronomical.moon.collection')
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
            ->getModel('legacies/astronomical.moon')
            ->load($this->_getParam('id'))
        ;

        $formKey = uniqid();
        $this->app()
            ->getSingleton('admin.core/session')
            ->setFormKey($formKey);

        $this->_form
            ->getTab('general')
            ->getElement('id_owner')
            ->setMultiOptions($this->app()->getModel('legacies/user.collection')->load()->toHash('username'))
        ;

        $this->_form->populate(array(
            'form_key' => $formKey,
            'general' => array(
                'name'     => $entityModel->getName(),
                'position' => $entityModel->getPosition()
                ),
            'production' => array(
                'metal_mine_porcent'           => $entityModel->getMetalMinePorcent(),
                'crystal_mine_porcent'         => $entityModel->getCrystalMinePorcent(),
                'deuterium_sintetizer_porcent' => $entityModel->getDeuteriumSintetizerPorcent(),
                'solar_plant_porcent'          => $entityModel->getSolarPlantPorcent(),
                'fusion_plant_porcent'         => $entityModel->getFusionPlantPorcent(),
                'solar_satelit_porcent'        => $entityModel->getSolarSatelitPorcent()
                ),
            'resource' => array(
                'metal_mine'           => $entityModel->getMetalMine(),
                'crystal_mine'         => $entityModel->getCrystalMine(),
                'deuterium_sintetizer' => $entityModel->getDeuteriumSintetizer(),
                'solar_plant'          => $entityModel->getSolarPlant(),
                'fusion_plant'         => $entityModel->getFusionPlant(),
                'metal_store'          => $entityModel->getMetalStore(),
                'crystal_store'        => $entityModel->getCrystalStore(),
                'deuterium_store'      => $entityModel->getDeuteriumStore()
                ),
            'military' => array(
                'hangar'       => $entityModel->getHangar(),
                'ally_deposit' => $entityModel->getAllyDeposit(),
                'silo'         => $entityModel->getSilo()
                ),
            'special' => array(
                'robot_factory' => $entityModel->getRobotFactory(),
                'nano_factory'  => $entityModel->getNanoFactory(),
                'laboratory'    => $entityModel->getLaboratory(),
                'terraformer'   => $entityModel->getTerraformer()
                ),
            'defenses' => array(
                'misil_launcher'          => $entityModel->getMisilLauncher(),
                'small_laser'             => $entityModel->getSmallLaser(),
                'big_laser'               => $entityModel->getBigLaser(),
                'gauss_canyon'            => $entityModel->getGaussCanyon(),
                'ionic_canyon'            => $entityModel->getIonicCanyon(),
                'buster_canyon'           => $entityModel->getBusterCanyon(),
                'small_protection_shield' => $entityModel->getSmallProtectionShield(),
                'big_protection_shield'   => $entityModel->getBigProtectionShield()
                ),
            'ballistic' => array(
                'interceptor_misil'    => $entityModel->getInterceptorMisil(),
                'interplanetary_misil' => $entityModel->getInterplanetaryMisil()
                )
            ));

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
            ->setTitle('Moon management')
            ->setEntityLabel(sprintf('Edit Moon "%s"', $entityModel->getUsername()))
            ->headTitle(sprintf('Edit Moon "%s"', $entityModel->getUsername()))
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
            ->getModel('legacies/astronomical.moon')
            ->load($this->_getParam('id'))
        ;

        $optionGroups = array(
            'general' => array(
                'name'     => array($entityModel, 'setName'),
                'position' => array($entityModel, 'setPosition')
                ),
            'production' => array(
                'metal_mine_porcent'           => array($entityModel, 'setMetalMinePorcent'),
                'crystal_mine_porcent'         => array($entityModel, 'setCrystalMinePorcent'),
                'deuterium_sintetizer_porcent' => array($entityModel, 'setDeuteriumSintetizerPorcent'),
                'solar_plant_porcent'          => array($entityModel, 'setSolarPlantPorcent'),
                'fusion_plant_porcent'         => array($entityModel, 'setFusionPlantPorcent'),
                'solar_satelit_porcent'        => array($entityModel, 'setSolarSatelitPorcent')
                ),
            'resource' => array(
                'metal_mine'           => array($entityModel, 'setMetalMine'),
                'crystal_mine'         => array($entityModel, 'setCrystalMine'),
                'deuterium_sintetizer' => array($entityModel, 'setDeuteriumSintetizer'),
                'solar_plant'          => array($entityModel, 'setSolarPlant'),
                'fusion_plant'         => array($entityModel, 'setFusionPlant'),
                'metal_store'          => array($entityModel, 'setMetalStore'),
                'crystal_store'        => array($entityModel, 'setCrystalStore'),
                'deuterium_store'      => array($entityModel, 'setDeuteriumStore')
                ),
            'military' => array(
                'hangar'       => array($entityModel, 'setHangar'),
                'ally_deposit' => array($entityModel, 'setAllyDeposit'),
                'silo'         => array($entityModel, 'setSilo')
                ),
            'special' => array(
                'robot_factory' => array($entityModel, 'setRobotFactory'),
                'nano_factory'  => array($entityModel, 'setNanoFactory'),
                'laboratory'    => array($entityModel, 'setLaboratory'),
                'terraformer'   => array($entityModel, 'setTerraformer')
                ),
            'defenses' => array(
                'misil_launcher'          => array($entityModel, 'setMisilLauncher'),
                'small_laser'             => array($entityModel, 'setSmallLaser'),
                'big_laser'               => array($entityModel, 'setBigLaser'),
                'gauss_canyon'            => array($entityModel, 'setGaussCanyon'),
                'ionic_canyon'            => array($entityModel, 'setIonicCanyon'),
                'buster_canyon'           => array($entityModel, 'setBusterCanyon'),
                'small_protection_shield' => array($entityModel, 'setSmallProtectionShield'),
                'big_protection_shield'   => array($entityModel, 'setBigProtectionShield')
                ),
            'ballistic' => array(
                'interceptor_misil'    => array($entityModel, 'setInterceptorMisil'),
                'interplanetary_misil' => array($entityModel, 'setInterplanetaryMisil')
                )
            );

        $session = $this->app()
            ->getSingleton('admin.core/session');

        $request = $this->getRequest();

        if ($request->getPost('form_key') !== $session->getFormKey()) {
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
            $session->addInfo('Moon successfully updated.');
        } catch (One_Core_Exception_DaoError $e) {
            $session->addError('Could not save Moon updates.');
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
            ->setTitle('Moon management')
            ->setEntityLabel('Add a new Moon')
            ->headTitle('Add a new Moon')
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
            ->getModel('legacies/astronomical.moon')
        ;

        $optionGroups = array(
            'general' => array(
                'name'     => array($entityModel, 'setName'),
                'position' => array($entityModel, 'setPosition')
                ),
            'production' => array(
                'metal_mine_porcent'           => array($entityModel, 'setMetalMinePorcent'),
                'crystal_mine_porcent'         => array($entityModel, 'setCrystalMinePorcent'),
                'deuterium_sintetizer_porcent' => array($entityModel, 'setDeuteriumSintetizerPorcent'),
                'solar_plant_porcent'          => array($entityModel, 'setSolarPlantPorcent'),
                'fusion_plant_porcent'         => array($entityModel, 'setFusionPlantPorcent'),
                'solar_satelit_porcent'        => array($entityModel, 'setSolarSatelitPorcent')
                ),
            'resource' => array(
                'metal_mine'           => array($entityModel, 'setMetalMine'),
                'crystal_mine'         => array($entityModel, 'setCrystalMine'),
                'deuterium_sintetizer' => array($entityModel, 'setDeuteriumSintetizer'),
                'solar_plant'          => array($entityModel, 'setSolarPlant'),
                'fusion_plant'         => array($entityModel, 'setFusionPlant'),
                'metal_store'          => array($entityModel, 'setMetalStore'),
                'crystal_store'        => array($entityModel, 'setCrystalStore'),
                'deuterium_store'      => array($entityModel, 'setDeuteriumStore')
                ),
            'military' => array(
                'hangar'       => array($entityModel, 'setHangar'),
                'ally_deposit' => array($entityModel, 'setAllyDeposit'),
                'silo'         => array($entityModel, 'setSilo')
                ),
            'special' => array(
                'robot_factory' => array($entityModel, 'setRobotFactory'),
                'nano_factory'  => array($entityModel, 'setNanoFactory'),
                'laboratory'    => array($entityModel, 'setLaboratory'),
                'terraformer'   => array($entityModel, 'setTerraformer')
                ),
            'defenses' => array(
                'misil_launcher'          => array($entityModel, 'setMisilLauncher'),
                'small_laser'             => array($entityModel, 'setSmallLaser'),
                'big_laser'               => array($entityModel, 'setBigLaser'),
                'gauss_canyon'            => array($entityModel, 'setGaussCanyon'),
                'ionic_canyon'            => array($entityModel, 'setIonicCanyon'),
                'buster_canyon'           => array($entityModel, 'setBusterCanyon'),
                'small_protection_shield' => array($entityModel, 'setSmallProtectionShield'),
                'big_protection_shield'   => array($entityModel, 'setBigProtectionShield')
                ),
            'ballistic' => array(
                'interceptor_misil'    => array($entityModel, 'setInterceptorMisil'),
                'interplanetary_misil' => array($entityModel, 'setInterplanetaryMisil')
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
        $entityModel->setPlanetType(Legacies_Model_Planet::TYPE_MOON);

        try {
            $entityModel->save();
            $session->addInfo('Moon successfully updated.');
        } catch (One_Core_Exception_DaoError $e) {
            $session->addError('Could not save Moon updates.');
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
                ->getModel('legacies/astronomical.moon')
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
        $this->addTab('legacies-planet-buildings-resources', 'resource', 'Resources Buildings');
        $this->addTab('legacies-planet-buildings-military', 'military', 'Military Buildings');
        $this->addTab('legacies-planet-buildings-special', 'special', 'Special Buildings');
        $this->addTab('legacies-planet-defenses', 'defenses', 'Defenses');
        $this->addTab('legacies-planet-ballistic', 'ballistic', 'Ballistics');
    }
}