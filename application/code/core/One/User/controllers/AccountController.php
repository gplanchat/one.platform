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
 * @category    Controller
 * @package     One
 * @subpackage  One_User
 */
class One_User_AccountController
    extends One_User_ControllerAbstract
{
    /**
     *
     *
     */
    public function indexAction()
    {
        $this->_requireLogon();

        var_dump($this->_getUser());
    }

    /**
     *
     *
     */
    public function loginAction()
    {
        $this->_requireLogoff();
    }

    /**
     *
     *
     */
    public function loginPostAction()
    {
        $this->_requireLogoff();

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError('user/login');
            return;
        }

        $fields = Nova::getFieldset('user_login');
        $formData = array();
        foreach ($fields as $fieldName => $fieldConfig) {
            $value = $this->getRequest()->getPost($fieldName, null);
            if (is_null($value) || empty($value)) {
                $this->redirectError('user/login');
                return;
            }
            $formData[$fieldName] = $value;
        }

        Nova::getModel('user/'); // FIXME

        $this->_redirectSuccess('user');
    }

    /**
     *
     *
     */
    public function registerAction()
    {
        $this->_requireLogoff();

        var_dump($this->_getUser());
    }

    /**
     *
     *
     */
    public function registerPostAction()
    {
        $this->_requireLogoff();

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError('user/login');
            return;
        }
        $this->_redirectSuccess('user');
    }
}