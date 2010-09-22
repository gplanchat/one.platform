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
 * XML file parser
 *
 * FIXME: PHPDoc
 *
 * @access      public
 * @author      gplanchat
 * @category    Config
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Model_Parser_Xml
    extends One_Core_Model_Parser_Xml_NodeAbstract
{
    protected $_handle = NULL;

    /**
     * FIXME: PHPDoc
     *
     */
    protected function _construct()
    {
        if (!$this->hasData('filename')) {
            Nova::throwException('core/invalidConstructorParams');
        }

        $this->_init();
    }

    /**
     * Initialization method, may be reimplemented if needed.
     *
     * @return One_Core_Model_Parser_Xml
     */
    protected function _init()
    {
        $this->_handle = simplexml_load_file($this->getData('filename'));

        return $this;
    }

    /**
     * Tells wether if this is a document node
     *
     * @return bool
     */
    public function isDocument()
    {
        return true;
    }
}