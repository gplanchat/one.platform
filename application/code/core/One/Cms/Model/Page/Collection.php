<?php

class One_Cms_Model_Page_Collection
    extends One_Core_Bo_CollectionAbstract
{
    protected function _construct($options)
    {
        $this->_init('cms/page', 'cms/page');

        return parent::_construct($options);
    }
}