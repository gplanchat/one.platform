<?php

class One_Social_Facebook_Model_Graph
    extends One_Core_Object
{
    private $_client = null;

    protected function _construct($options)
    {
        parent::_construct($options);

        $config = $this->app()->getConfig('general.facebook');

        require_once ROOT_PATH . DS . 'externals' . DS . 'facebook.php';
        $this->_client = new Facebook($config['application'], $config['secret']);
    }
}