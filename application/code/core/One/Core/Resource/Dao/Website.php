<?php

class One_Core_Resource_Dao_Website
    extends One_Core_Dao_Database_Table
{
    protected $_parentWebsite = null;

    public function _construct($data)
    {
        $this->_init('core/website', 'core/website');
    }

    public function getParentWebsite()
    {
        if ($this->_parentWebsite === null) {
            $parentId = $this->getParentWebsiteId();
            if ($this->getId() != $parentId) {
                $this->_parentWebsite = $this->app()
                    ->getModel('core/website')
                    ->load($parentId)
                ;
            } else {
                $this->_parentWebsite = false;
            }
        }
        return $this->_parentWebsite;
    }
}