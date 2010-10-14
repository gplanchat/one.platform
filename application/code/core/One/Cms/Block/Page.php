<?php

class One_Cms_Block_Page
    extends One_Core_BlockAbstract
{
    protected $_model = null;

    protected $_path = null;

    protected function _construct($options)
    {
        $options = parent::_construct($options);

        $this->_path = $this->getRequest()->getParam('page-id');

        $this->_model = $this->app()
            ->getSingleton('cms/page')
        ;

        return $options;
    }

    protected function _render()
    {
        $this->_model
            ->load(array(
                'path'       => $this->_path,
                'website_id' => $this->app()->getWebsiteId()
            ));

        return $this->_model->getContent();
    }
}