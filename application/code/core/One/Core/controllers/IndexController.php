<?php

class One_Core_IndexController
    extends One_Core_ControllerAbstract
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}