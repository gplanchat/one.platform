<?php

class One_Admin_Core_Block_Dashboard_Websites
    extends One_Core_Block_Html
{
    protected function _construct($options)
    {
        parent::_construct($options);

        $config = $this->app()->getModel('core/config');

        $this->headScript()
            ->appendFile($config->getUrl('/js/jquery.js'))
            ->appendFile($config->getUrl('/js/core.js'))
            ->appendFile($config->getUrl('/admin/js/tree.js'))
        ;
    }
}