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
 * Administration base form block
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
class One_Admin_Core_Block_Form
    extends One_Core_Block_Html
{
    protected $_form = array();

    protected function _construct($options)
    {
        $config = $this->app()->getModel('core/config');

        $this->headScript()
            ->appendFile($config->getBaseUrl('js/jquery.js'))
            ->appendFile($config->getBaseUrl('js/tiny_mce/tiny_mce.js'))
            ->appendFile($config->getBaseUrl('js/tiny_mce/jquery.tinymce.js'))
            ->appendFile($config->getBaseUrl('js/core.js'))
            ->appendFile($config->getBaseUrl('admin/js/core.js'))
            ->appendFile($config->getBaseUrl('admin/js/form.js'))
        ;

        $action = '#';
        if (isset($options['action'])) {
            $action = $options['action'];
            unset($options['action']);
        }
        $method = 'post';
        if (isset($options['method']) && in_array(strtolower($options['method']), array('get', 'post'))) {
            $action = $options['method'];
            unset($options['method']);
        }

        $this->_initInstance($action, $method);

        parent::_construct($options);
    }

    protected function _initInstance($action, $method)
    {
        $this->_form = new Zend_Form(array(
            'attribs' => array(
                ),
            'action' => $action,
            'method' => $method,
            'disableLoadDefaultDecorators' => true,
            'view' => $this,
            'decorators' => array(
                'Tooltip',
                array(
                    'decorator' => 'FormErrors',
                    'options'    => array(
                        )
                    ),
                'FormElements',
                'Form'
                ),
            'elementDecorators' => array(
                'element' => array(
                    'decorator' => 'ViewHelper'
                    ),
                'wrapper' => array(
                    'decorator' => 'HtmlTag',
                    'options'    => array(
                        'tag'   => 'div',
                        'class' => 'form-element'
                        )
                    )
                ),
            'elements' => array(
                'form_key' => array(
                    'type' => 'hidden',
                    'name' => 'form_key',
                    'options' => array(
                        ),
                    'decorators' => array(
                        array(
                            'decorator' => 'ViewHelper',
                            'options'   => array(
                                ''
                                )
                            )
                        )
                    )
                )
            ));

        $this->_form->setView($this)
            ->setAction($action)
            ->setMethod($method)
        ;

        return $this;
    }

    protected function _render()
    {
        return $this->_form->render();
    }

    public function addTab($configIdentifier, $name, $label)
    {
        $formConfig = $this->app()->getSingleton('admin.core/config')
            ->getForm($configIdentifier)
        ;

        $defaultConfig = array(
            'disableLoadDefaultDecorators' => true,
            'legend' => $label,
            'decorators' => array(
                'Tooltip',
                'FormElements',
                'fieldset' => array(
                    'decorator' => 'Fieldset',
                    'options'    => array(
                        ),
                    ),
                'wrapper' => array(
                    'decorator' => 'HtmlTag',
                    'options'    => array(
                        'tag'   => 'div',
                        'class' => 'subform'
                        )
                    )
                ),
            'elementDecorators' => array(
                'element' => array(
                    'decorator' => 'ViewHelper'
                    ),
                'label' => array(
                    'decorator' => 'Label'
                    ),
                'wrapper' => array(
                    'decorator' => 'HtmlTag',
                    'params'    => array(
                        'tag'   => 'div',
                        'class' => 'form-element'
                        )
                    )
                )
            );

        $formConfig = array_merge($defaultConfig, (array)$formConfig);

        $subForm = new Zend_Form_SubForm($formConfig);
        $this->_form->addSubForm($subForm, $name);

        return $this;
    }

    public function getTab($tabName)
    {
        return $this->_form->getSubForm($tabName);
    }

    public function getTabs()
    {
        return $this->_form->getSubForms();
    }

    public function setAction($action)
    {
        $this->_form->setAction($action);

        return $this;
    }

    /**
     *
     * @param mixed $values
     * @param Zend_Form_Subform $element
     */
    public function populate($values, $form = null)
    {
        $this->_form->populate($values);

        return $this;
    }
}