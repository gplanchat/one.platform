<?php

class One_OpenId_Model_UserAdapter
    extends Zend_OpenId_Provider_User
{
    public function __construct()
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));
    }

    protected function _log($message)
    {
        static $logger = null;

        if ($logger === null) {
            $logger = new Zend_Log(new Zend_Log_Writer_Stream(
                APPLICATION_PATH . DS . 'var' . DS . 'log' . DS . 'debug.log'
                ));
        }

        $logger->debug($message);
    }

    /**
     * Stores information about logged in user
     *
     * @param string $id user identity URL
     * @return bool
     */
    public function setLoggedInUser($id)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

        return true;
    }

    /**
     * Returns identity URL of logged in user or false
     *
     * @return mixed
     */
    public function getLoggedInUser()
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

        return 'http://one/openid/gplanchat';
    }

    /**
     * Performs logout. Clears information about logged in user.
     *
     * @return bool
     */
    public function delLoggedInUser()
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

        return true;
    }
}