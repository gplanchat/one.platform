<?php

abstract class One_Admin_Core_Block_Grid_ColumnAbstract
    extends One_Core_Block_Html
{
    protected $_collection = null;

    public function renderFilter()
    {
        return $this->render($this->getFilterTemplate());
    }

    public function getFilterTemplate()
    {
        if (!$this->_hasData('filter_template')) {
            $this->_setData('filter_template', 'grid/filter/default.phtml');
        }
        return $this->_getData('filter_template');
    }

    public function renderLabel()
    {
        return $this->render($this->getLabelTemplate());
    }

    public function getLabelTemplate()
    {
        if (!$this->_hasData('label_template')) {
            $this->_setData('label_template', 'grid/title/default.phtml');
        }
        return $this->_getData('label_template');
    }

    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'grid/column/default.phtml';
        }
        return parent::getTemplate();
    }

    public function getValue()
    {
        return $this->getCollection()
            ->current()
            ->getData($this->_getData('field'))
        ;
    }

    public function getId()
    {
        return $this->getCollection()
            ->current()
            ->getId()
        ;
    }

    public function setCollection(One_Core_Collection $collection)
    {
        $this->_collection = $collection;

        return $this;
    }

    public function getCollection()
    {
        return $this->_collection;
    }
}