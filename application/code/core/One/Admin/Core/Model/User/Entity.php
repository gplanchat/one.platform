<?php

class One_Admin_Core_Model_User_Entity
    extends One_User_Model_Entity
{
    public function _construct($options)
    {
        One_Core_Bo_EntityAbstract::_construct($options);

        $this->_init('admin.core/user.entity', 'admin.core/user.entity.data-mapper');

        return $this;
    }
}