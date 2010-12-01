<?php

class One_Admin_Core_Block_Grid_Column_Integer
    extends One_Admin_Core_Block_Grid_ColumnAbstract
{
    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/column/integer.phtml';
        }
        return parent::getTemplate();
    }

    public function getFilterTemplate()
    {
        if (!$this->_hasData('filter_template')) {
            $this->_setData('filter_template', 'grid/filter/range/integer.phtml');
        }
        return $this->_getData('filter_template');
    }
}