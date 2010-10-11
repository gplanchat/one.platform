<?php

class One_Core_Block_Html
    extends One_Core_Block_Text
{
    protected $_template = null;

    public function _construct($options)
    {
        if (isset($options['template'])) {
            $this->setTemplate($options['template']);
            unset($options['template']);
        }
        return parent::_construct($options);
    }

    public function setTemplate($template)
    {
        $this->_template = $template;

        return $this;
    }

    public function getTemplate()
    {
        return $this->_template;
    }

    protected function _render()
    {
        ob_start();

        include $this->getScriptPath() . One::DS . $this->getTemplate();

        return ob_get_clean();
    }
}