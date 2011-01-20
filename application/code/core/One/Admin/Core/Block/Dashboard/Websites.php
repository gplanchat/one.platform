<?php

class One_Admin_Core_Block_Dashboard_Websites
    extends One_Core_Block_Html
{
    protected function _construct($options)
    {
        parent::_construct($options);

        $config = $this->app()->getModel('core/config');

        $this->headScript()
            ->appendFile($config->getBaseUrl('js/jquery.js'))
            ->appendFile($config->getBaseUrl('js/core.js'))
            ->appendFile($config->getBaseUrl('admin/js/tree.js'))
        ;
    }
}