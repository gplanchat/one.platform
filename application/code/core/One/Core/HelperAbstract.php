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
 * Base helper class
 *
 * @since 0.1.2
 *
 * @access      public
 * @author      gplanchat
 * @category    Helper
 * @package     One
 * @subpackage  One_Core
 */
abstract class One_Core_HelperAbstract
{
    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     */
    const DEFAULT_CHARSET = 'UTF-8';

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.2
     *
     * @var One_Core_Model_Layout
     */
    private $_layout = null;

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.2
     *
     * @var One_Core_BlockAbstract
     */
    private $_block = null;

    protected $_app = null;

    protected $_module = null;

    public function __construct($module, Array $options, One_Core_Model_Application $app, One_Core_Model_Layout $layout = null, One_Core_BlockAbstract $block = null)
    {
        $this->_app = $app;
        $this->_module = $module;

        if ($layout !== null) {
            $this->setLayout($layout);
        }
        if ($block !== null) {
            $this->setBlock($block);
        }

        $this->_construct($options);
    }

    protected function _construct($options)
    {
        return $this;
    }

    public function app()
    {
        return $this->_app;
    }

    /**
     * Declare layout
     *
     * @since 0.1.2
     *
     * @param   One_Core_Model_Layout $layout
     * @return  One_Core_HelperAbstract
     */
    public function setLayout(One_Core_Model_Layout $layout)
    {
        $this->_layout = $layout;

        return $this;
    }

    /**
     * Retrieve layout model object
     *
     * @since 0.1.2
     *
     * @return One_Core_Model_Layout
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * Declare layout
     *
     * @since 0.1.2
     *
     * @param   One_Core_BlockAbstract $block
     * @return  One_Core_HelperAbstract
     */
    public function setBlock(One_Core_BlockAbstract $block)
    {
        $this->_block = $block;

        return $this;
    }

    /**
     * Retrieve layout model object
     *
     * @since 0.1.2
     *
     * @return One_Core_Model_Layout
     */
    public function getBlock()
    {
        return $this->_block;
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.2
     *
     * @param string $format
     * @param mixed $_
     * @return string
     */
    public function __($format, $_ = null)
    {
        $args = func_get_args();
        $format = array_shift($args);

        return vsprintf($format, $args);
    }
}