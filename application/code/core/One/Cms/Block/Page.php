<?php

class One_Cms_Block_Page
    extends One_Core_BlockAbstract
{
    protected $_model = null;

    protected $_pageId = null;

    protected function _construct($options)
    {
        $options = parent::_construct($options);

        $this->_pageId = $this->getRequest()->getParam('page-id');

        $this->_model = $this->app()
            ->getSingleton('cms/page')
        ;

        return $options;
    }

    protected function _render()
    {
        if (is_int($this->_pageId)) {
            $this->_model
                ->load($this->_pageId);
        } else {
            $this->_model
                ->load(array(
                    'path'       => $this->_pageId,
                    'website_id' => $this->app()->getWebsiteId()
                ));
        }

        return $this->_model->getContent();
    }
}