<?php

class One_Core_Block_Text
    extends One_Core_BlockAbstract
{
    protected $_content = null;

    public function setContent($content)
    {
        $this->_content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->_content;
    }

    protected function _render()
    {
        return $this->getContent();
    }
}