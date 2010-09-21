<?php

class One_Core_IndexController
    extends One_Core_Controller_Abstract
{
    public function indexAction()
    {
        $this->view->lang = $this->getRequest()->getParam('lang');
        $this->view->module = $this->getRequest()->getParam('module');
        $this->view->controller = $this->getRequest()->getParam('controller');
        $this->view->action = $this->getRequest()->getParam('action');
    }
}