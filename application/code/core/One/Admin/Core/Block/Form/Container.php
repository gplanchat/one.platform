<?php

class One_Admin_Core_Block_Form_Container
    extends One_Admin_Core_Block_ContainerAbstract
{
    /**
     * @var One_Admin_Core_Block_Form
     */
    protected $_form = null;

    public function _construct($options)
    {
        if (!isset($options['template'])) {
            $options['template'] = 'form/container.phtml';
        }
        $formName = 'form';
        if (isset($options['form'])) {
            $formName = $options['form'];
        }

        parent::_construct($options);

        $this->_form = $this->getChildNode($formName);
    }

    public function renderForm()
    {
        return $this->_form->render(null);
    }

    public function getTabs()
    {
        return $this->_form->getTabs();
    }

    public function getEntityLabel()
    {
        return $this->_form->getModel()->getPath();
    }
}