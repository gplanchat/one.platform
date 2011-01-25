<?php
/**
 * This file is part of One.Platform
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
 * Base helper class
 *
 * @since 0.1.2
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
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