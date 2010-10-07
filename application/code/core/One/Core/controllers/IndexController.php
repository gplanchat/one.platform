<?php

class One_Core_IndexController
    extends One_Core_ControllerAbstract
{
    public function indexAction()
    {
        One::getModel('tet/test', null, 'test', 'test');

        $this->view->lang = $this->getRequest()->getParam('path');
        $this->view->module = $this->getRequest()->getParam('module');
        $this->view->controller = $this->getRequest()->getParam('controller');
        $this->view->action = $this->getRequest()->getParam('action');
    }
}