<?php

class Legacies_Chat_MessageController
    extends One_User_Controller_AuthenticatedAbstract
{
    public function indexAction()
    {
//        $collection = $this->_loadMessageCollection(50);
//
//        $messageList = array();
//        foreach ($collection as $message) {
//            $messageList[] = array(
//                'id' => $message->getId(),
//                'message' => $message->getMessage(),
//                'author'  => $message->getUser()
//                );
//        }
        $this->_getSession()->unsLatestChatUpdate();

        $this->loadLayout('chat');
        $this->renderLayout();
    }

    protected function _loadMessageCollection($limit = 50, $since = null)
    {
        $collection = $this->app()
            ->getModel('legacies.chat/message.collection')
            ->setPageSize($limit)
            ->sort(array('timestamp' => 'DESC'))
        ;

        if ($since !== null) {
            $collection->addFilters(array(
                One_Core_Bo_CollectionInterface::FILTER_GREATER_THAN => array(
                    'messageid' => $since
                    )
                ));
        }
        return $collection->load();
    }

    public function sendMessageAjaxAction()
    {
        if (!$this->_isLoggedIn()) {
            $this->_response
                ->setHeader('Content-Type', 'application/x-json')
                ->setBody(Zend_Json::encode(array(
                    'error'   => true,
                    'message' => 'You should be logged-in in order to post a message.' // TODO: add translator
                    )));
            return;
        }

        $message = $this->_request->getParam('message', null);
        if ($message === null) {
            $this->_response
                ->setHeader('Content-Type', 'application/x-json')
                ->setBody(Zend_Json::encode(array(
                    'error'   => true,
                    'message' => 'Your message is empty.' // TODO: add translator
                    )));
            return;
        }
        $timestamp = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');

        $postData = array(
            'message'   => $message,
            'timestamp' => $timestamp,
            'user'      => $this->_getUser()->getUsername()
            );

        $collection = $this->app()
            ->getModel('legacies.chat/message')
            ->setData($postData)
            ->save();

        $this->_forward('update-message-list-ajax');
    }

    public function updateMessageListAjaxAction()
    {
        $collection = $this->_loadMessageCollection(50, $this->_getSession()->getLatestChatUpdate());

        if ($collection->count() > 0) {
            $message = $collection->end();
            if ($message !== false) {
                $this->_getSession()->setLatestChatUpdate($message->getId());
            }

            $this->_response
//                ->setHeader('Content-Type', 'application/x-json')
                ->setBody($collection->toJson());
        } else {
            $this->_response
//                ->setHeader('Content-Type', 'application/x-json')
                ->setBody(Zend_Json::encode(array('empty' => true)));
        }
    }
}