<?php

class One_Core_Block_Html_Form
    extends One_Core_Block_Html
{
    protected $_config = null;

    protected $_form = null;

    public function _construct($options)
    {
        $this->_config = $this->app()->getSingleton('core/config');

        if (isset($options['form'])) {
            $this->_form = $this->_config->getForm($options['form']);
            unset($options['form']);

            $this->_form->setView($this);
        }

        return parent::_construct($options);
    }

    public function _render()
    {
        $this->_form->addElement('submit', 'submit', array());

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
}