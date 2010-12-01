<?php

class One_Admin_Core_Model_Config
    extends One_Core_Bo_EntityAbstract
{
    protected $_forms = null;
    protected $_grids = null;

    protected function _construct($options)
    {
        $this->_init('admin.core/config');

        return parent::_construct($options);
    }

    /**
     * TODO PHPDoc
     *
     * @param string $fieldsetName
     * @return array
     */
    public function getGrid($gridName)
    {
        if ($this->_grids === null) {
            $this->_grids = $this->app()->getConfig('general.grids');
        }
        if (!isset($this->_grids[$gridName])) {
            return null;
        }
        return $this->_grids[$gridName];
    }

    /**
     * TODO PHPDoc
     *
     * @param string $fieldsetName
     * @return array
     */
    public function getForm($formName)
    {
        if ($this->_forms === null) {
            $this->_forms = $this->app()->getConfig('general.forms');
        }
        if (!isset($this->_forms[$formName])) {
            return null;
        }
        return $this->_forms[$formName];
    }
}