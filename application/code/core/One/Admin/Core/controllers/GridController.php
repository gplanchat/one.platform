<?php

class One_Admin_Core_GridController
    extends One_Admin_Core_ControllerAbstract
{
    public function indexAction()
    {
        $grid = $this->_prepareGrid('admin-user-entity', 'user/entity.collection');

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_prepareForm('admin.form', 'user.entity.form');

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