<?php

class One_Admin_Core_Block_Grid_Column_Entity
    extends One_Admin_Core_Block_Grid_Column_Integer
{
    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/column/entity.phtml';
        }
        return parent::getTemplate();
    }

    public function getLabelTemplate()
    {
        if (!$this->_hasData('label_template')) {
            $this->_setData('label_template', 'grid/title/entity.phtml');
        }
        return $this->_getData('label_template');
    }
}