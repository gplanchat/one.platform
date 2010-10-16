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

    public function _call($method, $params)
    {
        return call_user_func(array($this->navigation, $method), $params);
    }
}