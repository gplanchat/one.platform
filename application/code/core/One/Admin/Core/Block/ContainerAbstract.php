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
 * Administration abstract container
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
abstract class One_Admin_Core_Block_ContainerAbstract
    extends One_Core_Block_Html
{
    protected $_title = null;

    protected $_titleClass = null;

    protected $_buttons = array();

    public function addButton($name, $label = null, $action = null, Array $params = array())
    {
        if (!isset($this->_buttons[$name])) {
            $params = array_merge(array(
                'id'                           => uniqid('id_'),
                'label'                        => $label,
                'onclick'                      => $action,
                'disableLoadDefaultDecorators' => true,
                'escape'                       => false,
                'viewScript'                   => 'admin/container/title/button.phtml',
                'decorators' => array(
                    'Tooltip',
                    'ViewScript'
                    )
                ), $params);
            $this->_buttons[$name] = new Zend_Form_Element_Button($name, $params);
        } else {
            $params = array_merge(array(
                'label'                        => $label,
                'onclick'                      => $action
                ), $params);

            $this->_buttons[$name]->setOptions($params);
        }

        return $this;
    }

    public function addButtonBack($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button back';
        } else {
            $params['class']  .= ' scalable button back';
        }
        if ($label === null) {
            $label = 'Back';
        }

        return $this->addButton('back', $label, '', $params);
    }

    public function addButtonAdd($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button add';
        } else {
            $params['class']  .= ' scalable button add';
        }
        if ($label === null) {
            $label = 'Add Item';
        }

        return $this->addButton('add', $label, '', $params);
    }

    public function addButtonSave($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button save';
        } else {
            $params['class']  .= ' scalable button save';
        }
        if ($label === null) {
            $label = 'Save Item';
        }

        return $this->addButton('save', $label, '', $params);
    }

    public function addButtonSaveContinue($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button save';
        } else {
            $params['class']  .= ' scalable button save';
        }
        if ($label === null) {
            $label = 'Save and Continue Edit';
        }

        return $this->addButton('save-continue', $label, '', $params);
    }

    public function addButtonReset($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button';
        } else {
            $params['class']  .= ' scalable button';
        }
        if ($label === null) {
            $label = 'Reset';
        }

        return $this->addButton('reset', $label, '', $params);
    }

    public function addButtonDuplicate($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button add';
        } else {
            $params['class']  .= ' scalable button add';
        }
        if ($label === null) {
            $label = 'Duplicate';
        }

        return $this->addButton('duplicate', $label, '', $params);
    }

    public function addButtonDelete($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button delete';
        } else {
            $params['class']  .= ' scalable button delete';
        }
        if ($label === null) {
            $label = 'Delete Item';
        }

        return $this->addButton('delete', $label, '', $params);
    }

    public function addButtonCancel($label = null, $params = array())
    {
        if (!isset($params['class'])) {
            $params['class']  = 'scalable button cancel';
        } else {
            $params['class']  .= ' scalable button cancel';
        }
        if ($label === null) {
            $label = 'Cancel';
        }

        return $this->addButton('cancel', $label, '', $params);
    }

    public function getButtons()
    {
        return $this->_buttons;
    }

    public function setTitle($label, $class = null)
    {
        $this->_title = $label;
        if ($class !== null) {
            $this->_titleClass = $class;
        }

        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getTitleClass()
    {
        if ($this->_titleClass === null) {
            $this->_titleClass = 'head-edit-form';
        }
        return $this->_titleClass;
    }

    public function setTitleClass($class)
    {
        $this->_titleClass = $class;

        return $this;
    }
}