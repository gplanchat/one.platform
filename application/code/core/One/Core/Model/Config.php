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
 * Config BO entity model
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_core
 */
class One_Core_Model_Config
    extends One_Core_Bo_EntityAbstract
{
    protected $_forms = null;

    protected $_fieldsets = null;

    protected $_baseUrl = null;

    protected $_styleUrl = null;

    protected $_scriptUrl = null;

    protected $_host = null;

    protected function _construct($options)
    {
        $this->_init('core/config');

        return parent::_construct($options);
    }

    /**
     * TODO PHPDoc
     *
     * @param string $fieldsetName
     * @return Zend_Form
     */
    public function getFieldset($fieldsetName)
    {
        if ($this->_fieldsets === null) {
            $this->_fieldsets = $this->app()->getConfig('general/fieldsets');
        }
        if (!isset($this->_fieldsets[$fieldsetName])) {
            return null;
        }

        $defaultConfig = array(
            'disableLoadDefaultDecorators' => true,
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

        $formConfig = array_merge($defaultConfig, (array)$this->_fieldsets[$fieldsetName]);

        return new Zend_Form_SubForm($formConfig);
    }

    /**
     * TODO PHPDoc
     *
     * @param string $fieldsetName
     * @return Zend_Form
     */
    public function getFormDisplayGroup($groupName)
    {
        if ($this->_fieldsets === null) {
            $this->_fieldsets = $this->app()->getConfig('general/groups');
        }
        if (!isset($this->_fieldsets[$fieldsetName])) {
            return null;
        }
        return new Zend_Form_SubForm($this->_fieldsets[$fieldsetName]);
    }

    /**
     * TODO PHPDoc
     *
     * @param string $fieldsetName
     * @return Zend_Form
     */
    public function getForm($formName)
    {
        if ($this->_forms === null) {
            $this->_forms = $this->app()->getConfig('general/forms');
        }
        if (!isset($this->_forms[$formName])) {
            return null;
        }
        if (isset($this->_forms[$formName]['params'])) {
            $form = new Zend_Form($this->_forms[$formName]['params']);
        } else {
            $form = new Zend_Form();
        }

        if (isset($this->_forms[$formName]['elements'])) {
            $form->addElements($this->_forms[$formName]['elements']);
        }

        if (isset($this->_forms[$formName]['actions'])) {
            foreach ($this->_forms[$formName]['actions'] as $actionName => $actionUrl) {
                $params = isset($actionUrl['params']) ? $actionUrl['params'] : array();
                $name = isset($actionUrl['name']) ? $actionUrl['name'] : null;
                $reset = isset($actionUrl['reset']) ? $actionUrl['reset'] : false;

                switch ($actionName) {
                case 'submit':
                    $form->setAction($this->app()->getRouter()->assemble($params, $name, $reset));
                    break;

                case 'success':
                case 'error':
                    $form->addElement('hidden', $actionName, array(
                        'value' => $this->app()->getRouter()->assemble($params, $name, $reset),
                        'decorators' => array(
                            'element' => array(
                                'decorator' => 'ViewHelper',
                                'params'    => 'FormHidden'
                                )
                            )
                        ));
                    break;
                }
            }
        }

        if (isset($this->_forms[$formName]['fieldsets'])) {
            foreach ($this->_forms[$formName]['fieldsets'] as $fieldset => $name) {
                if ($fieldset === null) {
                    continue;
                }
                if (!is_string($name) || empty($name)) {
                    $name = $fieldset;
                }
                $form->addSubForm($this->getFieldset($fieldset), $name);
            }
        }

        if (isset($this->_forms[$formName]['groups'])) {
            foreach ($this->_forms[$formName]['groups'] as $name => $groupConfig) {
                if ($groupConfig === null) {
                    continue;
                }
                if (!is_string($name) || empty($name)) {
                    $name = isset($groupConfig['name']) ? $groupConfig['name'] : uniqid('group_');
                }

                $form->addDisplayGroup(array_keys($groupConfig['elements']), $name, isset($groupConfig['options']) ? $groupConfig['options'] : array());
            }
        }

        return $form;
    }

    public function getUrl($path)
    {
        if ($this->_host === null) {
            $this->_host = $this->app()->getConfig('system/hostname');
        }

        if (substr($path, 0, 1) !== '/') {
            $path = '/' . $path;
        }

        return 'http://' . $this->_host . $path;
    }

    public function getBaseUrl($path)
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = $this->app()->getConfig('system/base-url');
        }

        return $this->getUrl($this->_baseUrl . $path);
    }

    public function getScriptUrl($path)
    {
        if ($this->_scriptUrl === null) {
            $this->_scriptUrl = $this->app()->getConfig('system/script-url');
        }

        return $this->getUrl($this->_scriptUrl . $path);
    }

    public function getStyleUrl($path)
    {
        if ($this->_styleUrl === null) {
            $this->_styleUrl = $this->app()->getConfig('system/style-url');
            $layoutConfig = $this->app()->getConfig('system/layout');

            $this->_styleUrl .= "{$layoutConfig['class']}/{$layoutConfig['design']}/{$layoutConfig['template']}/";
        }

        return $this->getUrl($this->_styleUrl . $path);
    }
}