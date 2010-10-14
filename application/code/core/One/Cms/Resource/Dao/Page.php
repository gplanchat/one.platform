<?php

class One_Cms_Resource_Dao_Page
    extends One_Core_Dao_Database_Table
{
    public function _construct($data)
    {
        $this->_init('cms/page', 'cms/page');
    }
}