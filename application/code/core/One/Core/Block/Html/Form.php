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
 * HTML form block
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class One_Core_Block_Html_Form
    extends One_Core_Block_Html
{
    protected $_config = null;

    /**
     *
     * @var Zend_Form
     */
    protected $_form = null;

    protected $_submitLabel = 'Submit';

    protected $_submitName = 'submit';

    protected function _construct($options)
    {
        $this->_config = $this->app()->getSingleton('core/config');

        if (isset($options['form'])) {
            $this->loadForm($options['form']);
            unset($options['form']);
        } else {
            $this->_form = new Zend_Form(array(
                'decorators' => array(
                    'ViewScript'
                    )
                ));
        }
        if (isset($options['submit-label']) && !empty($options['submit-label'])) {
            $this->_submitLabel = $options['submit-label'];
            unset($options['submit-label']);
        }
        if (isset($options['submit-name']) && !empty($options['submit-name'])) {
            $this->_submitName = $options['submit-name'];
            unset($options['submit-name']);
        }

        return parent::_construct($options);
    }

    public function _render()
    {
        $this->_form->addElement('submit', $this->getSubmitName(), array(
            'label' => $this->getSubmitLabel()
            ));

        $decorator = $this->_form->getDecorator('ViewScript');
        if ($decorator !== null && $decorator !== false) {
            $decorator->setViewScript($this->getTemplate());
        }

        return $this->_form->render($this);
    }

    public function setAction($action, $name = null)
    {
        if (is_array($action)) {
            $action = $this->app()->getRouter()->assemble($action, $name);
        }

        $this->_form->setAction($action);
    }

    public function loadForm($formName)
    {
        $this->_form = $this->_config->getForm($formName);

        $this->_form->setView($this);

        return $this;
    }

    public function getForm()
    {
        return $this->_form;
    }

    public function getSubForm($name)
    {
        return $this->_form->getSubForm($name);
    }

    public function getSubForms()
    {
        return $this->_form->getSubForms();
    }

    public function addFieldset($fieldset)
    {
        $this->_form->addSubForm($this->_config->getFieldset($fieldset), $fieldset);
    }

    public function setSubmitLabel($label)
    {
        $this->_submitLabel = $label;

        return $this;
    }

    public function setSubmitName($name)
    {
        $this->_submitName = $name;

        return $this;
    }

    public function getSubmitLabel()
    {
        return $this->_submitLabel;
    }

    public function getSubmitName()
    {
        return $this->_submitName;
    }
}