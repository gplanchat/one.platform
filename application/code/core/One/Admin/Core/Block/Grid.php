<?php
/**
 * This file is part of One.Platform
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Zend Technologies USA, Inc. nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */

/**
 * Administration base grid block
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
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
            ->appendFile($config->getUrl('/js/jquery.js'))
            ->appendFile($config->getUrl('/js/core.js'))
            ->appendFile($config->getBaseUrl('/js/core.js'))
            ->appendFile($config->getBaseUrl('/js/grid.js'))
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