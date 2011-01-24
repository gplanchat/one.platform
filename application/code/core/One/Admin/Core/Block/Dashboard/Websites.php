<?php

class One_Admin_Core_Block_Dashboard_Websites
    extends One_Core_Block_Html
{
    protected function _construct($options)
    {
        parent::_construct($options);

        $this->headScript()
            ->appendFile($this->getScriptUrl('jquery.js'))
            ->appendFile($this->getScriptUrl('core.js'))
            ->appendFile($this->getScriptUrl('tree.js'))
        ;
    }
}