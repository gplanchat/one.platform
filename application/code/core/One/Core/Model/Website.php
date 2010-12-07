<?php

class One_Core_Model_Website
    extends One_Core_Bo_EntityAbstract
{
    public function _construct($options)
    {
        $this->_init('core/website');

        return parent::_construct($options);
    }
}