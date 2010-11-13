<?php

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