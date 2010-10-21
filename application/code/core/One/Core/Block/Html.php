<?php

class One_Core_Block_Html
    extends One_Core_BlockAbstract
{
    protected $_template = null;

    protected function _construct($options)
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

    protected function _render($script = null)
    {
        ob_start();

        if ($script === null) {
            $script = $this->getTemplate();
        }
        include $this->getScriptPath() . One::DS . $script;

        return ob_get_clean();
    }
}