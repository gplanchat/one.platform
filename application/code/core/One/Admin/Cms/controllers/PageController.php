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

        $this->_buildEditForm();

        $this->getLayout()
            ->getBlock('container')
            ->addButtonDuplicate()
            ->addButtonDelete()
        ;

        $this->renderLayout();
    }

    public function editPostAction()
    {
        $this->app()
            ->getModel('admin.core/session')
            ->addInfo('Edit action is not implemented.')
        ;// FIXME

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ));

        $this->_redirect($url);
    }

    public function newAction()
    {
        $this->_buildEditForm();

        $this->renderLayout();
    }

    public function newPostAction()
    {
        $this->app()
            ->getModel('admin.core/session')
            ->addInfo('Add action is not implemented.')
        ;// FIXME

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ));

        $this->_redirect($url);
    }

    public function deleteAction()
    {
        $this->app()
            ->getModel('admin.core/session')
            ->addInfo('Delete action is not implemented.')
        ;// FIXME

        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'index'
                ));

        $this->_redirect($url);
    }

    protected function _buildEditForm()
    {
        $this->prepareForm('cms/page', $this->_getParam('id'));
        $url = $this->app()
            ->getRouter()
            ->assemble(array(
                'path'       => $this->_getParam('path'),
                'controller' => $this->_getParam('controller'),
                'action'     => 'edit-post'
                ));
        $this->_form->setAction($url);

        $this->addTab('cms-page-general', 'general', 'General');
        $this->addTab('cms-page-content', 'content', 'Content');
        $this->addTab('cms-page-meta', 'meta', 'Meta data');
        $this->addTab('cms-page-layout', 'layout', 'Layout updates');
    }
}