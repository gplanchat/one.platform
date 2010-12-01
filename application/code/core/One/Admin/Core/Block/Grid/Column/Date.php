<?php

class One_Admin_Core_Block_Grid_Column_Date
    extends One_Admin_Core_Block_Grid_ColumnAbstract
{
    public function getFilterTemplate()
    {
        if (!$this->_hasData('filter_template')) {
            $this->_setData('filter_template', 'grid/filter/range/date.phtml');
        }
        return $this->_getData('filter_template');
    }
}