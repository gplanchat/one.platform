<?php

class One_Admin_Core_IndexController
    extends One_Core_ControllerAbstract
{
    public function indexAction()
    {
        $this->_forward('index', 'grid');

//        $this->loadLayout();
//        $this->renderLayout();
    }
}