<?php

class One_Core_Block_Html_Form
    extends One_Core_Block_Html
{
    protected $_config = null;

    protected $_form = null;

    protected $_submitLabel = 'Submit';

    protected $_submitName = 'submit';

    public function _construct($options)
    {
        $this->_config = $this->app()->getSingleton('core/config');

        if (isset($options['form'])) {
            $this->_form = $this->_config->getForm($options['form']);
            unset($options['form']);

            $this->_form->setView($this);
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

        return $this->_form->render($this->getTemplate());
    }

    public function setAction($action, $name = null)
    {
        if (is_array($action)) {
            $action = $this->app()->getRouter()->assemble($action, $name);
        }

        $this->_form->setAction($action);
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