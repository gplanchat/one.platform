<?php

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