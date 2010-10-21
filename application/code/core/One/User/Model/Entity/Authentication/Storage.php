<?php

class One_User_Model_Entity_Authentication_Storage
    implements Zend_Auth_Storage_Interface
{
    const DEFAULT_KEY = 'auth_storage';

    protected $_session = null;

    protected $_key = null;

    public function __construct($sessionObject = null, $authKey = self::DEFAULT_KEY)
    {
        if ($sessionObject != null) {
            $this->_session = $sessionObject;
        }
        $this->_key = $authKey;
    }

    public function read()
    {
        return $this->_session->getData($this->_key);
    }

    public function write($identity)
    {
        return $this->_session->setData($this->_key, $identity);
    }

    public function clear()
    {
        return $this->_session->unsetData($this->_key);
    }

    public function isEmpty()
    {
        return !$this->_session->hasData($this->_key);
    }
}