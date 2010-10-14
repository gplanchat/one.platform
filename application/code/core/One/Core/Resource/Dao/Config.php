<?php

class One_Core_Resource_Dao_Config
    extends One_Core_Dao_Database_Table
{
    public function _construct($data)
    {
        $this->_init('core/config', 'core/config');
    }

    public function load(One_Core_Bo_EntityInterface $model, One_Core_Orm_DataMapperAbstract $mapper, $identity, $field)
    {
        parent::load($model, $mapper, $identity, $field);

        return $this;
    }
}