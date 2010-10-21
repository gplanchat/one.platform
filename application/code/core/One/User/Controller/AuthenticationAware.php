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
 * @category    Exception
 * @package     One
 * @subpackage  One_Core
 */
abstract class One_User_Controller_AuthenticationAware
    extends One_Core_ControllerAbstract
{
    protected $_userModel = null;

    /**
     * TODO: PHPDoc
     *
     * @return One_User_Model_Entity
     */
    protected function _getUser()
    {
        if (is_null($this->_userModel)) {
            $this->_userModel = $this->app()
                ->getSingleton('user/session')
                ->getUserEntity()
            ;
        }
        return $this->_userModel;
    }

    /**
     * TODO: PHPDoc
     *
     * @return void
     */
    protected function _isLoggedIn()
    {
        return ($this->_getUser()->getId() !== null);
    }

    /**
     * TODO: PHPDoc
     *
     * @return void
     */
    protected function _redirectIfNotLoggedIn($redirect = null)
    {
        if ($this->_isLoggedIn()) {
            return;
        }

        $this->_redirect(is_null($redirect) ? 'account/login' : $redirect);
    }

    /**
     * TODO: PHPDoc
     *
     * @return void
     */
    protected function _redirectIfLoggedIn($redirect = null)
    {
        if (!$this->_isLoggedIn()) {
            return;
        }

        $this->_redirect(is_null($redirect) ? 'user' : $redirect);
    }
}