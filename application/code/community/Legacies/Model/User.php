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
 * Page entity model
 *
 * @access      public
 * @author      gplanchat
 * @category    Cms
 * @package     One_Cms
 * @subpackage  One_Cms
 */
class Legacies_Model_User
    extends One_Core_Bo_EntityAbstract
{
    protected $_currentPlanet = null;

    protected function _construct($options)
    {
        $this->_init('legacies/user');

        return parent::_construct($options);
    }

    public function getCurrentPlanetInstance()
    {
        if (!$this->_currentPlanet) {
            $this->_currentPlanet = $this->app()
                ->getModel('legacies/planet')
                ->load($this->getData('current_planet'));
        }
        return $this->_currentPlanet;
    }

    public function setCurrentPlanetInstance(Legacies_Model_Planet $planet)
    {
        $this->_currentPlanet = $planet;
        $this->setCurrentPlanet($planet->getId());

        return $this;
    }

    public function updateCurrentPlanet()
    {
        $request = Zend_Registry::get('request');

        if (($planetId = $request->getQuery('cp')) === null) {
            return $this;
        }

        $planet = One::app()
            ->getModel('legacies/planet')
            ->load(intval($planetId))
        ;

        if (!$planet->getId() || $planet->getIdOwner() !== $this->getId()) {
            $this->app()
                ->throwException('core/invalid-method-call',
                    'Planet not found or planet not attached to the current user.')
            ;
        }

        $clone = clone $this;
        $clone->resetData()->setCurrentPlanet($planet->getId())->save();
        unset($clone);

        $this->setCurrentPlanetInstance($planet);

        return $this;
    }
}