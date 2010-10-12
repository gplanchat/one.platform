<?php

class One_Core_block_Html_Navigation
    extends One_Core_Block_Html
{
    protected $_headBlock;

    protected $_navigationHelper = null;

    public function setHeadBlock($block)
    {
        if (is_string($block)) {
            $this->_headBlock = $this->getLayout()->getBlock($block);
        } else {
            $this->_headBlock = $block;
        }
        return $this;
    }

    public function getHeadBlock()
    {
        return $this->_headBlock;
    }

    public function addPage()
    {
        return $this;
    }

    protected function _call($method, $params)
    {
        if ($this->_navigationHelper === null) {
            $this->_navigationHelper = new Zend_View_Helper_Navigation();
            $this->_navigationHelper->view = new Zend_View();
        }

        $reflectionObject = new ReflectionObject($this->_navigationHelper);
        if ($reflectionObject->hasMethod($method)) {
            $reflectionMethod = $reflectionObject->getMethod($method);
            $reflectionMethod->invokeArgs($this->_navigationHelper, $params);
        } else {
            $this->_navigationHelper->__call($method, $params);
        }

        return $this;
    }
}