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
 * documentation for further information about customizing One.Platform.
 *
 */

/**
 * Administration grid column type 'select' block
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
class One_Admin_Core_Block_Grid_Column_Select
    extends One_Admin_Core_Block_Grid_ColumnAbstract
{
    protected $_options = array();

    protected function _construct($options)
    {
        if (isset($options['values'])) {
            $this->setOptions($options['values']);
            unset($options['values']);
        }

        return parent::_construct($options);
    }

    public function addOption($value, $label = null)
    {
        if ($label === null) {
            $label = $value;
        }
        $this->_options[$value] = $label;

        return $this;
    }

    public function setOptions($options)
    {
        $this->_options = array();
        foreach ($options as $optionValue => $optionsLabel) {
            $this->addOption($optionValue, $optionsLabel);
        }
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function getFilterTemplate()
    {
        if (!$this->_hasData('filter_template')) {
            $this->_setData('filter_template', 'grid/filter/select.phtml');
        }
        return $this->_getData('filter_template');
    }

    public function getTemplate()
    {
        if (!$this->_template) {
            $this->_template = 'grid/column/select.phtml';
        }
        return $this->_template;
    }

    public function getLabel()
    {
        $value = $this->getValue();

        if (!isset($this->_options[$value])) {
            return '';
        }

        return $this->_options[$value];
    }
}