<?php

class One_OpenId_ProviderController
    extends One_Core_ControllerAbstract
{
    private $_provider = null;
    private $_storage = null;
    private $_userAdapter = null;

    protected function _getProvider()
    {
        if (is_null($this->_provider)) {
            $this->_provider = new Zend_OpenId_Provider(
                "login",
                "trust",
                $this->_getUserAdapter(),
                $this->_getStorage()
                );
        }
        return $this->_provider;
    }

    protected function _getUserAdapter()
    {
        if ($this->_userAdapter === null) {
            $this->_userAdapter = $this->app()->getModel('openid/user-adapter');
        }
        return $this->_userAdapter;
    }

    protected function _getStorage()
    {
        if ($this->_storage === null) {
            $this->_storage = $this->app()->getModel('openid/storage');
        }
        return $this->_storage;
    }

    public function indexAction()
    {
        $server = $this->_getProvider();
//        $sreg = new Zend_OpenId_Extension_Sreg(array(
//            'nickname' =>'test',
//            'email' => 'test@test.com'
//        ));
        $ret = $server->handle(null, null, $this->getResponse());

        var_dump($ret);
        if (is_string($ret)) {
            $this->getResponse()
                ->setBody($ret)
            ;
        } else if ($ret !== true) {
            $this->getResponse()
                ->setRawHeader('HTTP/1.0 403 Forbidden')
                ->setBody('Forbidden')
            ;
        }
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $server = $this->_getProvider();

        if ($request->isPost()) {
            $action     = $request->getPost('openid_action');
            $identifier = $request->getPost('openid_identifier');
            $credential = $request->getPost('openid_password');

            if ($action === 'login' && $identifier !== null && $credential !== null) {
                if($server->login($identifier, $credential)) {
                    Zend_OpenId::redirect("/openid/{$this->_getParam('username')}", null, $this->getResponse());
                } else {
                    // TODO
                }
            }
        }
    }

    public function trustAction()
    {
        $request = $this->getRequest();
        $server = $this->_getProvider();

//        $this->view->site = $server->getSiteRoot($_GET);
//        $this->view->user = $server->getLoggedInUser();

//        $userDetails = new Default_Model_UserDetails();
//        $sreg = new Zend_OpenId_Extension_Sreg($userDetails->getUserDetailsFromOpenId($server->getLoggedInUser()));

        if ($request->isPost() && $request->getPost('openid_action') === 'trust') {
            if ($request->getPost('allow') !== null) {
                if ($request->getPost('forever') !== null) {
                    $server->allowSite($server->getSiteRoot($request->getQuery())/*, $sreg*/);
                }

                $server->respondToConsumer($request->getQuery(), null, $this->getResponse());
            } else if ($request->getPost('deny') !== null) {
                if ($request->getPost('forever') !== null) {
                    $server->denySite($server->getSiteRoot($request->getQuery()));
                }

                Zend_OpenId::redirect(urldecode($request->getQuery('openid_return_to')), array(
                    'openid.mode' => 'cancel'
                    ), $this->getResponse());
            }
        }
    }
}