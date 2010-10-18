<?php

class One_Core_Helper_Standard
    extends One_Core_HelperAbstract
{
    protected $_helper = array();

    protected $_loader = null;

    protected function _construct($options)
    {
        $this->_loader = new Zend_Loader_PluginLoader(array(
            'Zend_View_Helper' => 'Zend/View/Helper'
            ));

        parent::_construct($options);
    }

    public function __call($method, $params)
    {
        $name = ucfirst($method);
        if (!isset($this->_helper[$name])) {
            $class = $this->_loader->load($name);
            $this->_helper[$name] = new $class();
        }
        if (method_exists($this->_helper[$name], 'setView')) {
            $this->_helper[$name]->setView($this->getBlock());
        }

        return call_user_func_array(
            array($this->_helper[$name], $name),
            $params
        );
    }
}