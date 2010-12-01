<?php

class One_Admin_Core_Block_Grid_Column_Action
    extends One_Admin_Core_Block_Grid_ColumnAbstract
{
    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/column/action.phtml';
        }
        return parent::getTemplate();
    }

    public function getLabelTemplate()
    {
        if (!$this->_hasData('label_template')) {
            $this->_setData('label_template', 'grid/title/action.phtml');
        }
        return $this->_getData('label_template');
    }

    public function renderFilter()
    {
        return '';
    }

    public function getActionUrl()
    {
        return $this->url(array(
            'action'     => 'edit',
            'id'         => $this->getId(),
            'controller' => $this->getRequest()->getParam('controller'),
            'path'       => $this->getRequest()->getParam('path')
            ));
    }
}