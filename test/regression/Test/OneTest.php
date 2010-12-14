<?php

class Test_OneTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        One::setDefaultRequestObject(new Zend_Controller_Request_HttpTestCase());
        One::setDefaultResponseObject(new Zend_Controller_Response_HttpTestCase());
    }

    public function testDefaultAppTermination()
    {
        One::setDefaultRequestObject(new Zend_Controller_Request_HttpTestCase());
        One::setDefaultResponseObject(new Zend_Controller_Response_HttpTestCase());

        One::app();
        $defaultWebsiteId = One::getDefaultWebsiteId();

        $this->assertTrue(One::terminateApp($defaultWebsiteId));
        $this->assertFalse(One::terminateApp($defaultWebsiteId));
    }
}