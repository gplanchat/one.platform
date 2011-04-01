<?php

class Legacies_Chat_Model_Message_Collection
    extends One_Core_Bo_CollectionAbstract
{
    protected function _construct($options)
    {
        $this->_init('legacies.chat/message', 'legacies.chat/message');

        return parent::_construct($options);
    }

    public function exportArray($revertOrder = true)
    {
        if (!$this->isLoaded() && $revertOrder) {
            $this->sort(array('timestamp' => One_Core_Bo_CollectionAbstract::ORDER_DESC));
        }

        $date = new Zend_Date();
        $payload = array();
        foreach ($this as $child) {
            $date->set($child->getTimestamp(), 'yyyy-MM-dd HH:mm:ss');
            array_unshift($payload, array(
                'message'   => $child->getMessage(),
                'author'    => $child->getUser(),
                'timestamp' => $date->toString(Zend_Date::DATETIME_SHORT),
                'id'        => $child->getId()
                ));
        }
        return $payload;
    }

    public function toJson()
    {
        return Zend_Json::encode($this->exportArray(true));
    }
}