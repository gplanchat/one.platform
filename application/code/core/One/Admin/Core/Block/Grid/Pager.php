<?php

class One_Admin_Core_Block_Grid_Pager
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
            $this->_template = 'grid/pager.phtml';
        }
        return $this->_template;
    }

    public function getPage()
    {
        return $this->_grid->getPage();
    }

    public function getPageSize()
    {
        return $this->_grid->getPageSize();
    }

    public function getPageCount()
    {
        return $this->_grid->getPageCount();
    }

    public function getItemsCount()
    {
        return $this->_grid->getItemsCount();
    }

    public function getLastPageNum()
    {
        return $this->getPageCount();
    }

    public function getCurrentPageNum()
    {
        return $this->getPage();
    }

    public function getPageItemCountList()
    {
        return array(
            5, 10, 20, 30, 50, 75, 100, 150, 200
            );
    }

    public function getPageUrl($page, $itemsCount = null)
    {
        if ($itemsCount === null) {
            $itemsCount = $this->getPageSize();
        }

        $baseUrl = $this->url(array(
            'path'       => $this->getRequest()->getParam('path'),
            'controller' => $this->getRequest()->getParam('controller')
            ));

        $queryString = http_build_query(array(
            'p' => max(min($page, $this->getLastPageNum()), 1),
            'n' => $itemsCount
            ));

        return $baseUrl . '?' . $queryString;
    }

    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPageNum() + 1);
    }

    public function getPrevPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPageNum() - 1);
    }

    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getLastPageNum());
    }

    public function getFirstPageUrl()
    {
        return $this->getPageUrl(1);
    }
}