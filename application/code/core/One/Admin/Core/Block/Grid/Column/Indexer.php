<?php

class One_Admin_Core_Block_Grid_Column_Indexer
    extends One_Admin_Core_Block_Grid_ColumnAbstract
{
    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/column/indexer.phtml';
        }
        return parent::getTemplate();
    }

    public function getLabelTemplate()
    {
        if (!$this->_hasData('label_template')) {
            $this->_setData('label_template', 'grid/title/indexer.phtml');
        }
        return $this->_getData('label_template');
    }

    public function getFilterTemplate()
    {
        if (!$this->_hasData('filter_template')) {
            $this->_setData('filter_template', 'grid/filter/indexer.phtml');
        }
        return $this->_getData('filter_template');
    }
}