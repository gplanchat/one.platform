<?php

class One_OpenId_AuthController
    extends One_Core_ControllerAbstract
{
    private $_provider = null;
    private $_storage = null;
    private $_userAdapter = null;

    protected function _getProvider()
    {
        if (is_null($this->_provider)) {
            $this->_provider = new Zend_OpenId_Provider(
                'login',
                'trust',
                $this->_getUserAdapter(),
                $this->_getStorage()
                );
        }
        return $this->_provider;
    }

    protected function _getUserAdapter()
    {
        if (is_null($this->_userAdapter)) {
            $this->_storage = $this->app()->getModel('openid/user-adapter');
        }
        return $this->_userAdapter;
    }

    protected function _getStorage()
    {
        if (is_null($this->_storage)) {
            $this->_storage = $this->app()->getModel('openid/storage', DATA_PATH);
        }
        return $this->_storage;
    }

    public function indexAction()
    {
        $returnCode = $this->_getProvider()->handle();
        if (is_string($returnCode)) {
            echo $returnCode;
        } else if ($returnCode !== true) {
            $this->app()->throwException('openid/forbidden');
        }
    }

    public function loginAction()
    {
        if ($this->getRequest()->isPost()) {
            $credential = (string) $this->_getParam('openid_password', '');

            if (!empty($this->_identity) && !empty($credential)) {
                $this->_getProvider()->login($this->_identifier, $credential);
                $encodedIdentity = urlencode($this->_identity);
                Zend_OpenId::redirect("/{$encodedIdentity}", $_GET);
            }
        }
    }
}