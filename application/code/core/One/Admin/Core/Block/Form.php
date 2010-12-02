<?php

class One_Admin_Core_Block_Form
    extends One_Core_Block_Html
{
    protected $_form = array();

    protected function _construct($options)
    {
        $this->headScript()
            ->appendFile('/js/jquery.js')
            ->appendFile('/js/core.js')
            ->appendFile('/js/admin/core.js')
            ->appendFile('/js/admin/form.js')
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
                    'params'    => array('tag' => 'div')
                    )
                ),
            );

        $formConfig = array_merge($defaultConfig, (array)$formConfig);

        $subForm = new Zend_Form_SubForm($formConfig);
        $this->_form->addSubForm($subForm, $name);

        return $this;
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
}