<?php

class One_Cms_Model_Router_Route
    extends Zend_Controller_Router_Route_Abstract
{
    protected $_table = null;

    protected $_urlVariable = ':';
    protected $_urlDelimiter = '/';
    protected $_regexDelimiter = '#';
    protected $_defaultRegex = null;

    public function __construct()
    {
        $this->_table = new One_Cms_Model_Dal_Route();
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        if (isset($data['cms_page'])) {
        }
        throw new Exception('Unimplemented');
    }

    public function match($path, $partial = false)
    {
        $pathInfo = $path->getPathInfo();
        if (!$partial) {
            $pathInfo = trim($path, $this->_urlDelimiter);
        }

        if ($pathInfo !== '') {
            $pathInfo = explode($this->_urlDelimiter, $pathInfo);
        }

        foreach ($pathInfo as $pathPart) {
            $rowset = $this->_table->find($pathPart);
            if ($rowset->count() !== 0) {
                $lastRow = $rowset->current();
            }
            return false;
        }
        $this->setMatchedPath($path->getPathInfo());

        return array(
            'module'     => 'One_Cms',
            'controller' => 'index',
            'action'     => 'page',
            'cms_page'   => $lastRow->page_entity_id
            );
    }

    /**
     * Instantiates route based on passed Zend_Config structure
     *
     * @param Zend_Config $config Configuration object
     */
    public static function getInstance(Zend_Config $config)
    {
        return new self();
    }
}