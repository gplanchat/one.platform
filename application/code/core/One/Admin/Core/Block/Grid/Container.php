<?php

class One_Admin_Core_Block_Grid_Container
    extends One_Admin_Core_Block_ContainerAbstract
{
    protected $_grid = null;

    protected function _construct($options)
    {
        if (!isset($options['template'])) {
            $options['template'] = 'grid/container.phtml';
        }
        $gridName = 'grid';
        if (isset($options['grid'])) {
            $gridName = $options['grid'];
            unset($options['grid']);
        }

        parent::_construct($options);

        $this->_grid = $this->getChildNode($gridName);
    }

    public function renderGrid()
    {
        return $this->_grid->render(null);
    }
}