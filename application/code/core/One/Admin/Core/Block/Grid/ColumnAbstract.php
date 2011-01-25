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
 * Administration grid abstract column
 *
 * @abstract
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
abstract class One_Admin_Core_Block_Grid_ColumnAbstract
    extends One_Core_Block_Html
{
    protected $_collection = null;

    public function renderFilter()
    {
        return $this->render($this->getFilterTemplate());
    }

    public function getFilterTemplate()
    {
        if (!$this->_hasData('filter_template')) {
            $this->_setData('filter_template', 'grid/filter/default.phtml');
        }
        return $this->_getData('filter_template');
    }

    public function renderLabel()
    {
        return $this->render($this->getLabelTemplate());
    }

    public function getLabelTemplate()
    {
        if (!$this->_hasData('label_template')) {
            $this->_setData('label_template', 'grid/title/default.phtml');
        }
        return $this->_getData('label_template');
    }

    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/column/default.phtml';
        }
        return parent::getTemplate();
    }

    public function getValue()
    {
        return $this->getCollection()
            ->current()
            ->getData($this->_getData('field'))
        ;
    }

    public function getId()
    {
        return $this->getCollection()
            ->current()
            ->getId()
        ;
    }

    public function setCollection(One_Core_Collection $collection)
    {
        $this->_collection = $collection;

        return $this;
    }

    public function getCollection()
    {
        return $this->_collection;
    }
}