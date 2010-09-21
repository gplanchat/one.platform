<?php

class One_Admin_Cms_IndexController
    extends One_Admin_Core_Controller_Abstract
{
    public function indexAction()
    {
        var_dump($this->_getAllParams());
    }
}