<?php

class One_Core_Model_Config
    extends One_Core_Bo_EntityAbstract
{
    protected function _construct($options)
    {
        $this->_init('core/config');

        return parent::_construct($options);
    }
}