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

    public function render($name = null)
    {
        $this->_beforeRender();
        $content = $this->getContent();
        $this->_afterRender();

        return $content;
    }
}