<?php

class One_Core_Block_Html_Page
    extends One_Core_Block_Html
{
    protected $_bodyClasses = array();

    protected function _construct($options)
    {
        $this->doctype(Zend_View_Helper_Doctype::XHTML5);

        return parent::_construct($options);
    }

    public function addBodyClass($class)
    {
        if (is_string($class)) {
            $this->_bodyClasses[] = $class;
        } else if (is_array($class)) {
            foreach ($class as $classItem) {
                $this->_bodyClasses[] = $classItem;
            }
        }
        return $this;
    }

    public function setBodyClasses($classesList)
    {
        $this->_bodyClasses = $classesList;

        return $this->_bodyClasses;
    }

    public function getBodyClasses()
    {
        return $this->_bodyClasses;
    }

    public function renderBodyClasses()
    {
        return implode(' ', $this->getBodyClasses());
    }
}