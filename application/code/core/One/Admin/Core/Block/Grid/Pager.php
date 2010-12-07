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
 *     - Neither the name of Zend Technologies USA, Inc. nor the names of its
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
 * documentation for further information about customizing XNova.
 *
 */

/**
 * Administration grid pager block
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
class One_Admin_Core_Block_Grid_Pager
    extends One_Core_Block_Html
{
    protected $_grid = null;

    public function _construct($options)
    {
        if (isset($options['grid'])) {
            $this->_grid = $options['grid'];
            unset($options['grid']);
        }

        return parent::_construct($options);
    }

    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/pager.phtml';
        }
        return $this->_template;
    }

    public function getPage()
    {
        return $this->_grid->getPage();
    }

    public function getPageSize()
    {
        return $this->_grid->getPageSize();
    }

    public function getPageCount()
    {
        return $this->_grid->getPageCount();
    }

    public function getItemsCount()
    {
        return $this->_grid->getItemsCount();
    }

    public function getLastPageNum()
    {
        return $this->getPageCount();
    }

    public function getCurrentPageNum()
    {
        return $this->getPage();
    }

    public function getPageItemCountList()
    {
        return array(
            5, 10, 20, 30, 50, 75, 100, 150, 200
            );
    }

    public function getPageUrl($page, $itemsCount = null)
    {
        if ($itemsCount === null) {
            $itemsCount = $this->getPageSize();
        }

        $baseUrl = $this->url(array(
            'path'       => $this->getRequest()->getParam('path'),
            'controller' => $this->getRequest()->getParam('controller')
            ));

        $queryString = http_build_query(array(
            'p' => max(min($page, $this->getLastPageNum()), 1),
            'n' => $itemsCount
            ));

        return $baseUrl . '?' . $queryString;
    }

    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPageNum() + 1);
    }

    public function getPrevPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPageNum() - 1);
    }

    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getLastPageNum());
    }

    public function getFirstPageUrl()
    {
        return $this->getPageUrl(1);
    }
}