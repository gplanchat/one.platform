<?php

interface One_Core_ObjectInterface
{
    /**
     * @return One_Core_Model_Application
     */
    public function app();

    /**
     *
     * @param string $moduleName
     * @param array $options
     * @param One_Core_Model_Application $app
     */
    public function __construct($moduleName, Array $options = array(), One_Core_Model_Application $app = null);
}