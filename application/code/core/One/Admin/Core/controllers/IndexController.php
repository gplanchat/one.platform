<?php

class One_Admin_Html_IndexController
    extends One_Core_Controller_Abstract
{
    public function indexAction()
    {
        var_dump($this->_getAllParams());
    }
}