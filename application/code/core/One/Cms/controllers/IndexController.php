<?php

class One_Cms_IndexController
    extends One_Core_Controller_Abstract
{
    public function pageAction()
    {
        var_dump($this->_getAllParams());
    }
}