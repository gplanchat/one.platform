<?php

class One_Cms_Block_Page
    extends One_Core_BlockAbstract
{
    protected $_model = null;

    protected function _construct($options)
    {
        $options = parent::_construct($options);

        $this->setData('page_id', $this->getRequest()->getParam('page-id'));

        $this->_model = $this->app()
            ->getSingleton('cms/page')
        ;

        return $options;
    }

    protected function _render()
    {
        $pageId = $this->getPageId();
        if (is_int($pageId)) {
            $this->_model
                ->load($pageId);
        } else {
            $this->_model
                ->load(array(
                    'path'       => $pageId,
                    'website_id' => $this->app()->getWebsiteId()
                ));
        }

        return $this->_model->getContent();
    }
}