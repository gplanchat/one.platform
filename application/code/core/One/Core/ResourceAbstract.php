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
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One
 * @subpackage  One_Core
 */
abstract class One_Core_ResourceAbstract
    extends One_Core_Object
    implements One_Core_ResourceInterface
{
    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @var Zend_Db_Adapter
     */
    private $_readAdapter = NULL;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @var Zend_Db_Adapter
     */
    private $_writeAdapter = NULL;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @return Zend_Db_Adapter
     */
    public function getReadAdapter()
    {
        return $this->_readAdapter;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @return Zend_Db_Adapter
     */
    public function getWriteAdapter()
    {
        return $this->_writeAdapter;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @param string|Zend_Db_Adapter
     * @return One_Core_ResourceInterface
     */
    public function setReadAdapter($adapter)
    {
        if ($adapter instanceof Zend_Db_Adapter) {
            $this->_readAdapter = $adapter;
        } else {
            $this->_readAdapter = NULL; // FIXME
        }
        return $this;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @param string|Zend_Db_Adapter
     * @return One_Core_ResourceInterface
     */
    public function setWriteAdapter($adapter)
    {
        if ($adapter instanceof Zend_Db_Adapter) {
            $this->_writeAdapter = $adapter;
        } else {
            $this->_writeAdapter = NULL; // FIXME
        }
        return $this;
    }
}