<?php

class One_Admin_Cms_PageController
    extends One_Admin_Core_ControllerAbstract
{
    public function indexAction()
    {
        $this->loadLayout('admin.grid');

        $collection = $this->app()
            ->getModel('cms/page.collection')
            ->setPage($this->_getParam('p'), $this->_getParam('n'));

        $grid = $this->getLayout()
            ->getBlock('grid')
            ->setCollection($collection)
            ->loadColumns('cms-page')
            ->sort($this->_getParam('sort'))
        ;
        $this->_prepareGrid('cms-page', 'cms/page.collection');

        $this->renderLayout();
    }

    public function gridAjaxAction()
    {
        $collection = $this->app()
            ->getModel('cms/page.collection')
            ->setPage($this->_getParam('p'), $this->_getParam('n'));

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ->setBody(Zend_Json::encode($collection->load()->toArray()))
        ;
    }

    public function editAction()
    {
        if ($this->getRequest()->isPost()){
            $this->_forward('edit-post');
            return;
        }

        $this->prepareForm('cms/page', $this->_getParam('id'));
        $this->addTab('cms-page-general', 'general', 'General');
        $this->addTab('cms-page-content', 'content', 'Content');
        $this->addTab('cms-page-meta', 'meta', 'Meta data');
        $this->addTab('cms-page-layout', 'layout', 'Layout updates');

        $this->renderLayout();
    }

    public function editPostAction()
    {
    }

    public function newAction()
    {
    }

    public function newPostAction()
    {
    }

    public function deleteAction()
    {
    }
}