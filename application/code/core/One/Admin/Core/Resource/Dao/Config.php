<?php

class One_Admin_Core_Resource_Dao_Config
    extends One_Core_Dao_Database_Table
{
    public function _construct($data)
    {
        $this->_init('admin.core/config', 'admin.core/config');
    }
}