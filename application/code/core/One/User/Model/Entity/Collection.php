<?php

class One_User_Model_Entity_Collection
    extends One_Core_Bo_CollectionAbstract
{
    protected function _construct($options)
    {
        $this->_init('user/entity', 'user/entity', 'user/entity.data-mapper');

        return parent::_construct($options);
    }
}