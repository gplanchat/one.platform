<?php

class One_Core_Model_Website_Collection
    extends One_Core_Bo_CollectionAbstract
{
    protected function _construct($options)
    {
        $this->_init('core/website', 'core/website');

        return parent::_construct($options);
    }
}