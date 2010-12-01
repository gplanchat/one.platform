<?php

class One_Admin_Core_Block_Grid_MassActions
    extends One_Core_Block_Html
{
    protected $_grid = null;

    public function _construct($options)
    {
        if (isset($options['grid'])) {
            $this->_grid = $options['grid'];
            unset($options['grid']);
        }

        return parent::_construct($options);
    }

    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/mass-actions.phtml';
        }
        return $this->_template;
    }
}