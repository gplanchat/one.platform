<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * NOTICE:
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 * Base resource object management interface
 *
 * @uses        One_Core_ResourceInterface
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Model_Resource
    implements One_Core_ResourceInterface
{
    /**
     * FIXME: PHPDoc
     */
    private $_module = 'core';

    /**
     * FIXME: PHPDoc
     */
    private $_config = NULL;

    /**
     * FIXME: PHPDoc
     */
    private $_configPathCache = array();

    /**
     * FIXME: PHPDoc
     */
    protected function _setModule($moduleName)
    {
        $this->_module = $moduleName;
        return $this;
    }

    /**
     * FIXME: PHPDoc
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * FIXME: PHPDoc
     */
    public function getConfig($path = NULL)
    {
        $explodedPath = explode('/', $path);

        /** @var Zend_Config */
        $temporaryNode = $this->_config;
        foreach ($explodedPath as $key) {
            $temporaryNode = $temporaryNode->get($key);
        }
    }

    /**
     * FIXME: PHPDoc
     */
    public function getReadConnection()
    {
        // TODO
    }

    /**
     * FIXME: PHPDoc
     */
    public function getTable()
    {
        // TODO
    }
}