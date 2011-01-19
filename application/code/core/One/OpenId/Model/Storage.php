<?php

class One_OpenId_Model_Storage
    extends Zend_OpenId_Provider_Storage
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
     * Stores information about session identified by $handle
     *
     * @param string $handle association handle
     * @param string $macFunc HMAC function (sha1 or sha256)
     * @param string $secret shared secret
     * @param string $expires expiration UNIX time
     * @return void
     */
    public function addAssociation($handle, $macFunc, $secret, $expires)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));
//        $this->app()
//            ->getModel('openid/association')
//            ->save()
//        ;
//        $this->app()->log();
    }

    /**
     * Gets information about association identified by $handle
     * Returns true if given association found and not expired and false
     * otherwise
     *
     * @param string $handle assiciation handle
     * @param string &$macFunc HMAC function (sha1 or sha256)
     * @param string &$secret shared secret
     * @param string &$expires expiration UNIX time
     * @return bool
     */
    public function getAssociation($handle, &$macFunc, &$secret, &$expires)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

//        $this->app()
//            ->getModel('openid/association')
//            ->load($handle)
//        ;

        return true;
    }

    /**
     * Register new user with given $id and $password
     * Returns true in case of success and false if user with given $id already
     * exists
     *
     * @param string $id user identity URL
     * @param string $password encoded user password
     * @return bool
     */
    public function addUser($id, $password)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

        return false;
    }

    /**
     * Returns true if user with given $id exists and false otherwise
     *
     * @param string $id user identity URL
     * @return bool
     */
    public function hasUser($id)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));
//        $this->app()
//            ->getModel('openid/association')
//            ->save()
//        ;

        return true;
    }

    /**
     * Verify if user with given $id exists and has specified $password
     *
     * @param string $id user identity URL
     * @param string $password user password
     * @return bool
     */
    public function checkUser($id, $password)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

        return true;
    }

    /**
     * Returns array of all trusted/untrusted sites for given user identified
     * by $id
     *
     * @param string $id user identity URL
     * @return array
     */
    public function getTrustedSites($id)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

        return array();
    }

    /**
     * Stores information about trusted/untrusted site for given user
     *
     * @param string $id user identity URL
     * @param string $site site URL
     * @param mixed $trusted trust data from extensions or just a boolean value
     * @return bool
     */
    public function addSite($id, $site, $trusted)
    {
        $this->_log(__METHOD__ . " " . implode(', ', func_get_args()));

        return true;
    }
}