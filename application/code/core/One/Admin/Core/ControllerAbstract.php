<?php

abstract class One_Admin_Core_ControllerAbstract
    extends One_Core_ControllerAbstract
{
    protected $_entityModel = null;

    protected $_collectionModel = null;

    /**
     *
     * @var One_Admin_Core_Block_Form
     */
    protected $_form = null;

    protected function _prepareGrid($gridName, $collectionModel)
    {
        $this->loadLayout('admin.grid');

        if (is_string($collectionModel)) {
            $this->_collectionModel = $this->app()->getModel($collectionModel);
        } else if ($this->_collectionModel === null) {
            $this->_collectionModel = $collectionModel;
        }

        $grid = $this->getLayout()
            ->getBlock('grid')
            ->setCollection($this->_collectionModel)
            ->setPage($this->_getParam('p'), $this->_getParam('n'))
            ->loadColumns('cms-page')
        ;

        return $grid;
    }

    public function prepareForm($entityModel, $entityId = null)
    {
        $this->loadLayout('admin.form');

        if (is_string($entityModel)) {
            $this->_entityModel = $this->app()->getModel($entityModel);
        } else if ($this->_entityModel === null) {
            $this->_entityModel = $entityModel;
        }

        if ($entityId !== null) {
            $this->_entityModel
                ->load($entityId)
            ;
        }

        $this->_form = $this->getLayout()
            ->getBlock('form')
            ->setModel($this->_entityModel)
        ;

        return $this;
    }

    public function addTab($configIdentitifer, $name, $label)
    {
        $this->_form->addTab($configIdentitifer, $name, $label);
    }

    abstract public function indexAction();

    abstract public function newAction();

    abstract public function newPostAction();

    abstract public function editAction();

    abstract public function editPostAction();

    abstract public function deleteAction();
}