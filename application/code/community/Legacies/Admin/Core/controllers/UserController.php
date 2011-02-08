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
class Legacies_Admin_Core_UserController
    extends One_Admin_Core_Controller_FormGridAbstract
{
    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $this->_prepareGrid('legacies-users', 'legacies/user.collection', $this->_getParam('sort'));

        $container = $this->getLayout()
            ->getBlock('container')
            ->setTitle('User management')
        ;

        $this->renderLayout();
    }

    public function gridAjaxAction()
    {
        $collection = $this->app()
            ->getModel('legacies/user.collection')
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
            ->getModel('legacies/user')
            ->load($this->_getParam('id'))
        ;

        $formKey = uniqid();
        $this->app()
            ->getSingleton('admin.core/session')
            ->setFormKey($formKey);

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

        $this->_form->populate(array(
            'form_key' => $formKey,
            'general' => array(
                'username' => $entityModel->getUsername(),
                'email'    => $entityModel->getEmail(),
                'lang'     => $entityModel->getLang(),
                'sex'      => $entityModel->getSex()
                ),
            'profile' => array(
                'id_planet' => $entityModel->getIdPlanet(),
                'avatar'    => $entityModel->getAvatar(),
                'sign'      => $entityModel->getSign(),
                'dpath'     => $entityModel->getDpath(),
                'design'    => $entityModel->getDesign()
                ),
            'options' => array(
                'noipcheck'             => $entityModel->getNoipcheck(),
                'planet_sort_order'     => $entityModel->getPlanetSortOrder(),
                'spio_anz'              => $entityModel->getSpioAnz(),
                'settings_tooltiptime'  => $entityModel->getSettingsTooltiptime(),
                'settings_fleetactions' => $entityModel->getSettingsFleetactions(),
                'settings_allylogo'     => $entityModel->getSettingsAllylogo(),
                'settings_esp'          => $entityModel->getSettingsEsp(),
                'settings_wri'          => $entityModel->getSettingsWri(),
                'settings_bud'          => $entityModel->getSettingsBud(),
                'settings_mis'          => $entityModel->getSettingsMis(),
                'settings_rep'          => $entityModel->getSettingsRep()
                ),
            'meta' => array(
                'urlaubs_modus'  => $entityModel->getUrlaubsModus(),
                'urlaubs_until'  => $entityModel->getUrlaubsUntil(),
                'onlinetime'     => $entityModel->getOnlinetime(),
                'user_lastip'    => $entityModel->getUserLastip(),
                'ip_at_reg'      => $entityModel->getIpAtReg(),
                'register_time'  => $entityModel->getRegisterTime(),
                'user_agent'     => $entityModel->getUserAgent(),
                'current_page'   => $entityModel->getCurrentPage(),
                'current_planet' => $entityModel->getCurrentPlanet()
                ),
            'researches' => array(
                'spy_tech'              => $entityModel->getSpyTech(),
                'computer_tech'         => $entityModel->getComputerTech(),
                'military_tech'         => $entityModel->getMilitaryTech(),
                'shield_tech'           => $entityModel->getShieldTech(),
                'defence_tech'          => $entityModel->getDefenceTech(),
                'energy_tech'           => $entityModel->getEnergyTech(),
                'hyperspace_tech'       => $entityModel->getHyperspaceTech(),
                'combustion_tech'       => $entityModel->getCombustionTech(),
                'impulse_motor_tech'    => $entityModel->getImpulseMotorTech(),
                'hyperspace_motor_tech' => $entityModel->getHyperspaceMotorTech(),
                'laser_tech'            => $entityModel->getLaserTech(),
                'ionic_tech'            => $entityModel->getIonicTech(),
                'buster_tech'           => $entityModel->getBusterTech(),
                'intergalactic_tech'    => $entityModel->getIntergalacticTech(),
                'expedition_tech'       => $entityModel->getExpeditionTech(),
                'graviton_tech'         => $entityModel->getGravitonTech()
                )
            ));

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
            ->setTitle('User management')
            ->setEntityLabel(sprintf('Edit User "%s"', $entityModel->getUsername()))
            ->headTitle(sprintf('Edit User "%s"', $entityModel->getUsername()))
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
            ->getModel('legacies/user')
            ->load($this->_getParam('id'))
        ;

        $optionGroups = array(
            'general' => array(
                'username' => array($entityModel, 'setUsername'),
                'email'    => array($entityModel, 'setEmail'),
                'lang'     => array($entityModel, 'setLang'),
                'sex'      => array($entityModel, 'setSex')
                ),
            'profile' => array(
                'id_planet' => array($entityModel, 'setIdPlanet'),
                'avatar'    => array($entityModel, 'setAvatar'),
                'sign'      => array($entityModel, 'setSign'),
                'dpath'     => array($entityModel, 'setDpath'),
                'design'    => array($entityModel, 'setDesign')
                ),
            'options' => array(
                'noipcheck'             => array($entityModel, 'setNoipcheck'),
                'planet_sort_order'     => array($entityModel, 'setPlanetSortOrder'),
                'spio_anz'              => array($entityModel, 'setSpioAnz'),
                'settings_tooltiptime'  => array($entityModel, 'setSettingsTooltiptime'),
                'settings_fleetactions' => array($entityModel, 'setSettingsFleetactions'),
                'settings_allylogo'     => array($entityModel, 'setSettingsAllylogo'),
                'settings_esp'          => array($entityModel, 'setSettingsEsp'),
                'settings_wri'          => array($entityModel, 'setSettingsWri'),
                'settings_bud'          => array($entityModel, 'setSettingsBud'),
                'settings_mis'          => array($entityModel, 'setSettingsMis'),
                'settings_rep'          => array($entityModel, 'setSettingsRep')
                ),
            'meta' => array(
                'urlaubs_modus'  => array($entityModel, 'setUrlaubsModus'),
                'urlaubs_until'  => array($entityModel, 'setUrlaubsUntil'),
                'onlinetime'     => array($entityModel, 'setOnlinetime'),
                'user_lastip'    => array($entityModel, 'setUserLastip'),
                'ip_at_reg'      => array($entityModel, 'setIpAtReg'),
                'register_time'  => array($entityModel, 'setRegisterTime'),
                'user_agent'     => array($entityModel, 'setUserAgent'),
                'current_page'   => array($entityModel, 'setCurrentPage'),
                'current_planet' => array($entityModel, 'setCurrentPlanet')
                ),
            'researches' => array(
                'spy_tech'              => array($entityModel, 'setSpyTech'),
                'computer_tech'         => array($entityModel, 'setComputerTech'),
                'military_tech'         => array($entityModel, 'setMilitaryTech'),
                'shield_tech'           => array($entityModel, 'setShieldTech'),
                'defence_tech'          => array($entityModel, 'setDefenceTech'),
                'energy_tech'           => array($entityModel, 'setEnergyTech'),
                'hyperspace_tech'       => array($entityModel, 'setHyperspaceTech'),
                'combustion_tech'       => array($entityModel, 'setCombustionTech'),
                'impulse_motor_tech'    => array($entityModel, 'setImpulseMotorTech'),
                'hyperspace_motor_tech' => array($entityModel, 'setHyperspaceMotorTech'),
                'laser_tech'            => array($entityModel, 'setLaserTech'),
                'ionic_tech'            => array($entityModel, 'setIonicTech'),
                'buster_tech'           => array($entityModel, 'setBusterTech'),
                'intergalactic_tech'    => array($entityModel, 'setIntergalacticTech'),
                'expedition_tech'       => array($entityModel, 'setExpeditionTech'),
                'graviton_tech'         => array($entityModel, 'setGravitonTech')
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
            $session->addInfo('User successfully updated.');
        } catch (One_Core_Exception_DaoError $e) {
            $session->addError('Could not save User updates.');
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
            ->setTitle('User management')
            ->setEntityLabel('Add a new User')
            ->headTitle('Add a new User')
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
            ->getModel('legacies/user')
        ;

        $optionGroups = array(
            'form_key' => $formKey,
            'general' => array(
                'username' => array($entityModel, 'setUsername'),
                'email'    => array($entityModel, 'setEmail'),
                'lang'     => array($entityModel, 'setLang'),
                'sex'      => array($entityModel, 'setSex')
                ),
            'profile' => array(
                'id_planet' => array($entityModel, 'setIdPlanet'),
                'avatar'    => array($entityModel, 'setAvatar'),
                'sign'      => array($entityModel, 'setSign'),
                'dpath'     => array($entityModel, 'setDpath'),
                'design'    => array($entityModel, 'setDesign')
                ),
            'options' => array(
                'noipcheck'             => array($entityModel, 'setNoipcheck'),
                'planet_sort_order'     => array($entityModel, 'setPlanetSortOrder'),
                'spio_anz'              => array($entityModel, 'setSpioAnz'),
                'settings_tooltiptime'  => array($entityModel, 'setSettingsTooltiptime'),
                'settings_fleetactions' => array($entityModel, 'setSettingsFleetactions'),
                'settings_allylogo'     => array($entityModel, 'setSettingsAllylogo'),
                'settings_esp'          => array($entityModel, 'setSettingsEsp'),
                'settings_wri'          => array($entityModel, 'setSettingsWri'),
                'settings_bud'          => array($entityModel, 'setSettingsBud'),
                'settings_mis'          => array($entityModel, 'setSettingsMis'),
                'settings_rep'          => array($entityModel, 'setSettingsRep')
                ),
            'meta' => array(
                'urlaubs_modus'  => array($entityModel, 'setUrlaubsModus'),
                'urlaubs_until'  => array($entityModel, 'setUrlaubsUntil'),
                'onlinetime'     => array($entityModel, 'setOnlinetime'),
                'user_lastip'    => array($entityModel, 'setUserLastip'),
                'ip_at_reg'      => array($entityModel, 'setIpAtReg'),
                'register_time'  => array($entityModel, 'setRegisterTime'),
                'user_agent'     => array($entityModel, 'setUserAgent'),
                'current_page'   => array($entityModel, 'setCurrentPage'),
                'current_planet' => array($entityModel, 'setCurrentPlanet')
                ),
            'researches' => array(
                'spy_tech'              => array($entityModel, 'setSpyTech'),
                'computer_tech'         => array($entityModel, 'setComputerTech'),
                'military_tech'         => array($entityModel, 'setMilitaryTech'),
                'shield_tech'           => array($entityModel, 'setShieldTech'),
                'defence_tech'          => array($entityModel, 'setDefenceTech'),
                'energy_tech'           => array($entityModel, 'setEnergyTech'),
                'hyperspace_tech'       => array($entityModel, 'setHyperspaceTech'),
                'combustion_tech'       => array($entityModel, 'setCombustionTech'),
                'impulse_motor_tech'    => array($entityModel, 'setImpulseMotorTech'),
                'hyperspace_motor_tech' => array($entityModel, 'setHyperspaceMotorTech'),
                'laser_tech'            => array($entityModel, 'setLaserTech'),
                'ionic_tech'            => array($entityModel, 'setIonicTech'),
                'buster_tech'           => array($entityModel, 'setBusterTech'),
                'intergalactic_tech'    => array($entityModel, 'setIntergalacticTech'),
                'expedition_tech'       => array($entityModel, 'setExpeditionTech'),
                'graviton_tech'         => array($entityModel, 'setGravitonTech')
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
            $session->addInfo('User successfully updated.');
        } catch (One_Core_Exception_DaoError $e) {
            $session->addError('Could not save User updates.');
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

        $this->addTab('legacies-user-general', 'general', 'General');
        $this->addTab('legacies-user-profile', 'profile', 'Profile');
        $this->addTab('legacies-user-options', 'options', 'Options');
        $this->addTab('legacies-user-meta', 'meta', 'Metadata');
        $this->addTab('legacies-user-researches', 'researches', 'Researches');
//        $this->addTab('legacies-user-officers', 'officers', 'Officers');
    }
}