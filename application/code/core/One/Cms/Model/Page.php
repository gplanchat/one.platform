<?php

class One_Cms_Model_Page
    extends One_Core_Bo_EntityAbstract
{
    protected function _construct($options)
    {
        $this->_init('cms/page');

        return parent::_construct($options);
    }
}