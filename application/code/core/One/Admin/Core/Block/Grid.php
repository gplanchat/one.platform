<?php

class One_Admin_Core_Block_Grid
    extends One_Core_Block_Html
{
    /**
     * TODO: PHPDoc
     *
     * @var One_Core_Bo_CollectionInterface
     */
    protected $_collection = null;

    protected $_columns = array();

    protected $_pager = null;

    protected $_form = null;

    protected function _construct($options)
    {
        if (!isset($options['template'])) {
            $options['template'] = 'grid/layout.phtml';
        }

        $this->headScript()
            ->appendFile('/js/jquery.js')
            ->appendFile('/js/core.js')
            ->appendFile('/js/admin/core.js')
            ->appendFile('/admin/js/grid.js')
        ;

        return parent::_construct($options);
    }

    public function loadColumns($gridName)
    {
        $grid = $this->app()
            ->getSingleton('admin.core/config')
            ->getGrid($gridName)
        ;

        if ($grid === null) {
            return $this;
        }

        $this->setName($gridName);

        foreach ($grid as $columnName => $columnConfig) {
            $params = isset($columnConfig['params']) && is_array($columnConfig['params']) ? $columnConfig['params'] : array();
            $name = isset($columnName) && is_string($columnName) ? $columnName : null;
            $type = isset($columnConfig['type']) && is_string($columnConfig['type']) ? $columnConfig['type'] : 'admin.core/grid.column.string';

            $this->addColumn($name, $type, $params);
        }

        return $this;
    }

    public function addColumn($name, $type, Array $config)
    {
        $block = $this->app()
            ->getBlock($type, $config, $this->getLayout())
            ->setBasePath($this->getBasePath())
            ->setScriptPath($this->getScriptPath())
            ->setName($name)
        ;

        $block->setCollection($this->getCollection());

        $this->appendChild("column.{$name}", $block);

        $this->_columns[(string) $name] = $block;

        return $this;
    }

    public function getColumns()
    {
        return $this->_columns;
    }

    public function setCollection(One_Core_Bo_CollectionInterface $collection)
    {
        $this->_collection = $collection;

        foreach ($this->_columns as $column) {
            $column->setCollection($collection);
        }

        return $this;
    }

    public function getCollection()
    {
        return $this->_collection;
    }

    public function render($name)
    {
        $block = $this->app()
            ->getBlock('admin.core/grid.pager', array('grid' => $this), $this->getLayout())
            ->setBasePath($this->getBasePath())
            ->setScriptPath($this->getScriptPath())
        ;
        $this->appendChild("pager", $block);

        $block = $this->app()
            ->getBlock('admin.core/grid.mass-actions', array('grid' => $this), $this->getLayout())
            ->setBasePath($this->getBasePath())
            ->setScriptPath($this->getScriptPath())
        ;
        $this->appendChild("mass-actions", $block);

        $this->_collection->load();

        return parent::render($name);
    }

    public function setPage($page, $pageCount = null)
    {
        $this->_collection->setPage($page, $pageCount);

        return $this;
    }

    public function getPage()
    {
        return $this->_collection->getPage();
    }

    public function getPageSize()
    {
        return $this->_collection->getPageSize();
    }

    public function getPageCount()
    {
        return $this->_collection->getPageCount();
    }

    public function getItemsCount()
    {
        return $this->_collection->count();
    }

    public function sort($fields)
    {
        if (!is_array($fields)) {
            return $this;
        }
        foreach ($fields as $fieldName => $order) {
            $order = strtoupper($order);
            if (!in_array($order, array('ASC', 'DESC'))) {
                continue;
            }
            $this->_collection->sort($fieldName, $order);
        }
    }
}